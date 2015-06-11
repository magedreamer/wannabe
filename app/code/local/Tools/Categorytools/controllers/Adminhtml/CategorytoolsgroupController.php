<?php
/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Categorytools
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tools_Categorytools_Adminhtml_CategorytoolsgroupController extends Mage_Adminhtml_Controller_action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('tools/categorytools/categorytoolsgroup')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('categorytools/categorytoolsgroup')->load($id);
        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
        }
        Mage::register('categorytoolsgroup_data', $model);
        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

    public function categorytoolsgridAction() {
        $this->_initAction();
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('categorytools/adminhtml_categorytoolsgroup_edit_tab_categorytools')->toHtml()
        );
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('categorytools/categorytoolsgroup')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('categorytoolsgroup_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('tools/categorytools/categorytoolsgroup');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('categorytools/adminhtml_categorytoolsgroup_edit'))
                    ->_addLeft($this->getLayout()->createBlock('categorytools/adminhtml_categorytoolsgroup_edit_tabs'));

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
        if ($data = $this->getRequest()->getPost()) {
            $categorytoolss = array();
            $availCategorytoolsIds = Mage::getModel('categorytools/categorytools')->getAllAvailCategorytoolsIds();
            parse_str($data['categorytoolsgroup_categorytoolss'], $categorytoolss);
            foreach ($categorytoolss as $k => $v) {
                if (preg_match('/[^0-9]+/', $k) || preg_match('/[^0-9]+/', $v)) {
                    unset($categorytoolss[$k]);
                }
            }
            $categorytoolsIds = array_intersect($availCategorytoolsIds, $categorytoolss);
            $data['categorytools_ids'] = implode(',', $categorytoolsIds);
            $data['categorytools_effects'] = (($data['animation_type'] == 0) ? '' : $data['categorytools_effects']);
            $data['pre_categorytools_effects'] = (($data['animation_type'] == 0) ? $data['pre_categorytools_effects'] : '');
            $model = Mage::getModel('categorytools/categorytoolsgroup');
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
                Mage::helper('categorytools')->cleanCache(Tools_Categorytools_Block_Categorytools::GROUP_CACHE_TAG.'_'.$this->getRequest()->getParam('id'));
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('categorytools')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                $model->DeleteOldImage($this->getRequest()->getParam('id'));
                
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
                $model = Mage::getModel('categorytools/categorytoolsgroup')->load($this->getRequest()->getParam('id'));
                $filePath = Mage::getBaseDir('media') . DS . 'custom' . DS . 'categorytoolss' . DS . 'resize' . DS . $model->getGroupCode();              
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
                    $categorytools = Mage::getModel('categorytools/categorytoolsgroup')->load($categorytoolsId);
                    $filePath = Mage::getBaseDir('media') . DS . 'custom' . DS . 'categorytoolss' . DS . 'resize' . DS . $categorytools->getGroupCode();
                    $categorytools->delete();
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
                    $categorytools = Mage::getSingleton('categorytools/categorytoolsgroup')
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
        return Mage::getSingleton('admin/session')->isAllowed('tools/categorytools/categorytoolsgroup');
    }
}