<?php
/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Categorytools
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tools_Categorytools_Block_Adminhtml_Categorytoolsgroup_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('categorytoolsgroup_form', array('legend' => Mage::helper('categorytools')->__('Item information')));
        $animations = Mage::getSingleton('categorytools/status')->getAnimationArray();
        $preAnimations = Mage::getSingleton('categorytools/status')->getPreAnimationArray();


        $fieldset->addField('group_name', 'text', array(
            'label' => Mage::helper('categorytools')->__('Categorytools Group Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'group_name',
        ));

        if (Mage::registry('categorytoolsgroup_data')->getId() == null) {
            $fieldset->addField('group_code', 'text', array(
                'label' => Mage::helper('categorytools')->__('Categorytools Group Code'),
                'class' => 'required-entry',
                'name' => 'group_code',
                'required' => true,
            ));
        }

        $fieldset->addField('categorytools_width', 'text', array(
            'label' => Mage::helper('categorytools')->__('Categorytools Width [in px]'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'categorytools_width',
        ));

        $fieldset->addField('categorytools_height', 'text', array(
            'label' => Mage::helper('categorytools')->__('Categorytools Height [in px]'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'categorytools_height',
        ));

        $fieldset->addField('animation_type', 'select', array(
            'label' => Mage::helper('categorytools')->__('Categorytools Animation'),
            'name' => 'animation_type',
            'required' => false,
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('categorytools')->__('Pre-defined Animation'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('categorytools')->__('Custom Animation'),
                ),
            ),
        ));

        $fieldset->addField('pre_categorytools_effects', 'select', array(
            'label' => Mage::helper('categorytools')->__('Pre-Defined Categorytools Effects'),
            'name' => 'pre_categorytools_effects',
            'required' => true,
            'values' => $preAnimations
        ));

        $fieldset->addField('categorytools_effects', 'select', array(
            'label' => Mage::helper('categorytools')->__('Custom Categorytools Effects'),
            'name' => 'categorytools_effects',
            'required' => true,
            'values' => $animations
        ));       
        

        $fieldset->addField('show_title', 'select', array(
            'label' => Mage::helper('categorytools')->__('Display Title'),
            'class' => 'required-entry',
            'name' => 'show_title',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('categorytools')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('categorytools')->__('No'),
                ),
            ),
        ));

        $fieldset->addField('show_content', 'select', array(
            'label' => Mage::helper('categorytools')->__('Display Content'),
            'class' => 'required-entry',
            'name' => 'show_content',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('categorytools')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('categorytools')->__('No'),
                ),
            ),
        ));

        $fieldset->addField('link_target', 'select', array(
            'label' => Mage::helper('categorytools')->__('Target'),
            'name' => 'link_target',
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('categorytools')->__('New Window'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('categorytools')->__('Same Window'),
                ),
            ),
        ));
        
        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('categorytools')->__('Status'),
            'class' => 'required-entry',
            'name' => 'status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('categorytools')->__('Enabled'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('categorytools')->__('Disabled'),
                ),
            ),
        ));

//        $fieldset->addField('categorytools_ids', 'multiselect', array(
//            'label' => Mage::helper('categorytools')->__('Categorytools Images'),
//            'class' => 'required-entry',
//            'required' => true,
//            'name' => 'categorytools_ids[]',
//            'values' => $categorytoolsData,
//        ));


        if (Mage::getSingleton('adminhtml/session')->getCategorytoolsgroupData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getCategorytoolsgroupData());
            Mage::getSingleton('adminhtml/session')->setCategorytoolsgroupData(null);
        } elseif (Mage::registry('categorytoolsgroup_data')) {
            $form->setValues(Mage::registry('categorytoolsgroup_data')->getData());
        }
        return parent::_prepareForm();
    }

}