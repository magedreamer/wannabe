<?php
/**
 * 

 * @package    Uni_Upload


 */
class Tools_Upload_Model_Upload extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('upload/upload');
    }

    public function getAllAvailUploadIds(){
        $collection = Mage::getResourceModel('upload/upload_collection')
                        ->getAllIds();
        return $collection;
    }

    public function getAllUploads() {
        $collection = Mage::getResourceModel('upload/upload_collection');
        $collection->getSelect()->where('status = ?', 1);
        $data = array();
        foreach ($collection as $record) {
            $data[$record->getId()] = array('value' => $record->getId(), 'label' => $record->getfilename());
        }
        return $data;
    }

    public function getDataByUploadIds($uploadIds) {
        $data = array();
        if ($uploadIds != '') {
            $collection = Mage::getResourceModel('upload/upload_collection');
            $collection->getSelect()->where('upload_id IN (' . $uploadIds . ')')->order('sort_order');
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