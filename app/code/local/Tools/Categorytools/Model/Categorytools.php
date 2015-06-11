<?php
/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Categorytools
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tools_Categorytools_Model_Categorytools extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('categorytools/categorytools');
    }

    public function getAllAvailCategorytoolsIds(){
        $collection = Mage::getResourceModel('categorytools/categorytools_collection')
                        ->getAllIds();
        return $collection;
    }

    public function getAllCategorytoolss() {
        $collection = Mage::getResourceModel('categorytools/categorytools_collection');
        $collection->getSelect()->where('status = ?', 1);
        $data = array();
        foreach ($collection as $record) {
            $data[$record->getId()] = array('value' => $record->getId(), 'label' => $record->getfilename());
        }
        return $data;
    }

    public function getDataByCategorytoolsIds($categorytoolsIds) {
        $data = array();
        if ($categorytoolsIds != '') {
            $collection = Mage::getResourceModel('categorytools/categorytools_collection');
            $collection->getSelect()->where('categorytools_id IN (' . $categorytoolsIds . ')')->order('sort_order');
            foreach ($collection as $record) {
                $status = $record->getStatus();
                if ($status == 1) {
                    $data[] = $record;
                }
            }
        }
        return $data;
    }

}