<?php
/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Categorytools
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tools_Categorytools_Block_Adminhtml_Categorytoolsgroup_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('categorytoolsgroup_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('categorytools')->__('Categorytools Group Information'));
    }

    protected function _beforeToHtml() {
        $this->addTab('form_section', array(
            'label' => Mage::helper('categorytools')->__('Categorytools Group'),
            'alt' => Mage::helper('categorytools')->__('Categorytools Group'),
            'content' => $this->getLayout()->createBlock('categorytools/adminhtml_categorytoolsgroup_edit_tab_form')->toHtml(),
        ));

        $this->addTab('grid_section', array(
            'label' => Mage::helper('categorytools')->__('Categorytoolss'),
            'alt' => Mage::helper('categorytools')->__('Categorytoolss'),
            'content' => $this->getLayout()->createBlock('categorytools/adminhtml_categorytoolsgroup_edit_tab_gridcategorytools')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

}