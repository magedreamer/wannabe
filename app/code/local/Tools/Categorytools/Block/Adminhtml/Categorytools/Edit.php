<?php
/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Categorytools
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tools_Categorytools_Block_Adminhtml_Categorytools_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'categorytools';
        $this->_controller = 'adminhtml_categorytools';

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

            function showTypeContents(){
                var typeId=$('categorytools_type').value;
                var show = ((typeId==0)?'block':'none');
                var hide = ((typeId==0)?'none':'block');
                $('filename').setStyle({display:show});
                $('filename_delete').setStyle({display:show});
                $('categorytools_content').setStyle({display:hide});
                setTimeout('categorytoolsContentType()',1000);
                alert($('filename').getStyle('display'))
            }
         
            function categorytoolsContentType(){
                var typeId=$('categorytools_type').value;
                var hide = ((typeId==0)?'none':'block');
                $('buttonscategorytools_content').setStyle({display:hide});
                $('categorytools_content_parent').setStyle({display:hide});
            }


            /* Event.observe('categorytools_type', 'change', function(){
                    showTypeContents();
                });
            Event.observe(window, 'load', function(){
                    showTypeContents();
                }); */
        ";
    }

    public function getHeaderText() {
        if (Mage::registry('categorytools_data') && Mage::registry('categorytools_data')->getId()) {
            return Mage::helper('categorytools')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('categorytools_data')->getTitle()));
        } else {
            return Mage::helper('categorytools')->__('Add Item');
        }
    }

}