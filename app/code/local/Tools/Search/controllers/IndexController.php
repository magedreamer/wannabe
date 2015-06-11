<?php
class Tools_Search_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {	
		$this->loadLayout();     
		$this->renderLayout();
    }
    
	public function downloadAction()
    {	
		Mage::getModel('search/observer')->autodownload();
		exit();
    }
}