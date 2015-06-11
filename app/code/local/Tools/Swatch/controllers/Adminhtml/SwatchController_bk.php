<?php
class Tools_Swatch_Adminhtml_SwatchController extends Mage_Adminhtml_Controller_action {
  protected $_imageInstance;
    public function indexAction() {
	$this->loadLayout();
        $this->renderLayout();
	
	//$this->_redirectUrl('http://google.com.vn');
    }
    public function uploadAction()
    {
         $productId = $this->getRequest()->getParam('id'); 
         if ($productId) {
           $backUrl = $this->getUrl('adminhtml/catalog_product/edit', array(
                'id'    => $productId,
                'tab'   => 'product_info_tabs_swatch_customoptions',
            ));
            $attributeId = $this->getRequest()->getParam('attribute_id');
            $toDelete = Mage::app()->getRequest()->getPost('swatch_delete');
            if ($toDelete) {
                $this->_getImageInstance()->deleteImages(array_keys($toDelete));
            }
            
            $files = isset($_FILES['customoptions_swatches']) ? $_FILES['customoptions_swatches'] : array();
            if ($files) {
                foreach ($files['name'] as $key => $file) {
                    if ($files['error'][$key] == UPLOAD_ERR_OK) {
                        try {
                            $uploader = new Varien_File_Uploader(array(
                                'name' => $file,
                                'tmp_name' => $files['tmp_name'][$key],
                            ));
                            $uploader->setAllowedExtensions($this->_getAllowedExtensions());
                            $uploader->setAllowRenameFiles(true);
                            $uploader->setFilesDispersion(false);
                            $uploader->save($this->_getDestinationFolder());
			
				$swatch = Mage::helper('swatch')->getSwatchByProductOption($productId,$attributeId,$key);
				if($swatch )
				{
				        $imageFolder = Mage::helper('swatch')->getImagePath();
				
					@unlink($imageFolder .$swatch->getImage());
					$swatch->setImage($uploader->getUploadedFileName());
					$swatch->save();
				} 
				else
				{
					 $model = Mage::getModel('swatch/swatch');
					 $model->setAttribute_id($attributeId);
					 $model->setProduct_id($productId);
					 $model->setOption_id($key);
					 $model->setImage($uploader->getUploadedFileName());
					 $model->save();
				}
			
                        } catch (Exception $e) {
			
                            $this->_getSession()->addError($e->getMessage());
                        }
                    }
                }
            }
        }
        else {
            $backUrl = Mage::helper('adminhtml')->getUrl('*/catalog_product/index');
            $this->_getSession()
                    ->addError('Images were not uploaded. Please try again.');
        }

        $this->_redirectUrl($backUrl);

    }
    
    protected function _getAllowedExtensions()
    {
        return Mage::helper('swatch')->getAllowedExtensions();
    }
    
    protected function _getDestinationFolder()
    {
        return Mage::helper('swatch')->getImagePath();
    }
    
    protected function _getImageInstance()
    {
        
    }
    public function deleteImages($option_type_ids = array())
    {
        if ($option_type_ids) {
            $select = $this->_getWriteAdapter()->select()
                ->from($this->getMainTable())
                ->where('option_type_id IN (?)', $option_type_ids);

            $result = $this->_getWriteAdapter()->fetchAll($select);
            $imageFolder = Mage::helper('swatch')->getImagePath();
            foreach($result as $record) {
                @unlink($imageFolder . $record['image']);
            }
   
            $this->_getWriteAdapter()->delete(
                $this->getMainTable(),
                array('option_type_id IN (?)' => $option_type_ids)
            );
        }
        return $this;
    }

}