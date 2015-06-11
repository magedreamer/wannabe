<?php
/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Categorytools
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tools_Categorytools_Model_Mysql4_Categorytools extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        // Note that the categorytools_id refers to the key field in your database table.
        $this->_init('categorytools/categorytools', 'categorytools_id');
    }
}