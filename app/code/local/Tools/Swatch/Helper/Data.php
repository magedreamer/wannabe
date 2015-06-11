<?php

class Tools_Swatch_Helper_Data extends Mage_Core_Helper_Abstract {

    protected $_width;
    protected $_height;

    public function getSelectOptionTypes() {
        return array(
            Mage_Catalog_Model_Product_Option::OPTION_TYPE_DROP_DOWN,
            Mage_Catalog_Model_Product_Option::OPTION_TYPE_RADIO,
            Mage_Catalog_Model_Product_Option::OPTION_TYPE_CHECKBOX,
            Mage_Catalog_Model_Product_Option::OPTION_TYPE_MULTIPLE,
        );
    }

    public function getAllowedExtensions() {
        return array('jpg', 'jpeg', 'gif', 'png');
    }

    public function getImagePath($path = '') {
        return Mage::getBaseDir('media') . DS . 'swatches' .
                DS . 'customoptions' . DS . $path;
    }

    public function getImageUrl($path = '') {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)
                . 'swatches/customoptions/' . $path;
    }

    public function getResizedUrl($image) {
        if (!$this->getWidth() OR !$this->getHeight()) {
            return $this->getImageUrl($image);
        }

        $resized = $this->getResizedFolder();
        $origImage = $this->getImagePath($image);
        $newImage = $this->getImagePath($resized . DS . $image);
        if (!file_exists($newImage)) {
            if (!file_exists($this->getImagePath($resized))) {
                @mkdir($this->getImagePath($resized), 0777);
            }
            try {
                $imageObj = new Varien_Image($this->getImagePath($image));
                $imageObj->constrainOnly(true);
                $imageObj->keepFrame(false);
                $imageObj->keepTransparency(true);
                $imageObj->resize($this->getWidth(), $this->getHeight());
                $imageObj->save($newImage);
            } catch (Exception $e) {
                //Mage::log($e->getMessage(), null, 'swatches.log');
            }
        }
        return $this->getImageUrl($resized . '/' . $image);
    }

    protected function getWidth() {
        if (!$this->_width) {
            $this->_width = 42; //Mage::getStoreConfig('swatches/customoptions/width');
        }
        return $this->_width;
    }

    protected function getHeight() {
        if (!$this->_height) {
            $this->_height = 59; //Mage::getStoreConfig('swatches/customoptions/height');
        }
        return $this->_height;
    }

    protected function getResizedFolder() {
        return $this->getWidth() . 'x' . $this->getHeight();
    }

    public function getSwatchByProductOption($productId, $attribute_id, $option_id) {

        $swatchCollection = Mage::getModel('swatch/swatch')->getCollection()
                ->addFieldToFilter('product_id', $productId)
                ->addFieldToFilter('attribute_id', $attribute_id)
                ->addFieldToFilter('option_id', $option_id)
        ;
        if (count($swatchCollection) > 0) {
            foreach ($swatchCollection as $swatch) {
                return $swatch;
            }
        }
        return false;
    }

    public function getAvailableAtrributeArr($inputProduct) {
        $attributes = array();
        $options = array();
        $store = Mage::app()->getStore();
        $taxHelper = Mage::helper('tax');
        $currentProduct = $inputProduct;

        $preconfiguredFlag = $currentProduct->hasPreconfiguredValues();
        if ($preconfiguredFlag) {
            $preconfiguredValues = $currentProduct->getPreconfiguredValues();
            $defaultValues = array();
        }

        foreach ($this->getAllowProducts($currentProduct) as $product) {
            $productId = $product->getId();

            foreach ($this->getAllowAttributes($currentProduct) as $attribute) {

                $productAttribute = $attribute->getProductAttribute();
                $productAttributeId = $productAttribute->getId();

                $attributeValue = $product->getData($productAttribute->getAttributeCode());
                if (!isset($options[$productAttributeId])) {
                    $options[$productAttributeId] = array();
                }

                if (!isset($options[$productAttributeId][$attributeValue])) {
                    $options[$productAttributeId][$attributeValue] = array();
                }
                $options[$productAttributeId][$attributeValue][] = $productId;
            }

            foreach ($this->getAllowAttributes($currentProduct) as $attribute) {

                $productAttribute = $attribute->getProductAttribute();
                $attributeId = $productAttribute->getId();
                $info = array(
                    'id' => $productAttribute->getId(),
                    'code' => $productAttribute->getAttributeCode(),
                    'label' => $attribute->getLabel(),
                    'options' => array()
                );

                $optionPrices = array();
                $prices = $attribute->getPrices();

                if (is_array($prices)) {

                    foreach ($prices as $value) {

                        if (!$this->_validateAttributeValue($attributeId, $value, $options)) {
                            continue;
                        }
                        if (isset($options[$attributeId][$value['value_index']])) {
                            $productsIndex = $options[$attributeId][$value['value_index']];
                        } else {
                            $productsIndex = array();
                        }

                        $info['options'][] = array(
                            'id' => $value['value_index'],
                            'label' => $value['label'],
                            'products' => $productsIndex,
                                //'color_images'  => $color,
                        );

                        if ($this->_validateAttributeInfo($info)) {
                            // if($attributeId == 76)
                            // {
                            // echo "<pre>"; print_r($info); echo "</pre>";
                            // }
                            //var_dump($info);

                            $attributes[$attributeId] = $info;
                        }
                    }
                }
            }
        }
        //	echo "<pre>"; print_r($attributes); ;
        return $attributes;
    }

    //public function generateAttributeImage($product, $attributeId)
    public function generateAttributeImage($product, $attributeId) {
        //return $attributes[$attributeId]['options'];
        //$array =  generateAttributeImage($attributeId, $attributes);
        $attributes = Mage::helper('swatch')->getAvailableAtrributeArr($product);
   
        if (isset($attributes[$attributeId]['options'])) {
            $array = $attributes[$attributeId]['options'];
            foreach ($array as $key => $colorObject) {
                $productIdArr = $colorObject['products'];
                foreach ($productIdArr as $productId) {
                    $tempProduct = Mage::getModel('catalog/product')->load($productId);
                    $images = $tempProduct->getMediaGalleryImages();
                    foreach ($images as $_image) {
                        $tmp['hi_res'] = Mage::helper('catalog/image')->init($product, 'image', $_image->getFile())->__toString();
                        $tmp['thumbnail'] = Mage::helper('catalog/image')->init($product, 'thumbnail', $_image->getFile())->resize(60, 60)->__toString();
                        $tmp['base_image'] = Mage::helper('catalog/image')->init($product, 'image', $_image->getFile())->resize(400, 560)->__toString();
                        $tmp['label'] = $_image->getLabel();
                        $array[$key]['image'][] = $tmp;
                    }
                    break;
                }
            }
           
            return Mage::helper('core')->jsonEncode($array);
        }
        return array();
    }

    public function _validateAttributeInfo(&$info) {
        if (count($info['options']) > 0) {
            return true;
        }
        return false;
    }

    public function getAttributeOptionsByAtrributeId($attributeId, $attributes) {
        $options = $attributes[$attributeId]['options'];
        $optionArr = array();
        foreach ($options as $option) {
            $optionArr[] = $option['id'];
        }
        return $optionArr;
    }

    public function getAllowProducts($inputProduct) {
        $products = array();
        //  $skipSaleableCheck = Mage::helper('catalog/product')->getSkipSaleableCheck();
        $allProducts = $inputProduct->getTypeInstance(true)
                ->getUsedProducts(null, $inputProduct);
        foreach ($allProducts as $product) {
            //         if ($product->isSaleable() || $skipSaleableCheck) {
            if ($product->isSaleable()) {
                $products[] = $product;
            }
        }
        //$this->setAllowProducts($products);
        return $products;
    }

    public function getAllowAttributes($product) {
        return $product->getTypeInstance(true)
                        ->getConfigurableAttributes($product);
    }

    public function _validateAttributeValue($attributeId, &$value, &$options) {
        if (isset($options[$attributeId][$value['value_index']])) {
            return true;
        }

        return false;
    }

    public function getAttributeLabelArr($productId, $attributeId) {
        $_product = Mage::getModel('catalog/product')->load($productId);
        $attributes = Mage::helper('swatch')->getAvailableAtrributeArr($_product);
        $array = $attributes[$attributeId]['options'];
        $attributeLabelArr = array();
        foreach ($array as $attributeItem) {
            $attributeLabelArr[] = $attributeItem['label'];
        }
        return Mage::helper('core')->jsonEncode($attributeLabelArr);
    }

}

?>
