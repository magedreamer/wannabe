<?php
/**
 * 

 * @package    Uni_Upload


 */
require_once 'Mage/Adminhtml/Block/Widget/Grid/Column.php';

class Tools_Upload_Block_Adminhtml_Widget_Grid_Column extends Mage_Adminhtml_Block_Widget_Grid_Column {

    protected function _getRendererByType() {
        switch (strtolower($this->getType())) {
            case 'upload':
                $rendererClass = 'upload/adminhtml_widget_grid_column_renderer_upload';
                break;
            default:
                $rendererClass = parent::_getRendererByType();
                break;
        }
        return $rendererClass;
    }

    protected function _getFilterByType() {
        switch (strtolower($this->getType())) {
            case 'upload':
                $filterClass = 'upload/adminhtml_widget_grid_column_filter_upload';
                break;
            default:
                $filterClass = parent::_getFilterByType();
                break;
        }
        return $filterClass;
    }

}