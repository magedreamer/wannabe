<?php

class Tools_Search_Model_Search extends Mage_Core_Model_Abstract
{
    protected $_resource="";
	protected $_arrAttribute = array("manufacturer","author");
	protected $_supplier = 669; // option id value amazon
	
    public function _construct()
    {
        parent::_construct();
        $this->_init("search/search");
        $this->_resource = $this->getResource();
    }
    public function SearchItem($keywork,$numpage,$enable=1){
    	
    	$public_key = "AKIAJZJVAIPHY6KXOOIA";
		$private_key = "faiYqnFuLYUtYG4tFekEeL9+cr5l1VII/zhHaDGj";

		$string  = array("Operation"=>"ItemSearch",
						"SearchIndex"=>"Books",
						"Keywords" => $keywork,
						"ResponseGroup"=>"Medium,Offers",
						"ItemPage" => $numpage
						);
		$pxml = $this->Signed("com", $string , $public_key, $private_key);
		if ($pxml === False){
			Mage::helper('search')->addNoteMessage("Did not work.\n");
		}else{
   			//return $pxml;
   			return $this->addProductArray($pxml,$enable);
		}
    }
    
    protected function Signed($region, $params, $public_key, $private_key){
    	
    	$method = "GET";
    	$host = "ecs.amazonaws.".$region;
    	$uri = "/onca/xml";
    
    	// additional parameters
    	$params["Service"] = "AWSECommerceService";
   	 	$params["AWSAccessKeyId"] = $public_key;
    	// GMT timestamp
    	$params["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z");
    	// API version
    	$params["Version"] = "2009-03-31";
    
    	// sort the parameters
    	ksort($params);
    
    	// create the canonicalized query
    	$canonicalized_query = array();
    	foreach ($params as $param=>$value)
    	{
        	$param = str_replace("%7E", "~", rawurlencode($param));
        	$value = str_replace("%7E", "~", rawurlencode($value));
        	$canonicalized_query[] = $param."=".$value;
    	}
    	$canonicalized_query = implode("&", $canonicalized_query);
    
    	// create the string to sign
    	$string_to_sign = $method."\n".$host."\n".$uri."\n".$canonicalized_query;
    
    	// calculate HMAC with SHA256 and base64-encoding
    	$signature = base64_encode(hash_hmac("sha256", $string_to_sign, $private_key, True));
    
    	// encode the signature for the request
    	$signature = str_replace("%7E", "~", rawurlencode($signature));
    
    	// create request
    	$request = "http://".$host.$uri."?".$canonicalized_query."&Signature=".$signature;
    
    	//echo $request;
    	
		$session = curl_init($request);
		curl_setopt($session, CURLOPT_HEADER, false);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($session);
		curl_close($session); 
  
    	if ($response === False){
        	return False;
    	}else{
        	$pxml = simplexml_load_string($response);
        	if ($pxml === False){
            	return False;
        	}else{
            	return $pxml;
        	}
    	}
    }
	
