<?php
/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Categorytools
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tools_Categorytools_Model_Categorytoolsgroup extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('categorytools/categorytoolsgroup');
    }

    public function getDataByGroupCode($groupCode) {        
        $groupData = array();
        $categorytoolsData = array();
        $result = array('group_data'=>$groupData,'categorytools_data'=>$categorytoolsData);
        $collection = Mage::getResourceModel('categorytools/categorytoolsgroup_collection');
        $collection->getSelect()->where('group_code = ?', $groupCode)->where('status = 1');
        foreach ($collection as $record) {
            $categorytoolsIds = $record->getCategorytoolsIds();
            $categorytoolsModel = Mage::getModel('categorytools/categorytools');
            $categorytoolsData = $categorytoolsModel->getDataByCategorytoolsIds($categorytoolsIds);
            $result = array('group_data' => $record, 'categorytools_data' => $categorytoolsData);
        }
        return $result;
    }

    public function DeleteOldImage($categorytoolsGroupID) {
        if($categorytoolsGroupID){
            $categorytoolsGroup = Mage::getModel('categorytools/categorytoolsgroup')->load($categorytoolsGroupID);
            $groupName = $categorytoolsGroup->getGroupCode();
            $mediaDir = Mage::getBaseDir('media');
            $resizeDir = $mediaDir . DS . 'custom' . DS . 'categorytoolss' . DS . 'resize' . DS;
     
            if($categorytoolsGroup){
                $this->checkDir($resizeDir . $groupName);
                $this->removeAllFileInFoler($resizeDir . $groupName);
                return true;
            }
        }
        return false;
    }


   protected function removeAllFileInFoler($path){
        foreach (glob($path."/*.*") as $filename) {
            @unlink($filename);
        }
        return true;
   }
   
   protected function checkDir($directory) {
        if (!is_dir($directory)) {
            umask(0);
            mkdir($directory, 0777,true);
            return true;
        }
    }
}