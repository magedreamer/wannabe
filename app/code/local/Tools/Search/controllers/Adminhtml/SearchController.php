<?php

class Tools_Search_Adminhtml_SearchController extends Mage_Adminhtml_Controller_action
{
    protected $_moduleName = "search/search";

    protected function _construct()
    {
        $this->setUsedModuleName('Tools_Search');
    }
	
    
    protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('tools/search/search_normal')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Amazon Search'), Mage::helper('adminhtml')->__('Amazon Search'));
		
		return $this;
	} 
	public function indexAction() {
	
		$action     = $this->getRequest()->getParam('action');
		
		switch($action){
			case "search":
							$this->amazonSearch();
							break;
							
			case "addamzproduct":
							$this->addProductToAmazon();
							break;
										
			default : 		$this->amazonSearch();	
		}

	}
	
	public function addAction()
	{
		$isbn    = $this->getRequest()->getParam('add');
		$arrCate    = $this->getRequest()->getParam('categories');
		$arrAuthorName    = $this->getRequest()->getParam('authorName');
		$arrResult = unserialize($_SESSION['search_result']);
		$amazonsearch = Mage::getModel($this->_moduleName);
		if($isbn){
			foreach($isbn as $a => $v){
				$arrResult[$v]["attributes"]['category_ids']=$arrCate;
				$arrResult[$v]["attributes"]['author']=$arrAuthorName[$v];
				$sku = $amazonsearch->addProductToTiki($arrResult[$v]["attributes"]);
				if(!$sku )
					Mage::getSingleton('adminhtml/session')->addError(
						Mage::helper('search')->__('Error when add product '.$arrResult[$v]["attributes"]["name"]));
				else{
					$amazonsearch->addImageToTiki($sku,$arrResult[$v]["image"]["image_label"]);
				
					Mage::getSingleton('adminhtml/session')->addSuccess(
						Mage::helper('search')->__('Add product Success'));
				}
			}
		}
		$_SESSION['search_result']="";
		$this->_redirect('*/*/index');
	}
	
	protected function amazonSearch(){
		$keyword     = $this->getRequest()->getParam('keyword');
		$page     = $this->getRequest()->getParam('page');
		$amazonsearchresult="";$cate_tree="";

		if($keyword != null){
    		$amazonsearch = Mage::getModel('search/search');
    		$amazonsearchresult = $amazonsearch->SearchItem($keyword,($page? $page : 1));
  
    		$cate_tree = $amazonsearch->getCategory();
    		Mage::register('category_tree', $cate_tree);
    	}
		
		Mage::register('amazonsearch', $amazonsearchresult);
		$this->_initAction()    
			->renderLayout();
	}
	
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('tools/search/search_normal');
    }
	
    public function testAction(){
        ini_set("soap.wsdl_cache_enabled", "0"); 
        $params = array("trace" => true, "connection_timeout" => 480);
        try
        {
            $_proxy = new SoapClient(Mage::getBaseUrl("web") . 'api/v2_soap/?wsdl',$params);
            $_sessionId = $_proxy->login("ebook","!@#ebook#@!");
            $complexFilter = array(
                'complex_filter' => array(
                    array(
                        'key' => 'set',
                        'value' => array('key' => 'in', 'value' => '27')
                    )
                )
            );
            $result = $_proxy->catalogProductList($_sessionId, $complexFilter);
            //$result = $_proxy->call($_sessionId,'catalog_product.list');

            echo "<pre>";
            print_r($result);
            echo "</pre>";
        }catch (Exception $e){
            print '<pre>';
            var_dump(libxml_get_last_error());
            print '</pre>';
        }
    }
}