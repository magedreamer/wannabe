<?php
/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Categorytools
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tools_Categorytools_Model_Mysql4_Categorytoolsgroup extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        $this->_init('categorytools/categorytoolsgroup', 'group_id');
    }

    public function _beforeSave(Mage_Core_Model_Abstract $object) {
        $isDataValid = true;
        $id = $object->getId();
        $groupCode = $object->getGroupCode();
        $groupCollection = Mage::getModel('categorytools/categorytoolsgroup')->getCollection();
        if ($id == '' || $id == 0) {
            if ($groupCode == '') {
                throw new Exception('Categorytools Group code can\'t be blank !!');
            } else {
                $groupInfo = $groupCollection->getDuplicateGroupCode($groupCode);
                if ($groupInfo->count() > 0) {
                    $isDataValid = false;
                }
                if ($isDataValid === false) {
                    throw new Exception("Categorytools Group Code already exists !!");
                }
            }
        }
    }

}