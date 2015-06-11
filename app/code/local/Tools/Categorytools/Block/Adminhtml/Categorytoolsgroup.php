<?php
/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Categorytools
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tools_Categorytools_Block_Adminhtml_Categorytoolsgroup extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_categorytoolsgroup';
        $this->_blockGroup = 'categorytools';
        $this->_headerText = Mage::helper('categorytools')->__('Categorytools Group Manager');
        $this->_addButtonLabel = Mage::helper('categorytools')->__('Add Categorytools Group');
        parent::__construct();
    }

}