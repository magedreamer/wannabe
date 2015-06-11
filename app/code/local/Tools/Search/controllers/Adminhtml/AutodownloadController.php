<?php
class Tools_Search_Adminhtml_AutodownloadController extends Mage_Adminhtml_Controller_action
{

	protected function _construct()
    {
        $this->setUsedModuleName('Tools_Search');
    }
    
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('tools/search/autodownload')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Auto Download'), Mage::helper('adminhtml')->__('Auto Download'));
		
		return $this;
	} 
	
	public function indexAction() {
	
		$this->_initAction()
			->renderLayout();

	}
	
	public function newAction() {
	
		$this->loadLayout();
		$this->_setActiveMenu('tools/search/autodownload');

		$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Add product item'), Mage::helper('adminhtml')->__('Add ISBN'));
		$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Add product item'), Mage::helper('adminhtml')->__('Add ISBN'));

		$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

		$this->_addContent($this->getLayout()->createBlock('search/adminhtml_autodownload_edit'))
				->_addLeft($this->getLayout()->createBlock('search/adminhtml_autodownload_edit_tabs'));

		$this->renderLayout();
	}
	
	public function saveAction() {
		
		if ($data = $this->getRequest()->getPost()) {
			try {
				$finish = Mage::getResourceModel('search/autodownload')->saveISBN($data['isbn']);
				if($finish)
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('search')->__('ISBN was successfully saved'));
				else
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('search')->__('Save ISBN error'));
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('author')->__('Error save'));
        $this->_redirect('*/*/');
	}
	
	public function massStatusAction()
    {
        $isbn_ids = $this->getRequest()->getParam('autodownload');
        if(!is_array($isbn_ids)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select ISBN(s)'));
        } else {
            try {
                foreach ($isbn_ids as $isbn_id) {
                    $author = Mage::getSingleton('search/autodownload')
                        ->load($isbn_id)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($isbn_ids))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
	public function massDeleteAction() {
        $isbnIds = $this->getRequest()->getParam('autodownload');
        if(!is_array($isbnIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select ISBN(s)'));
        } else {
            try {
                foreach ($isbnIds as $id) {
                	//Mage::getModel('author/author')->deleteImage($id);
                	//Mage::getModel('author/author')->deleteAttribute($id);
                	
                    $isbn = Mage::getModel('search/autodownload')->load($id);
                    $isbn->delete();
                    
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($isbnIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    
	protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('tools/search/autodownload');
    }
}