<?php

class Tools_Search_Model_Mysql4_Search extends Mage_Core_Model_Mysql4_Abstract
{
    protected $_username = "lieuvanduc";
	protected $_password = "!@#tikivn#@!";
	protected $_proxy="";
	protected $_sessionId="";
	protected $_attributeSet=26;
	protected $_storeId=0;
	protected $_old_default_socket_timeout=0;
	protected $_default_socket_timeout=480;
	protected $_connection_timeout=15;
	protected $log_name='amazonsearch.log';
	
	
	
	
    public function _construct()
    {    
        $this->_init('search/search', 'search_id');
        $this->connectService();
    }
   	
	protected function connectService(){
		$this->_old_default_socket_timeout = ini_get('default_socket_timeout');
		ini_set('default_socket_timeout', $this->_default_socket_timeout);
    	$params = array("trace" => true, "connection_timeout" => $this->_connection_timeout);
    	try{
    		$this->_proxy = new SoapClient(Mage::getBaseUrl("web") . 'api/soap/?wsdl',$params);
      		$this->_sessionId = $this->_proxy->login($this->_username,$this->_password);
    	}catch (Exception $e) {
    		Mage::log($e->getMessage(),3,$this->log_name);
    	}
    }
   	
	public function getAttributeSW($function,$arrCondition=""){
		$this->connectService();
		$attribute_options="";
		try{
			if($arrCondition){
				$attribute_options = $this->_proxy->call($this->_sessionId, $function, $arrCondition);
			}else{
				$attribute_options = $this->_proxy->call($this->_sessionId, $function);
			}
			return $attribute_options;
		}catch (Exception $e) {
    		Mage::log($e->getMessage(),3,$this->log_name);
    	}
    }
    
    public function addImageSW($sku,$linkImage)
    {
		if($linkImage){
    	$session = curl_init($linkImage);
		curl_setopt($session, CURLOPT_HEADER, false);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($session);
		curl_close($session); 
		
		$countImage = count($this->getAttributeSW("product_media.list",$sku));
		if($countImage==0){
			$arrTypes = array("image","small_image","thumbnail");
			$exclude = 1;
		}else{
			$exclude = 0;
			$arrTypes = array();
		}
		
		$newImage = array(
    				'file' => array(
    						'content' => base64_encode($response),
    						'mime'    => 'image/jpeg'
							),
					'label'    => $sku,
					'position' => $countImage + 1,
					'types'    => $arrTypes,
					'exclude'  => $exclude);
	 	return $this->_proxy->call($this->_sessionId, 'product_media.create', array($sku, $newImage));;
		} return false;
    }

    
    protected function addInventory($sku,$qty=5,$is_in_stock=1){
    	$this->_proxy->call($this->_sessionId, 'product_stock.update', array($sku, array('qty'=>$qty, 'is_in_stock'=>$is_in_stock)));
    }
    
	protected function addOption($optionName,$attributeCode=""){
    	if($attributeCode && $optionName){
    		$option_id="";
    		$attribute_id="";
    		$resultAttribute = $this->getAttributeSW("product_attribute.list",$this->_attributeSet);
    		if($resultAttribute){
    			foreach($resultAttribute as $attribute){
    				if($attribute["code"] == $attributeCode){
    					$attribute_id = trim($attribute["attribute_id"]);
    					break;
    				}
    			}

    			if($attribute_id){
    				$resultOption = $this->getAttributeSW("product_attribute.options",array("attribute_id"=>$attribute_id));
	    			foreach($resultOption as $option){
	    				if(strtolower($this->trimStr($option["label"])) == strtolower($this->trimStr($optionName))){
	    					return trim($option["value"]);
	    				}
	    			}
	    			$this->_getwriteAdapter()->beginTransaction();
    				$status = $this->_getwriteAdapter()->insert("eav_attribute_option",
    								array("attribute_id"=>$attribute_id,"sort_order"=>0));
    				$option_id=false;				
    				if($status){
    					$option_id = $this->getLastId("eav_attribute_option","option_id");
    					$this->_getwriteAdapter()->insert("eav_attribute_option_value",
    							array("option_id"=>$option_id,
    										"store_id"=>$this->_storeId,
    										"value"=>$optionName));
    							
    					if($attributeCode=="author"){
    						$this->_getwriteAdapter()->insert("author",
    							array("author_att_id"=>$option_id,
    										"author_status"=>1,
    										"author_name"=>$optionName));
    					}
    					
    					
    				}
    				$this->_getwriteAdapter()->commit();
    				return $option_id;
    			}
    		}
    	}
    	return false;
    }
    
