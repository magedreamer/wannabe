<?php

class Tools_Upload_Block_Upload extends Mage_Core_Block_Template {

    const CACHE_TAG = 'tools_upload';
    const GROUP_CACHE_TAG = 'tools_group_upload';

    protected $_uploadGroupData = '';

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

        $cacheKey = 'Tools_upload'.'_'.
                    Mage::app()->getStore()->getId().'_'.
                    $this->getUploadGroupCode();

        return $cacheKey;
    }

    public function getCacheTags(){

        if(!$this->_isCacheActive())
            return parent::getCacheTags();
        
        $cacheTags = array();
        $data = $this->getDataByGroupCode($this->getUploadGroupCode());
        $group = $data['group_data'];
        if(isset($data['upload_data']) && $data['upload_data']){
            
            foreach($data['upload_data'] as $_upload) {
                    $cacheTags[] = self::CACHE_TAG."_".$_upload->getUploadId();
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

    public function getUpload() {
        if (!$this->hasData('upload')) {
            $this->setData('upload', Mage::registry('upload'));
        }
        return $this->getData('upload');
    }

    public function getDataByGroupCode($groupCode){
        if(isset($this->_uploadGroupData[$groupCode]) && $this->_uploadGroupData[$groupCode])
              return $this->_uploadGroupData[$groupCode];

        $this->_uploadGroupData[$groupCode] = Mage::getModel('upload/uploadgroup')->getDataByGroupCode($groupCode);
        return $this->_uploadGroupData[$groupCode];
    }

    public function getResizeImage($uploadPath, $groupName, $w = 0, $h = 0) {
        $name = '';
        $_helper = Mage::helper('upload');
        $uploadDirPath = $_helper->updateDirSepereator($uploadPath);
        $baseDir = Mage::getBaseDir();
        $mediaDir = Mage::getBaseDir('media');
        $mediaUrl = $this->getMediaUrlPath();
        $resizeDir = $mediaDir . DS . 'custom' . DS . 'uploads' . DS . 'resize' . DS;
        $resizeUrl = $mediaUrl.'custom/uploads/resize/';
        $imageName = basename($uploadDirPath);
        
        if (@file_exists($mediaDir . DS . $uploadDirPath)) {
            $name = $mediaDir . DS . $uploadPath;
            $this->checkDir($resizeDir . $groupName);
            $smallImgPath = $resizeDir . $groupName . DS . $imageName;
            $smallImg = $resizeUrl . $groupName .'/'. $imageName;
        }
        
        if ($name != '') {
           
            $resizeObject = Mage::getModel('upload/uploadresize');
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
        $url = Mage::getStoreConfig('upload/upload/upload_url');
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