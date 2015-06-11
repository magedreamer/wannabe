<?php

class Tools_Search_Model_Status extends Varien_Object
{
    const STATUS_ENABLED	= 1;
    const STATUS_DISABLED	= 2;
    
    const STATUS_DOWNLOAD	= 1;
	const STATUS_DONTDOWNLOAD	= 2;
    const STATUS_SUCCESS	= 3;
    const STATUS_FAIL	= 4;
    const STATUS_DOWNLOADING	= 5;
    const STATUS_DOWNLOAD_EXIST = 6;
    const STATUS_DOWNLOAD_NOT_RESULT = 7;


    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('search')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('search')->__('Disabled')
        );
    }
    
	static public function getDownloadOptionArray()
    {
        return array(
            self::STATUS_DOWNLOAD    => Mage::helper('search')->__('Download'),
            self::STATUS_DONTDOWNLOAD   => Mage::helper('search')->__("Don't download"),
            self::STATUS_SUCCESS   => Mage::helper('search')->__("Success"),
            self::STATUS_FAIL   => Mage::helper('search')->__("Fail")
        );
    }
    
    static public function getStatusDownloadFail(){
    	return  self::STATUS_FAIL;
    }
    
	static public function getStatusDownloadSuccess(){
    	return  self::STATUS_SUCCESS;
    }
    
	static public function getStatusDownload(){
    	return  self::STATUS_DOWNLOAD;
    }
    
	static public function getStatusDownloading(){
    	return  self::STATUS_DOWNLOADING;
    }
    
	static public function getStatusDownloadExist(){
    	return  self::STATUS_DOWNLOAD_EXIST;
    }
    
	static public function getStatusDownloadNotResult(){
    	return  self::STATUS_DOWNLOAD_NOT_RESULT;
    }
}