	public function addProduct($arrProduct,$arrAttributeName=array()){
		
		$isbn="";$isbnLabel = "";
		if($arrProduct){
			if(isset($arrProduct["isbn13"]) && $arrProduct["isbn13"]){
				$isbn=$arrProduct["isbn13"];
				$isbnLabel="isbn13";
			}else{
				$isbn=$arrProduct["isbn"];
				$isbnLabel="isbn";
			}
			$t = array( $isbnLabel=>array('='=>$isbn));
			
			if($this->getAttributeSW('product.list',array($t))){
				Mage::getSingleton('adminhtml/session')->addError(
						Mage::helper('search')->__($arrProduct["name"]).' with '. strtoupper($isbnLabel) ." : ".$isbn.' already exists');
				return false;
			}
			if(isset($arrProduct) && is_array($arrProduct) && is_array($arrAttributeName)){
				for($i=0;$i<count($arrAttributeName);$i++){
					if(isset($arrProduct[$arrAttributeName[$i]]) && $arrProduct[$arrAttributeName[$i]]){
						$optionId = $this->addOption($arrProduct[$arrAttributeName[$i]],$arrAttributeName[$i]);
						if($optionId){
							$arrProduct[$arrAttributeName[$i]]= $optionId;
						}
					}
				}
				if((int)($arrProduct["price"])==0) $arrProduct["status"]= 2;
				$arrProduct["price"]= $this->currencyExchangeVND("VND",(isset($arrProduct["price"])? $arrProduct["price"] : 0));
				$sku = Mage::getModel('search/search')->ISBN_13($isbn);
				$this->_proxy->call($this->_sessionId, 'product.create', array('simple', $this->_attributeSet, $sku , $arrProduct));
				$this->addInventory($sku);
				ini_set('default_socket_timeout', $this->_old_default_socket_timeout);
				return $sku;
			}
		}else{
			Mage::getSingleton('adminhtml/session')->addError(
					Mage::helper('search')->__("Don't select products"));
			return false;
		}
		return false;
    }
    
	protected function currencyExchangeVND($currencyChanged,$value){

		$select = $this->_getReadAdapter()->select()
          ->from("directory_currency_rate", "rate")
          ->where("currency_from" .'=?', $currencyChanged)
          ->where("currency_to" .'=?', "USD");
        $result = $this->_getReadAdapter()->fetchOne($select);
		if($result)
        	$result = $this->ceilThousand($value / $result);
		else
			$result = $value;
		return $result;
    }
    
	public function ceilThousand($price){
    	$price = sprintf("%01.3f", $price * 0.001);
    	$price = sprintf("%01.3f",ceil($price)) * 1000;
    	return $price;
    }
    
    public function getLastId($tableName,$fieldId){
    	$select = $this->_getReadAdapter()->select()
          ->from($tableName, "max($fieldId)");
        $id = $this->_getReadAdapter()->fetchOne($select);
        return $id;
    }
	
	public function trimStr($str)
	{
	    $str = trim($str);
	    $ret_str="";
	    for($i=0;$i < strlen($str);$i++){
	        if(substr($str, $i, 1) != " "){
	            $ret_str .= trim(substr($str, $i, 1));
	        }else{
	            while(substr($str,$i,1) == " "){
	                $i++;
	            }
	            $ret_str.= " ";
	            $i--;
	        }
	    }
	    return $ret_str;
	} 
	
	public function _getAllAttributeAuthor($attributeCode = "author"){
    	
    	$read = $this->_getReadAdapter();
    	$select = $read->select()
           	->from("eav_attribute_option","eav_attribute_option.option_id")
            ->join("eav_attribute_option_value","eav_attribute_option.option_id = eav_attribute_option_value.option_id","eav_attribute_option_value.value")
            ->join("eav_attribute","eav_attribute_option.attribute_id = eav_attribute.attribute_id",null)
            ->where("eav_attribute.attribute_code=?", $attributeCode)
           	->where("eav_attribute_option_value.store_id=?", 0); 
       $data = $read->fetchAll($select);
       return $data;   
	}
}