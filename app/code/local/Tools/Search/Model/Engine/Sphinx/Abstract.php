<?php

class Tools_Search_Model_Engine_Sphinx_Abstract
{
    protected $_indexer = null;

    protected $_sphinx = null;

    public function getSphinxEngine()
    {
        if ($this->_sphinx === null) {
            $this->_sphinx = Mage::getModel('awadvancedsearch/engine_sphinx');
        }
        return $this->_sphinx;
    }

    protected function _getHelper($name = null)
    {
        return Mage::helper('awadvancedsearch' . ($name ? '/' . $name : ''));
    }

    protected function _getLogHelper()
    {
        return $this->_getHelper('log');
    }

    public function setIndexer($indexer)
    {
        $this->_indexer = $indexer;
        return $this;
    }

    public function getIndexer()
    {
        return $this->_indexer;
    }

    protected function _createFullIndexConfig()
    {
        if (!$this->_isConfigCreated()) {
            return $this->getSphinxEngine()->createConfigFile();
        }
        return true;
    }

    protected function _getIndexName()
    {
        return $this->getIndexer()->getIndex()->getIndexName();
    }

    protected function _getDeltaIndexName()
    {
        return $this->_getIndexName() . 'delta';
    }

    public function removeVarDir($indexName = null)
    {
        if ($this->getSelfVarDir()) {
            if ($indexName) {
                foreach (glob($this->getSelfVarDir() . DS . $indexName . '.*') as $file) {
                    @unlink($file);
                }
            } else {
                Mage::helper('awadvancedsearch')->rrmdir($this->getSelfVarDir());
            }
        }
        return $this;
    }

    public function getSelfVarDir()
    {
        $_varDir = null;
        if ($this->getIndexer() && $this->getIndexer()->getIndex() && $this->getIndexer()->getIndex()->getId()) {
            $_varDir = $this->_getHelper()->getVarDir($this->getIndexer()->getIndex()->getId());
        }
        return $_varDir;
    }

    public function reindex($indexer = null, $delta = false)
    {
        if ($indexer) {
            $this->setIndexer($indexer);
        } else {
            $indexer = $this->getIndexer();
        }
        if ($indexer) {
            if ($this->_createFullIndexConfig()) {
                $indexName = $delta ? $this->_getDeltaIndexName() : $this->_getIndexName();
                $this->removeVarDir($indexName);
                $this->getSelfVarDir();
                $this->_getLogHelper()->log($this, 'Sphinx: Executing indexer for ' . $indexName);
                $path = Mage::helper('awadvancedsearch/config')->getSphinxServerPath();
                ob_start();
                //Mage::log($path . AW_Advancedsearch_Model_Engine_Sphinx::INDEXER_CALL . ' -c ' . $this->getSphinxEngine()->getConfigFileName() . ' ' . $indexName, null, 'advancedsearch.log');
                passthru($path . AW_Advancedsearch_Model_Engine_Sphinx::INDEXER_CALL . ' -c ' . $this->getSphinxEngine()->getConfigFileName() . ' ' . $indexName, $_ret);
                $_out = ob_get_contents();
                //Mage::log($_out, null, 'advancedsearch.log');
                ob_end_clean();
                if ($_ret === 0) {
                    $indexer->getIndex()->setLastUpdate();
                } else {
                    $this->_getLogHelper()->log($this, 'Sphinx error', null, $_out);
                }
                $this->_getLogHelper()->log($this, 'Sphinx: Done, returned value ' . $_ret);
                return $_ret === 0;
            }
        }
        return false;
    }

    public function reindexDelta($indexer = null)
    {
        return $this->reindex($indexer, true);
    }

    protected function _isConfigCreated()
    {
        if (file_exists($this->getSphinxEngine()->getConfigFileName())) {
            $config = @file_get_contents($this->getSphinxEngine()->getConfigFileName());
            if ($config) {
                return (bool)preg_match("/{$this->getIndexer()->getIndex()->getIndexName()}/mi", $config);
            }
        }
        return false;
    }

    public function mergeDeltaWithMain($indexer = null)
    {
        if ($indexer) {
            $this->setIndexer($indexer);
        } else {
            $indexer = $this->getIndexer();
        }
        if ($indexer) {
            $mainIndex = $this->_getIndexName();
            $deltaIndex = $this->_getDeltaIndexName();
            $this->_getLogHelper()->log($this, 'Sphinx: merging ' . $deltaIndex . ' with ' . $mainIndex);
            ob_start();
            passthru(AW_Advancedsearch_Model_Engine_Sphinx::INDEXER_CALL . ' -c ' . $this->getSphinxEngine()->getConfigFileName() . ' --merge ' . $mainIndex . ' ' . $deltaIndex . ' --rotate', $_ret);
            $_out = ob_get_contents();
            ob_end_clean();
            if ($_ret === 0) {
                $indexer->getIndex()->setLastUpdate();
            } else {
                $this->_getLogHelper()->log($this, 'Sphinx error', null, $_out);
            }
            $this->_getLogHelper()->log($this, 'Sphinx: Done, returned value ' . $_ret);
            return $_ret === 0;
        }
        return false;
    }

