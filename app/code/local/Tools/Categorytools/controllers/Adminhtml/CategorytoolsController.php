<?php
/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Categorytools
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tools_Categorytools_Adminhtml_CategorytoolsController extends Mage_Adminhtml_Controller_action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('tools/categorytools/categorytools')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('categorytools/categorytools')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('categorytools_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('tools/categorytools/categorytools');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('categorytools/adminhtml_categorytools_edit'))
                    ->_addLeft($this->getLayout()->createBlock('categorytools/adminhtml_categorytools_edit_tabs'));
            $version = substr(Mage::getVersion(), 0, 3);
            if (($version=='1.4' || $version=='1.5') && Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
                $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
            }
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('categorytools')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        $imagedata = array();
        $MAXIMUM_FILESIZE = 1 * 1024 * 1024;
       
        if ($data = $this->getRequest()->getPost()) {
            $categoryId = $data['category_name'];
            $catagory_model = Mage::getModel('catalog/category');
            $category = $catagory_model->load($data['category_name']);
            $data['category_name'] =  $category->getName();
            $data['category_id'] = $categoryId;

            $model = Mage::getModel('categorytools/categorytools');
            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));

            try {
                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                            ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }

                $model->save();
                Mage::helper('categorytools')->cleanCache(Tools_Categorytools_Block_Categorytools::CACHE_TAG.'_'.$this->getRequest()->getParam('id'));
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('categorytools')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('categorytools')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('categorytools/categorytools')->load($this->getRequest()->getParam('id'));
                $_helper = Mage::helper('categorytools');
                $filePath = Mage::getBaseDir('media').DS.$_helper->updateDirSepereator($model->getFilename());
                $model->delete();
                $this->removeFile($filePath);

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $categorytoolsIds = $this->getRequest()->getParam('categorytools');
        if (!is_array($categorytoolsIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($categorytoolsIds as $categorytoolsId) {
                    $model = Mage::getModel('categorytools/categorytools')->load($categorytoolsId);
                    $_helper = Mage::helper('categorytools');
                    $filePath = Mage::getBaseDir('media').DS.$_helper->updateDirSepereator($model->getFilename());
                    $model->delete();
                    $this->removeFile($filePath);
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($categorytoolsIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $categorytoolsIds = $this->getRequest()->getParam('categorytools');
        if (!is_array($categorytoolsIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($categorytoolsIds as $categorytoolsId) {
                    $categorytools = Mage::getSingleton('categorytools/categorytools')
                                    ->load($categorytoolsId)
                                    ->setStatus($this->getRequest()->getParam('status'))
                                    ->setIsMassupdate(true)
                                    ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($categorytoolsIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction() {
        $fileName = 'categorytools.csv';
        $content = $this->getLayout()->createBlock('categorytools/adminhtml_categorytools_grid')
                        ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'categorytools.xml';
        $content = $this->getLayout()->createBlock('categorytools/adminhtml_categorytools_grid')
                        ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream') {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }

    protected function removeFile($file) {
        try {
            $io = new Varien_Io_File();
            $result = $io->rmdir($file, true);
        } catch (Exception $e) {

        }
    }

    protected function _isAllowed()
    {
        $action = strtolower($this->getRequest()->getActionName());
        switch ($action) {
            case 'listcategory':
                    $aclResource = 'tools/categorytools/categorylist';
                    break;
            default : 
                    $aclResource = 'tools/categorytools/categorytools';
                    break;
        }
        return Mage::getSingleton('admin/session')->isAllowed($aclResource);
    }
    
    public function listcategoryAction()
    {
            header ('Content-type: text/html; charset=utf-8');
            $category = Mage::getModel('catalog/category');
            $tree = $category->getTreeModel();
            $tree->load();

            $ids = $tree->getCollection()->getAllIds();
            $arr = array();

            if ($ids){
                    foreach ($ids as $id){
                    $cat = Mage::getModel('catalog/category');
                    $cat->load($id);
                    array_push($arr, $cat);
                    }
            }

            foreach($arr as $key => $value)
            {
                    echo $value->getId().' '. $value->getName().'<br/>';
            }

    }
}