    protected function addProductArray($pxml,$enable=1){
	
		$numOfItems = strval($pxml->Items->TotalResults);
		$product = array();
		$item = 0;
		$product[$item]['total'] = $numOfItems;
		$product[$item]['totalpage'] = strval($pxml->Items->TotalPages);
		$product[$item]['itempage'] = strval($pxml->Items->Request->ItemSearchRequest->ItemPage);
		
		$authorName=array();
		$data = Mage::getResourceModel('search/search')->_getAllAttributeAuthor();
    	for($i=0;$i<count($data);$i++){
			$authorName[$i] = $data[$i]['value'];
		}
		
		if($numOfItems>0){
			foreach($pxml->Items->Item as $current){
				
				$product[$item]['attributes']['status'] = $enable;
				$product[$item]['attributes']['tax_class_id'] = 0;
				$product[$item]['attributes']['websites'] = array(1);
				$product[$item]['ASIN'] = strval($current->ASIN);
				
				if (isset($current->ItemAttributes->Title))
					$product[$item]['attributes']['name'] = strval($current->ItemAttributes->Title);
						
				if (isset($current->ItemAttributes->PackageDimensions->Weight)){
					$product[$item]['attributes']['weight'] = sprintf("%.1f",($current->ItemAttributes->PackageDimensions->Weight? ($current->ItemAttributes->PackageDimensions->Weight /100 ) * 453.59237: 0.0 ));
					
					$product[$item]['attributes']['dimensions'] = sprintf("%.1f",($current->ItemAttributes->PackageDimensions->Length? ($current->ItemAttributes->PackageDimensions->Length /100) * 2.54 : 0));
					
					$product[$item]['attributes']['dimensions'] .= " x ".sprintf("%.1f",($current->ItemAttributes->PackageDimensions->Width? ($current->ItemAttributes->PackageDimensions->Width  /100 ) * 2.54: 0.0));
					
					$product[$item]['attributes']['dimensions'] .= " x ".sprintf("%.1f",($current->ItemAttributes->PackageDimensions->Height? ($current->ItemAttributes->PackageDimensions->Height /100) * 2.54: 0.0));
				}else{
					$product[$item]['attributes']['weight']="";
					$product[$item]['attributes']['dimensions']="";
				}
				
				if (isset($current->ItemAttributes->Author)){
					$product[$item]['attributes']['author'] = strval($current->ItemAttributes->Author);
					$product[$item]['attributes']['meanAuthor'] = $this->exactMatch($product[$item]['attributes']['author'],$authorName);
				}else{
					$product[$item]['attributes']['author']="";
					$product[$item]['attributes']['meanAuthor']="";
				}
				
				if (isset($current->ItemAttributes->Binding))
					$product[$item]['attributes']['cover'] = strval($current->ItemAttributes->Binding);
				else
					$product[$item]['attributes']['cover']="";
				
				if (isset($current->ItemAttributes->ListPrice->FormattedPrice)){
					$product[$item]['attributes']['list_price'] = substr(strval($current->ItemAttributes->ListPrice->FormattedPrice),1);
					$product[$item]['attributes']['price'] = substr(strval($current->ItemAttributes->ListPrice->FormattedPrice),1);
				}else{
					$product[$item]['attributes']['list_price']=0;
					$product[$item]['attributes']['price']=0;
				}
				
				if (isset($current->Offers->Offer->OfferListing->Price->FormattedPrice)){
					$product[$item]['Offers']['offer_price'] = strval($current->Offers->Offer->OfferListing->Price->FormattedPrice);
				}else{
					$product[$item]['Offers']['offer_price'] = 0;
				}
				
				
				if (isset($current->ItemAttributes->PublicationDate))
					$product[$item]['attributes']['publication_date'] = strval($current->ItemAttributes->PublicationDate);
				else
					$product[$item]['attributes']['publication_date']="";
					
				if (isset($current->ItemAttributes->NumberOfPages))
					$product[$item]['attributes']['number_of_page'] = strval($current->ItemAttributes->NumberOfPages);
				else
					$product[$item]['attributes']['number_of_page']=0;
					
				if (isset($current->ItemAttributes->Manufacturer))
					$product[$item]['attributes']['manufacturer'] = strval($current->ItemAttributes->Manufacturer);
				else
					$product[$item]['attributes']['manufacturer']="";
					
				if (isset($current->ItemAttributes->ISBN))
					$product[$item]['attributes']['isbn'] = strval($current->ItemAttributes->ISBN);
				else
					$product[$item]['attributes']['isbn']="";
					
				if (isset($current->ItemAttributes->EAN))
					$product[$item]['attributes']['isbn13'] = strval($current->ItemAttributes->EAN);
				else
					$product[$item]['attributes']['isbn13']="";
				
				if(!$product[$item]['attributes']['isbn13'] && !$product[$item]['attributes']['isbn']){
					if($product[$item]['ASIN']){
						$product[$item]['attributes']['isbn13'] = $product[$item]['attributes']['isbn'] = $product[$item]['ASIN'];
					}	
				}
					
				if (isset($current->ItemAttributes->Edition))
					$product[$item]['attributes']['edition'] = strval($current->ItemAttributes->Edition);
				else
					$product[$item]['attributes']['edition']="";
				
				$product[$item]['attributes']['supplier'] = $this->_supplier;	
				
				if (isset($current->DetailPageURL))
					$product[$item]['link']['detailpageURL'] = strval($current->DetailPageURL);
				else
					$product[$item]['link']['detailpageURL'] = "#";
					
				if (isset($current->ImageSets->ImageSet->LargeImage->URL))
					$product[$item]['image']['image_label'] = strval($current->ImageSets->ImageSet->LargeImage->URL);
				else
					$product[$item]['image']['image_label']="";
					
				if (isset($current->ImageSets->ImageSet->SmallImage->URL))
					$product[$item]['image']['small_image_label'] = strval($current->ImageSets->ImageSet->SmallImage->URL);
					
				if (isset($current->ImageSets->ImageSet->ThumbnailImage->URL))
					$product[$item]['image']['thumbnail_label'] = strval($current->ImageSets->ImageSet->MediumImage->URL);
					
		 		if(count($current->EditorialReviews)>0){
		 			
		 				foreach($current->EditorialReviews->EditorialReview as $editorialReview){
							if (isset($editorialReview->Source) && strval($editorialReview->Source) == "Product Description")
								$product[$item]['attributes']['description'] = strval($editorialReview->Content);
							else{	
								if (isset($editorialReview->Source))
									$product[$item]['review'][strval($editorialReview->Source)] = strval($editorialReview->Content);
							}
						}
		 		}else
		 			$product[$item]['attributes']['description']="";

				$item++;
			}
		}
		return $product;
    }
    
    public function addProductToTiki($newProductData){
    	return $this->_resource->addProduct($newProductData,$this->_arrAttribute);
    	//return $this->_resource->getAttributeSW("product.list",array('sku'=>"HTC Touch Diamond"));
		//return $this->_resource->getLastId('catalog_product_entity','entity_id');
    }
   
    public function addImageToTiki($sku,$linkImage){
    	return $this->_resource->addImageSW($sku,$linkImage);
    }
   
    public function getCategory(){
    	return $this->_resource->getAttributeSW("category.tree");
    }
	
	public function exactMatch($input,$words=array()){
            $arrValue = array();
            if($words){
    		if(!is_array($words))
				$words = array($words);
			for($i=0;$i<count($words);$i++){
				similar_text(strtolower($input),strtolower($words[$i]),$p);
				if($p >= 90 && $p < 100){
					$arrValue[] = $words[$i];
				}
			}
		}
		return $arrValue;
    }
    
    public function ISBN_13($isbn='',$tiento = '026')
    {
    	$sku='';
    	switch(strlen($isbn)){
    		case 10 :
    					$sku = str_pad($tiento, 3, "0").$isbn;
    					break;
    		case 13 :
    					$sku = $isbn;
    					break;
    		default :
    					$sku = mt_rand();
    					$sku = str_pad($tiento, 3, "0").str_pad($sku, 10, "0", STR_PAD_LEFT);
    					break;
    	}
    	return $sku;
    }
}