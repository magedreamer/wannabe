<?php
class Tools_Search_Model_Observer
{
	const STATUS_PRODUCT_ENABLE 	= 1;
	const STATUS_PRODUCT_DISABLE	= 2;

	const XML_PATH_AUTO_DOWNLOAD	= 'tools/search/autodownload_allow';
	
	public function autodownload(){
		
		$this->download();
		//Mage::getModel('products/observer')->autoupdate();
	}
	
	public function download(){
		
		if (!Mage::getStoreConfig(self::XML_PATH_AUTO_DOWNLOAD, 'default' )) {
           	return false;
        }
		$downResourceModel = Mage::getResourceModel('search/autodownload');
		$isbns = $downResourceModel->getIsbnAll(1);
		if($isbns){
			$searchModel = Mage::getModel('search/search');
			$statusModel = Mage::getModel('search/status');
			foreach($isbns as $isbn){
				$status = $statusModel->getStatusDownloading();
				$downResourceModel->changeStatus($isbn['id'],$status);
				try{
					$arrProduct = $searchModel->SearchItem($isbn['isbn'],1,self::STATUS_PRODUCT_DISABLE);
					if(isset($arrProduct[0]["total"]) && $arrProduct[0]["total"]>0){
						$sku = $searchModel->addProductToTiki($arrProduct[0]["attributes"]);
						if($sku){
							$status = $statusModel->getStatusDownloadSuccess();
							$downResourceModel->changeStatus($isbn['id'],$status);
							$searchModel->addImageToTiki($sku,$arrProduct[0]["image"]["image_label"]);
						}else{
							if(isset($arrProduct[0]['attributes']["isbn13"]) && $arrProduct[0]['attributes']["isbn13"]){
								$searchisbn=$arrProduct[0]['attributes']["isbn13"];
								$isbnLabel="isbn13";
							}else{
								$searchisbn=$arrProduct[0]['attributes']["isbn"];
								$isbnLabel="isbn";
							}
							$result = $downResourceModel->checkIsbn_in_product($isbnLabel,$searchisbn);
							if($result){
								$status = $statusModel->getStatusDownloadExist();
							}else{
								$status = 0;//$statusModel->getStatusDownloadFail();
							}
							$downResourceModel->changeStatus($isbn['id'],$status);
						}
					}else{
						$status = $statusModel->getStatusDownloadNotResult();
						$downResourceModel->changeStatus($isbn['id'],$status);
					}
				}catch (Exception $e){
					$status = $statusModel->getStatusDownloadFail();
					$downResourceModel->changeStatus($isbn['id'],$status);
				}
			}
		}else{
			$isAllow = Mage::getStoreConfig(self::XML_PATH_AUTO_DOWNLOAD, 'default' );
			if($isAllow){
				Mage::getModel('core/config')->saveConfig(self::XML_PATH_AUTO_DOWNLOAD,0);
				Mage::getModel('core/config')->cleanCache();
			}
		}
	}
}