    public function getConfigFileContent()
    {
        $indexName = $this->_getIndexName();
        $deltaIndexName = $this->_getDeltaIndexName();
        $sphinxConfig = Mage::getStoreConfig('awadvancedsearch/sphinx');
        $_files = array('index_path' => $this->getSelfVarDir() . DS . $indexName,
            'delta_index_path' => $this->getSelfVarDir() . DS . $deltaIndexName);
        $fcontent = <<<FILE
source {$indexName} : dbconnect
{
    sql_query_pre = SET NAMES utf8
    sql_query = SELECT * FROM {$this->getIndexer()->getTableName()}
    sql_attr_uint = _updated
    sql_ranged_throttle = 0
}
source {$deltaIndexName} : {$indexName}
{
    sql_query = SELECT * FROM {$this->getIndexer()->getTableName()} WHERE `_updated` = 1
}
index {$indexName}
{
    source = {$indexName}
    path = {$_files['index_path']}
    docinfo = extern
    mlock = 0
    morphology = stem_enru
    min_word_len = 2
    charset_type = utf-8
    charset_table = 0..9, A..Z->a..z, _, a..z, U+410..U+42F->U+430..U+44F, U+430..U+44F, U+00C0->a, U+00C1->a, U+00C2->a, U+00C3->a, U+00C4->a, U+00E0->a, U+00E1->a, U+00E2->a, U+00E3->a, U+0102->a, U+0103->a, U+1EA0->a, U+1EA1->a, U+1EA2->a, U+1EA3->a, U+1EA4->a, U+1EA5->a, U+1EA6->a, U+1EA7->a, U+1EA8->a, U+1EA9->a, U+1EAA->a, U+1EAB->a, U+1EAC->a, U+1EAD->a, U+1EAE->a, U+1EAF->a, U+1EB0->a, U+1EB1->a, U+1EB2->a, U+1EB3->a, U+1EB4->a, U+1EB5->a, U+1EB6->a, U+1EB7->a, U+00D2->o, U+00D3->o, U+00D4->o, U+00D5->o, U+00F2->o, U+00F3->o, U+00F4->o, U+00F5->o, U+01A0->o, U+01A1->o, U+1ECC->o, U+1ECD->o, U+1ECE->o, U+1ECF->o, U+1ED0->o, U+1ED1->o, U+1ED2->o,U+1ED3->o, U+1ED4->o, U+1ED5->o, U+1ED6->o, U+1ED7->o, U+1ED8->o, U+1ED9->o, U+1EDA->o, U+1EDB->o, U+1EDC->o, U+1EDD->o, U+1EDE->o, U+1EDF->o, U+1EE0->o, U+1EE1->o, U+1EE2->o, U+1EE3->o, U+00C8->e, U+00C9->e, U+00CA->e, U+00E8->e, U+00E9->e, U+00EA->e, U+1EB8->e, U+1EB9->e, U+1EBA->e, U+1EBB->e, U+1EBC->e, U+1EBD->e, U+1EBE->e, U+1EBF->e, U+1EC0->e, U+1EC1->e, U+1EC2->e, U+1EC3->e, U+1EC4->e, U+1EC5->e, U+1EC6->e, U+1EC7->e, U+00CC->i, U+00CD->i, U+00EC->i, U+00ED->i, U+0128->i, U+0129->i, U+1EC8->i, U+1EC9->i, U+1ECA->i, U+1ECB->i, U+00DD->y, U+00FD->y, U+1EF2->y, U+1EF3->y, U+1EF4->y, U+1EF5->y, U+1EF6->y, U+1EF7->y, U+1EF8->y, U+1EF9->y, U+00D9->u, U+00DA->u, U+00F9->u, U+00FA->u, U+0168->u, U+0169->u, U+01AF->u, U+01B0->u, U+1EE4->u, U+1EE5->u, U+1EE6->u, U+1EE7->u, U+1EE8->u, U+1EE9->u, U+1EEA->u, U+1EEB->u, U+1EEC->u, U+1EED->u, U+1EEE->u, U+1EEF->u, U+1EF0->u, U+1EF1->u, U+0110->d, U+0111->d
    min_infix_len = 2
    enable_star = 1
}
index {$deltaIndexName} : {$indexName}
{
    source = {$deltaIndexName}
    path = {$_files['delta_index_path']}
}

FILE;
        return $fcontent;
    }
}
