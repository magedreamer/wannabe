<?php
class Tools_Search_Model_Mysql4_Autodownload extends Mage_Core_Model_Mysql4_Abstract
{

	const XML_PATH_AUTO_DOWNLOAD	= 'tools/search/autodownload_allow';
	
    public function _construct()
    {    
        $this->_init('search/autodownload', 'id');
    }
    
    public function saveISBN($isbns){
    	$result = false;
    	if($isbns){
    		$arrIsbn = explode("\n",$isbns);
		    $data['status'] =  Mage::getModel('search/status')->getStatusDownload();
    		foreach($arrIsbn as $isbn){
    			$ishave = $this->getISBN($isbn);
	    		if(!isset($ishave['id']) && !$ishave['id']){
		    		if(trim($isbn)){
		    			$isbn = str_replace("-", "", $isbn);
		    			$data['isbn'] =  trim($isbn);
		    			$data['datetime'] =  date("Y-m-d H:i:s");
	    		  		$this->_getWriteAdapter()->insert('tools_search_download',$data);
		    		}
	    		  	$result = true;
	    		}
    		}
    		Mage::getModel('core/config')->saveConfig(self::XML_PATH_AUTO_DOWNLOAD,1);
			Mage::getModel('core/config')->cleanCache();
    	}
    	return $result;
    }
    public function deleteISBN($id){
    	return $this->_getWriteAdapter()->delete('tools_search_download','id '. $id);
    }
    
    public function getISBN($isbn){
    	
    	if($isbn){
    		$cache  = Mage::Helper('search')->loadCache('tools_search_download');
    		if(isset($cache[$isbn]) && $cache[$isbn]){
    			return $cache[$isbn];
    		}else{
    			$read = $this->_getReadAdapter();
	    		$select = $read->select()
	         		->from("tools_search_download")
           			->where("isbn=?", $isbn);
           		$cache[$isbn] = $read->fetchRow($select);
           		$cache  = Mage::Helper('search')->saveCache($cache,'tools_search_download');
       			return $cache[$isbn];
    		}
    	}
    	return false;
    }
    
    public function getIsbnAll($limit){
    	$read = $this->_getReadAdapter();
	    $select = $read->select()
	         		->from("tools_search_download")
           			->where("status=?", Mage::getModel('search/status')->getStatusDownload())
           			->order('id desc');
        $select->limit($limit);
        return $read->fetchAll($select);
    }
    
    public function changeStatus($id,$status){
    	return $this->_getWriteAdapter()->update('tools_search_download',array('status'=>$status,'datetime'=>date("Y-m-d H:i:s")),'id='. $id);
    }
    
    public function checkIsbn_in_product($typeIsbn,$isbn){
    	if($isbn){
	    	$read = $this->_getReadAdapter();
		    		$select = $read->select()
		         		->from("catalog_product_entity_varchar",array('value'))
		         		->join('eav_attribute','catalog_product_entity_varchar.attribute_id = eav_attribute.attribute_id 
		         				AND  eav_attribute.attribute_code = "'.$typeIsbn.'"',array('attribute_id'))
	           			->where("catalog_product_entity_varchar.value=?", $isbn);
        	return $read->fetchRow($select);
    	}
    	return false;
    }
    
    /*public function getAutoDownloadAllow($path,$website){
    	$read = $this->_getReadAdapter();
    	$select = $read->select()
    		->from("core_config_data",array('value'))
    		->where("path=?", $path)
    		->where("scope=?", $website);
    	return $read->fetchOne($select);
    }*/
}