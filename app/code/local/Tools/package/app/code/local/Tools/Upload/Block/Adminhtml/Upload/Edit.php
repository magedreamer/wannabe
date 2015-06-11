<?php
/**
 * 

 * @package    Uni_Upload


 */
class Tools_Upload_Block_Adminhtml_Upload_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'upload';
        $this->_controller = 'adminhtml_upload';

        $this->_updateButton('save', 'label', Mage::helper('upload')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('upload')->__('Delete Item'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
                ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('upload_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'upload_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'upload_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }

            function showTypeContents(){
                var typeId=$('upload_type').value;
                var show = ((typeId==0)?'block':'none');
                var hide = ((typeId==0)?'none':'block');
                $('filename').setStyle({display:show});
                $('filename_delete').setStyle({display:show});
                $('upload_content').setStyle({display:hide});
                setTimeout('uploadContentType()',1000);
                alert($('filename').getStyle('display'))
            }
         
            function uploadContentType(){
                var typeId=$('upload_type').value;
                var hide = ((typeId==0)?'none':'block');
                $('buttonsupload_content').setStyle({display:hide});
                $('upload_content_parent').setStyle({display:hide});
            }


            /* Event.observe('upload_type', 'change', function(){
                    showTypeContents();
                });
            Event.observe(window, 'load', function(){
                    showTypeContents();
                }); */
        ";
    }

    public function getHeaderText() {
        if (Mage::registry('upload_data') && Mage::registry('upload_data')->getId()) {
            return Mage::helper('upload')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('upload_data')->getTitle()));
        } else {
            return Mage::helper('upload')->__('Add Item');
        }
    }

}