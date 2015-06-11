<?php

class Tools_Banner_Block_Banner extends Mage_Core_Block_Template {

    const CACHE_TAG = 'tools_banner';
    const GROUP_CACHE_TAG = 'tools_group_banner';

    protected $_bannerGroupData = '';

    protected function _isCacheActive()
    {
        if(!Mage::app()->useCache(self::CACHE_TAG)) {
            return false;
        }
        return true;
    }

    public function getCacheLifetime()
    {
        if($this->_isCacheActive())
            return false;
    }

    public function getCacheKey()
    {
        if(!$this->_isCacheActive())
            return parent::getCacheKey();

        $cacheKey = 'Tools_banner'.'_'.
                    Mage::app()->getStore()->getId().'_'.
                    $this->getBannerGroupCode();

        return $cacheKey;
    }

    public function getCacheTags(){

        if(!$this->_isCacheActive())
            return parent::getCacheTags();
        
        $cacheTags = array();
        $data = $this->getDataByGroupCode($this->getBannerGroupCode());
        $group = $data['group_data'];
        if(isset($data['banner_data']) && $data['banner_data']){
            
            foreach($data['banner_data'] as $_banner) {
                    $cacheTags[] = self::CACHE_TAG."_".$_banner->getBannerId();
            }
        }
        if($group)
             $cacheTags[] = self::GROUP_CACHE_TAG.'_'. $group->getGroupId();
        $cacheTags[] = self::CACHE_TAG;
        return $cacheTags;
    }

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getBanner() {
        if (!$this->hasData('banner')) {
            $this->setData('banner', Mage::registry('banner'));
        }
        return $this->getData('banner');
    }

    public function getDataByGroupCode($groupCode){
        if(isset($this->_bannerGroupData[$groupCode]) && $this->_bannerGroupData[$groupCode])
              return $this->_bannerGroupData[$groupCode];

        $this->_bannerGroupData[$groupCode] = Mage::getModel('banner/bannergroup')->getDataByGroupCode($groupCode);
        return $this->_bannerGroupData[$groupCode];
    }

    public function getResizeImage($bannerPath, $groupName, $w = 0, $h = 0) {
        $name = '';
        $_helper = Mage::helper('banner');
        $bannerDirPath = $_helper->updateDirSepereator($bannerPath);
        $baseDir = Mage::getBaseDir();
        $mediaDir = Mage::getBaseDir('media');
        $mediaUrl = $this->getMediaUrlPath();
        $resizeDir = $mediaDir . DS . 'custom' . DS . 'banners' . DS . 'resize' . DS;
        $resizeUrl = $mediaUrl.'custom/banners/resize/';
        $imageName = basename($bannerDirPath);
        
        if (@file_exists($mediaDir . DS . $bannerDirPath)) {
            $name = $mediaDir . DS . $bannerPath;
            $this->checkDir($resizeDir . $groupName);
            $smallImgPath = $resizeDir . $groupName . DS . $imageName;
            $smallImg = $resizeUrl . $groupName .'/'. $imageName;
        }
        
        if ($name != '') {
           
            $resizeObject = Mage::getModel('banner/bannerresize');
            $resizeObject->setImage($name);
            if ($resizeObject->resizeLimitwh($w, $h, $smallImgPath) === false) {
                return $resizeObject->error();
            } else {                
                return $smallImg;
            }
        } else {
            return '';
        }
    }

    public function getMediaUrlPath(){
        $url = Mage::getStoreConfig('banner/banner/banner_url');
        if($url)
            return $url;
        else
            return Mage::getBaseUrl('media');
    }

    protected function checkDir($directory) {
        if (!is_dir($directory)) {
            umask(0);
            mkdir($directory, 0777,true);
            return true;
        }
    }
}