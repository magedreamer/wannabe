<?php
/**
 * 

 * @package    Uni_Upload


 */
class Tools_Upload_Adminhtml_UploadController extends Mage_Adminhtml_Controller_action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('tools/upload/upload')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('upload/upload')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('upload_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('tools/upload/upload');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('upload/adminhtml_upload_edit'))
                    ->_addLeft($this->getLayout()->createBlock('upload/adminhtml_upload_edit_tabs'));
            $version = substr(Mage::getVersion(), 0, 3);
            if (($version=='1.4' || $version=='1.5') && Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
                $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
            }
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('upload')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        $imagedata = array();
        $MAXIMUM_FILESIZE = 1 * 1024 * 1024;
        if (!empty($_FILES['filename']['name'])) {
            try {
                $ext = substr($_FILES['filename']['name'], strrpos($_FILES['filename']['name'], '.') + 1);
               // if($_FILES['filename']['size'] > $MAXIMUM_FILESIZE){
               //     Mage::getSingleton('adminhtml/session')->addError(Mage::helper('upload')->__('File size only allow 1M'));
                //    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                //    return 0;
                //}*/
                $fname = 'File-' . time() . '.' . $ext;
                $uploader = new Varien_File_Uploader('filename');
               // $uploader->setAllowedExtensions(array('pdf', 'xls', 'xlsx')); // or pdf or anything

                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);

                $path = Mage::getBaseDir('media').DS.'custom'.DS.'uploads';

                $uploader->save($path, $fname);
                $imagedata['filename'] = 'custom/uploads/'.$fname;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        if ($data = $this->getRequest()->getPost()) {
            if (!empty($imagedata['filename'])) {
                $data['filename'] = $imagedata['filename'];
            } else {
                if (isset($data['filename']['delete']) && $data['filename']['delete'] == 1) {
                    if ($data['filename']['value'] != '') {
                        $_helper = Mage::helper('upload');
                        $this->removeFile(Mage::getBaseDir('media').DS.$_helper->updateDirSepereator($data['filename']['value']));
                    }
                    $data['filename'] = '';
                } else {
                    unset($data['filename']);
                }
            }
            $model = Mage::getModel('upload/upload');
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
                Mage::helper('upload')->cleanCache(Tools_Upload_Block_Upload::CACHE_TAG.'_'.$this->getRequest()->getParam('id'));
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('upload')->__('Item was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('upload')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('upload/upload')->load($this->getRequest()->getParam('id'));
                $_helper = Mage::helper('upload');
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
        $uploadIds = $this->getRequest()->getParam('upload');
        if (!is_array($uploadIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($uploadIds as $uploadId) {
                    $model = Mage::getModel('upload/upload')->load($uploadId);
                    $_helper = Mage::helper('upload');
                    $filePath = Mage::getBaseDir('media').DS.$_helper->updateDirSepereator($model->getFilename());
                    $model->delete();
                    $this->removeFile($filePath);
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($uploadIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $uploadIds = $this->getRequest()->getParam('upload');
        if (!is_array($uploadIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($uploadIds as $uploadId) {
                    $upload = Mage::getSingleton('upload/upload')
                                    ->load($uploadId)
                                    ->setStatus($this->getRequest()->getParam('status'))
                                    ->setIsMassupdate(true)
                                    ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($uploadIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction() {
        $fileName = 'upload.csv';
        $content = $this->getLayout()->createBlock('upload/adminhtml_upload_grid')
                        ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'upload.xml';
        $content = $this->getLayout()->createBlock('upload/adminhtml_upload_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('tools/upload/upload');
    }
}