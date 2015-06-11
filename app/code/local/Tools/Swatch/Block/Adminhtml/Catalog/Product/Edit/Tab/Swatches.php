<?php
class Tools_Swatch_Block_Adminhtml_Catalog_Product_Edit_Tab_Swatches
    extends Mage_Adminhtml_Block_Widget 
        implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected $_optionsCollection;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('swatch/catalog/product/edit/swatches.phtml');
	
    }
    
    protected function _getProduct()
    {
        return Mage::registry('current_product');
    }
    
    public function getTabLabel()
    {
        return $this->__('Custom Swatch');
    }
    
    public function getTabTitle()
    {
        return $this->__('Custom Swatch');
    }
    
    public function canShowTab()
    {
    //return true;
      /*  if ($this->_getProduct()->getHasOptions() && $this->getOptionsCollection()->count()) {
            return true;
        }
	*/
	
	$product = $this->_getProduct();
	
	if($product->isConfigurable())
	{
		foreach ($this->getAllowAttributes() as $attribute) {
			$productAttribute = $attribute->getProductAttribute();
			//$attributeId = $productAttribute->getId();
			if( $productAttribute->getAttributeCode()=='color')
                        {
                            return true;
                        }
		}
	}
        return false;
	
    }
   public function getAllowAttributes()
    {
        return $this->_getProduct()->getTypeInstance(true)
            ->getConfigurableAttributes($this->_getProduct());
    }
    public function isHidden()
    {
        return ($this->getRequest()->getParam('store') != 0) ? true : false;
    }
    
    public function getSubmitUrl()
    {
        $urlData = array('id' => $this->_getProduct()->getId());
	 return Mage::helper("adminhtml")->getUrl("swatch/adminhtml_swatch/upload/",  $urlData);
    }
    
    public function getUploadImagesButtonHtml()
    {
        return $this->getButtonHtml(
            $this->__('Upload Images'), 
            '$(\'swatches-upload\').submit();', 
            'scalable save', 
            'upload_images_btn_customoptions'
        );
    }
    public function getSwatchByProductOption($attribute_id,$option_id)
    {
	$swatchCollection = Mage::getModel('swatch/swatch')->getCollection()
		 ->addFieldToFilter('product_id', $this->_getProduct()->getId())
		 ->addFieldToFilter('attribute_id', $attribute_id)
		  ->addFieldToFilter('option_id', $option_id)
		 ;
	if(count($swatchCollection)>0)
	{	
		foreach ($swatchCollection as $swatch)
		{
			return $swatch;
		}
	}
	return 0;	
    }
    public function getColorSwatchArray()
    {
	$attributes = array();
	foreach ($this->getAllowAttributes() as $attribute) {
		$productAttribute = $attribute->getProductAttribute();
		$attributeId = $productAttribute->getId();
		if($productAttribute->getAttributeCode()=='color')
		{
		
			$info = array(
			'id'        => $productAttribute->getId(),
			'code'      => $productAttribute->getAttributeCode(),
			'label'     => $attribute->getLabel(),
			'options'   => array()
			);
			
			 $prices = $attribute->getPrices();
			 //echo 'test info:'.$attributeId; echo "<pre>"; print_r($prices); 
			
			foreach ($prices as $value) {
			
				$swatch = $this->getSwatchByProductOption($productAttribute->getId(),$value['value_index']);
				
				if($swatch!=0)
				$image = $swatch->getData('image');
				else
				$image = '';
				$info['options'][] = array(
					'id'        => $value['value_index'],
					'label'     => $value['label'],
					'image'     => $image,
					);
					
				//echo $value['label']."swatch: <pre>"; print_r($info); echo "</pre>";
			}
			$attributes[$attributeId] = $info;
		}
		
	}
	
	return $attributes ;
    }
    public function getOptionsCollection()
    {
        if (is_null($this->_optionsCollection)) {
            $this->_optionsCollection = Mage::getModel('catalog/product_option')
                ->getProductOptionCollection($this->_getProduct());
            foreach ($this->_optionsCollection as $key => $item) {
                if (!in_array($item->getType(), $this->_getSelectOptionTypes())) {
                    $this->_optionsCollection->removeItemByKey($key);
                }       
            }
        }
        return $this->_optionsCollection;
    }
    
    protected function _getSelectOptionTypes()
    {
         return Mage::helper('swatches_customoptions')->getSelectOptionTypes();
    }
      
}
