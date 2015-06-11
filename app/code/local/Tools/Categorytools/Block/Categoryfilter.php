<?php

class Tools_Categorytools_Block_Categoryfilter extends Mage_Catalog_Block_Product_Abstract {

    const CACHE_TAG = 'tools_categorytools';

    protected $_productCollection;
    protected $_title = '';
    protected $_link = '';

    public function _prepareLayout() {
        
    }

    protected function _isCacheActive() {
        if (!Mage::app()->useCache(self::CACHE_TAG)) {
            return false;
        }
        return true;
    }

    public function getCacheLifetime() {
        if ($this->_isCacheActive()) {
            $time = Mage::getStoreConfig('categorytools/categorytools/categorytools_categoryfilter_cachetime');
            if (!$time)
                $time = false;
            return $time;
        }
    }

    public function getCacheKey() {
        if (!$this->_isCacheActive()) {
            parent::getCacheKey();
        }
        $cacheKey = self::CACHE_TAG . '_categoryfilter' . '_' .
                Mage::app()->getStore()->getId() . '_' .
                Mage::getDesign()->getPackageName() . '_' .
                Mage::getDesign()->getTheme('template') . '_' .
                Mage::getSingleton('customer/session')->getCustomerGroupId() . '_' .
                $this->_cateId . '_' .
                $this->getTemplate();
        return $cacheKey;
    }

    public function getCacheTags() {
        if (!$this->_isCacheActive())
            return parent::getCacheTags();

        $cacheTags = array();
        $cacheTags[] = self::CACHE_TAG;
        return $cacheTags;
    }

    public function setLink($link) {
        $this->_link = $link;
    }

    public function getLink() {
        return $this->_link;
    }

    public function setCategoryId($cateId) {
        $this->_cateId = $cateId;
    }

    public function getCategoryId() {
        return $this->_cateId;
    }

    public function setTitle($title) {
        $this->_title = $title;
    }

    public function getTitle() {
        return $this->_title;
    }

    public function getProductCollection() {


        $category = Mage::getModel('catalog/category')->load($this->getCategoryId());
        $todayDate = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
        $collection = Mage::getResourceModel('catalog/product_collection');
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
        $collection = $collection->addAttributeToSelect('*');
        // ->setStoreId(Mage::app()->getStore()->getId)->setOrder('news_from_date');

        if ($category) {
            $collection->addCategoryFilter($category);
        }

        $collection->getSelect()->limit(10);
        return $collection;
    }

    public function showLableSaleOff($_product) {
        return Mage::getModel('products/view')->showLableSaleOff($_product);
    }

}

