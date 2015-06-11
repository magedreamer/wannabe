<?php
/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Categorytools
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tools_Categorytools_Model_Searchvolume extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('categorytools/searchvolume');
    }

    function cronSearchVolume()
    {
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
    
        $select = 'select query_id,product_id,relevance,volume,relevance_origin from catalogsearch_result where volume is not null';
        $arrQueryId = $write->query($select);
        $arrResult = $arrQueryId->fetchAll();
        
        if($arrResult != false || !empty($arrResult))
        {
            foreach($arrResult as $result)
            {
                $relevance = $result['volume'] + $result['relevance_origin'];
                if($relevance != $result['relevance'])
                {
                    $update = 'update catalogsearch_result set relevance='.($result['volume']+$result['relevance_origin']).' where query_id='.$result['query_id'].' and product_id='.$result['product_id'];
                    $write->query($update);
                }
            }
        }
    }
}
