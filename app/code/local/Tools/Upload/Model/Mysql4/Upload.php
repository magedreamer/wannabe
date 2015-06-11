<?php
/**
 * 

 * @package    Uni_Upload


 */
class Tools_Upload_Model_Mysql4_Upload extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        // Note that the upload_id refers to the key field in your database table.
        $this->_init('upload/upload', 'upload_id');
    }
}