<?php
class TTS_Baokim_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/baokim?id=15 
    	 *  or
    	 * http://site.com/baokim/id/15 	
    	 */
    	/* 
		$baokim_id = $this->getRequest()->getParam('id');

  		if($baokim_id != null && $baokim_id != '')	{
			$baokim = Mage::getModel('baokim/baokim')->load($baokim_id)->getData();
		} else {
			$baokim = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($baokim == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$baokimTable = $resource->getTableName('baokim');
			
			$select = $read->select()
			   ->from($baokimTable,array('baokim_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$baokim = $read->fetchRow($select);
		}
		Mage::register('baokim', $baokim);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}