<?php

/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Categorytools
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tools_Categorytools_Block_Adminhtml_Categorytools_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('categorytools_form', array('legend' => Mage::helper('categorytools')->__('Item information')));
        $version = substr(Mage::getVersion(), 0, 3);
        //$config = (($version == '1.4' || $version == '1.5') ? "'config' => Mage::getSingleton('categorytools/wysiwyg_config')->getConfig()" : "'class'=>''");
	$model = Mage::registry('categorytools_data');

	$model->setCategory_name($model->getCategory_id());
        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('categorytools')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
        ));

	$options = array();
	$rootCategoryId = Mage::app()->getStore(1)->getRootCategoryId();
	$_category = Mage::getModel('catalog/category')->load($rootCategoryId);
	$_subcategories = $_category->getChildrenCategories();

	foreach($_subcategories as $cat)
	{
		$options[$cat->getId()] = $cat->getName();
	}

	 $fieldset->addField('category_name', 'select', 
            array (
                'label' => Mage::helper('tools_feature')->__('category_name'), 
                'title' => Mage::helper('tools_feature')->__('category_name'), 
                'name' => 'category_name', 
                'required' => false,
                'values' => $options,
            )
        );

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

        if (Mage::getSingleton('adminhtml/session')->getCategorytoolsData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getCategorytoolsData());
            Mage::getSingleton('adminhtml/session')->setCategorytoolsData(null);
        } elseif (Mage::registry('categorytools_data')) {
            $form->setValues(Mage::registry('categorytools_data')->getData());
        }
        return parent::_prepareForm();
    }

}