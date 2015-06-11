<?php
/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Categorytools
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tools_Categorytools_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() { 
        $this->loadLayout();
        $this->renderLayout();
    }
	public function getsecondleftAction() {
		$to_date = '2012/09/04 00:00:00';
		$to = 	strtotime($to_date);
		$now = Mage::getModel('core/date')->timestamp(time());
		if($to>$now) 
		$secondRemain =  $to - $now;
		else
		$secondRemain =  0;
		echo $secondRemain ;
    }
	public function theloaiAction()
	{
		$categoryId = (int) $this->getRequest()->getParam('id', false);
		if (!$categoryId) {
		    return false;
		}
		$category = Mage::getModel('catalog/category')
		    ->setStoreId(Mage::app()->getStore()->getId())
		    ->load($categoryId);
		//Mage::getSingleton('catalog/session')->setLastVisitedCategoryId($category->getId());
		if(!Mage::register('current_category'))
		Mage::register('current_category', $category);
		$this->loadLayout();
		$this->renderLayout();
	
	}
	public function addrouteAction()
	{
		Mage::getModel('core/url_rewrite')
			->setIsSystem(1)
			->setStoreId(1)   
			->setOptions('') 	
			->setIdPath('bestproduct/index/view/id/316')
			->setTargetPath('bestproduct/index/view/id/316')
			->setRequestPath('sach-truyen-tieng-viet/tieu-bieu.html')
			->save();

		Mage::getModel('core/url_rewrite')
			->setIsSystem(1)
			->setStoreId(1)   
			->setOptions('') 	
			->setIdPath('bestproduct/index/view/id/320')
			->setTargetPath('bestproduct/index/view/id/320')
			->setRequestPath('sach-tieng-anh/tieu-bieu.html')
			->save();
		Mage::getModel('core/url_rewrite')
			->setIsSystem(1)
			->setStoreId(1)   
			->setOptions('') 	
			->setIdPath('categorytools/index/theloai/id/316')
			->setTargetPath('categorytools/index/theloai/id/316')
			->setRequestPath('sach-truyen-tieng-viet/the-loai.html')
			->save();
		Mage::getModel('core/url_rewrite')
			->setIsSystem(1)
			->setStoreId(1)   
			->setOptions('') 	
			->setIdPath('categorytools/index/theloai/id/320')
			->setTargetPath('categorytools/index/theloai/id/320')
			->setRequestPath('sach-tieng-anh/the-loai.html')
			->save();

	}
	public function headercartAction()
	{
		echo $this->getLayout()->createBlock('checkout/cart_sidebar')->setTemplate('checkout/cart/header.cart.phtml')->toHtml();
	}
	public function customernameAction()
	{
		if (Mage::helper('customer')->isLoggedIn())
		{
			$customer =  Mage::getModel('customer/session')->getCustomer();
			$customerFullName = $customer->getLastname();
			$fullNameWords = explode(" ",$customerFullName);
			$name = end($fullNameWords); 
			echo $name;
		}
	}
	public function customerinfoAction()
	{
		if (Mage::helper('customer')->isLoggedIn())
		{
			$customer =  Mage::getModel('customer/session')->getCustomer();
			$rewardSession = Mage::getSingleton ( 'rewards/session' )->getSessionCustomer ();
			$customerFullName = $customer->getLastname();
			$fullNameWords = explode(" ",$customerFullName);
			$name = end($fullNameWords); 
			$customerData = Array();
			$customerData['customer_name'] = $name;
			$customerData['customer_rewardpoint'] = $rewardSession->getPointsSummary ();
		        echo json_encode($customerData);
		}
	}
	function testAction()
	{
		$begin = $this->getRequest()->getParam('begin');
		$end = $this->getRequest()->getParam('end');
		$i=0;
		$rootCats = array("316", "320", "25", "723", "914");
		$collection = Mage::getResourceModel('catalog/product_collection');
		$collection = $collection->addAttributeToSelect('*');
		
		foreach ($collection as $item) {
			$i++;
			
			if(($i>=$begin)&&($i<=$end))
			{	
				$product = Mage::getModel('catalog/product')->load($item->getData('entity_id'));
				
				foreach ($rootCats as $catId) {
					if ($this->isChildOfCategory($product, $catId)) {
						$categories = $product->getCategoryIds();
						echo 'out'.$item->getData('entity_id').'...';
						if (!in_array($catId, $categories)) {
							echo 'in'.$item->getData('entity_id').'<br/>';
							Mage::log('stt:'.$i.':'.$item->getData('entity_id').' ',3,'resetcategory.log');
							$categories[] = $catId;
							$product->setCategoryIds($categories);
							$product->save();
						}
					}
				}
			}
		}
		
	}
	 public function isChildOfCategory($product, $categoryId) {
        $category_model = Mage::getModel('catalog/category'); //get category model
        $_category = $category_model->load($categoryId); //$categoryid for which the child categories to be found       
        $all_child_categories = $category_model->getResource()->getAllChildren($_category); //array consisting of all child categories id

        $productCategories = $product->getCategoryIds();
        foreach ($productCategories as $catId) {
            if (in_array($catId, $all_child_categories))
                return 1;
        }
        return 0;
    }

    public function resetTopCategories() {
        $rootCats = array("316", "320", "25", "723", "914");
        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection = $collection->addAttributeToSelect('*');
        foreach ($collection as $item) {
            $product = Mage::getModel('catalog/product')->load($item->getData('entity_id'));
            foreach ($rootCats as $catId) {
                if ($this->isChildOfCategory($product, $catId)) {
                    $categories = $product->getCategoryIds();
                    if (!in_array($catId, $categories)) {
                        $categories[] = $catId;
                        $product->setCategoryIds($categories);
                        $product->save();
                    }
                }
            }
        }
    }
}
