<?php
class TTS_Nganluong_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/nganluong?id=15 
    	 *  or
    	 * http://site.com/nganluong/id/15 	
    	 */
    	/* 
		$nganluong_id = $this->getRequest()->getParam('id');

  		if($nganluong_id != null && $nganluong_id != '')	{
			$nganluong = Mage::getModel('nganluong/nganluong')->load($nganluong_id)->getData();
		} else {
			$nganluong = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($nganluong == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$nganluongTable = $resource->getTableName('nganluong');
			
			$select = $read->select()
			   ->from($nganluongTable,array('nganluong_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$nganluong = $read->fetchRow($select);
		}
		Mage::register('nganluong', $nganluong);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}