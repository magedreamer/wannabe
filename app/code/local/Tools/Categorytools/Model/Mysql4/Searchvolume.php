<?php
/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Categorytools
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tools_Categorytools_Model_Mysql4_Searchvolume extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        // Note that the categorytools_id refers to the key field in your database table.
        $this->_init('categorytools/searchvolume', 'id');
    }
    public function searchVolume($params)
    {
        if(isset($params['query_text']) && $params['query_text'] != null)
        {
            $write = Mage::getSingleton('core/resource')->getConnection('core_write');
            
//            $searchVolumeCollection = Mage::getResourceModel('categorytools/searchvolume_collection');
//         
//            $searchVolumeCollection->getSelect()
//                    ->where('main_table.query_text = ?', $query_text);    
//            foreach($searchVolumeCollection as $searchVolume)
//            {
//                $query_id = $searchVolume->getData('query_id');
//            }
            
            $bind = array(':query' => $params['query_text']);
            $sql = "SELECT query_id FROM catalogsearch_query WHERE query_text = :query";
            $resultQueryId = $write->query($sql, $bind);
            $arrQueryId = $resultQueryId->fetch();
            $query_id = $arrQueryId['query_id'];

            $select_volume = "select volume from catalogsearch_result where query_id=$query_id and product_id=".$params['productId'];
            $resultVolume = $write->query($select_volume);
            $arrVolume = $resultVolume->fetch();
            $volume = $arrVolume['volume'];
            
            $session_id = session_id();
            if($volume == null || $volume == '')
            {
                $insert = "update catalogsearch_result set volume = 1 where (query_id=$query_id and product_id=".$params['productId'].")";
                $write->query($insert);
                
                //$_SESSION[$session_id.$productId] = 1;
                setcookie($session_id.$params['productId'], 1, time()+(3600*12));
            }
            else
            {
                /*if(!isset($_SESSION[$session_id.$productId]))
                {
                    $volume = (int) $arrVolume['volume'];
                    $update = "update catalogsearch_result set volume=" . ($volume + 1) . " where (query_id=$query_id and product_id=$productId)";
                    $write->query($update);
                    
                    $_SESSION[$session_id.$productId] = 1;
                }*/
                if(!isset($_COOKIE[$session_id.$params['productId']]))
                {
                    $volume = (int) $arrVolume['volume'];
                    $update = "update catalogsearch_result set volume=" . ($volume + 1) . " where (query_id=$query_id and product_id=".$params['productId'].")";
                    $write->query($update);
                    
                    //$_SESSION[$session_id.$productId] = 1;
                    setcookie($session_id.$params['productId'], 1, time()+(3600*12));
                }
            }
        }
    }
}