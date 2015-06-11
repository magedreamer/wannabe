<?php
/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Categorytools
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tools_Categorytools_Block_Adminhtml_Categorytoolsgroup_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'categorytools';
        $this->_controller = 'adminhtml_categorytoolsgroup';

        $this->_updateButton('save', 'label', Mage::helper('categorytools')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('categorytools')->__('Delete Item'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
                ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('categorytools_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'categorytools_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'categorytools_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }

            function showCategorytoolsEfects(){
                var typeId=$('animation_type').value;
                var reqClass = ((typeId==1)?'required-entry':'');
                var preReqClass = ((typeId==1)?'':'required-entry');
                $('categorytools_effects').disabled = ((typeId==1)?false:true);
                $('pre_categorytools_effects').disabled = ((typeId==1)?true:false);
                $('categorytools_effects').addClassName(reqClass);
                $('pre_categorytools_effects').addClassName(preReqClass);
            }

            Event.observe('animation_type', 'change', function(){
                    showCategorytoolsEfects();
                });
            Event.observe(window, 'load', function(){
                    showCategorytoolsEfects();
                });
        ";
    }

    public function getHeaderText() {
        if (Mage::registry('categorytoolsgroup_data') && Mage::registry('categorytoolsgroup_data')->getId()) {
            return Mage::helper('categorytools')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('categorytoolsgroup_data')->getGroupName()));
        } else {
            return Mage::helper('categorytools')->__('Add Item');
        }
    }

}