<?php
/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Categorytools
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tools_Categorytools_Block_Adminhtml_Categorytoolsgroup_Edit_Tab_Categorytools extends Tools_Categorytools_Block_Adminhtml_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('categorytoolsLeftGrid');
        $this->setDefaultSort('categorytools_id');
        $this->setUseAjax(true);
    }

    public function getCategorytoolsgroupData() {
        return Mage::registry('categorytoolsgroup_data');
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('categorytools/categorytools')->getCollection();
        $collection->getSelect()->order('categorytools_id');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _addColumnFilterToCollection($column) {
        if ($this->getCollection()) {
            if ($column->getId() == 'categorytools_triggers') {
                $categorytoolsIds = $this->_getSelectedCategorytoolss();
                if (empty($categorytoolsIds)) {
                    $categorytoolsIds = 0;
                }
                if ($column->getFilter()->getValue()) {
                    $this->getCollection()->addFieldToFilter('categorytools_id', array('in' => $categorytoolsIds));
                } else {
                    if ($categorytoolsIds) {
                        $this->getCollection()->addFieldToFilter('categorytools_id', array('nin' => $categorytoolsIds));
                    }
                }
            } else {
                parent::_addColumnFilterToCollection($column);
            }
        }
        return $this;;
    }

    protected function _prepareColumns() {
        $this->addColumn('categorytools_triggers', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'values' => $this->_getSelectedCategorytoolss(),
            'align' => 'center',
            'index' => 'categorytools_id'
        ));
        $this->addColumn('categorytools_id', array(
            'header' => Mage::helper('catalog')->__('ID'),
            'sortable' => true,
            'width' => '50',
            'align' => 'center',
            'index' => 'categorytools_id'
        ));

        $this->addColumn('filename', array(
            'header' => Mage::helper('categorytools')->__('Image'),
            'align' => 'center',
            'index' => 'filename',
            'type' => 'categorytools',
            'escape' => true,
            'sortable' => false,
            'width' => '150px',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('catalog')->__('Title'),
            'index' => 'title',
            'align' => 'left',
        ));

        $this->addColumn('link', array(
            'header' => Mage::helper('categorytools')->__('Link'),
            'width' => '200px',
            'index' => 'link',
        ));

        $this->addColumn('categorytools_type', array(
            'header' => Mage::helper('categorytools')->__('Type'),
            'width' => '100px',
            'index' => 'categorytools_type',
            'type' => 'options',
            'options' => array(
                0 => 'Image',
                1 => 'Html',
            ),
        ));
        
        $this->addColumn('sort_order', array(
            'header' => Mage::helper('categorytools')->__('Sort Order'),
            'width' => '80px',
            'index' => 'sort_order',
            'align' => 'center',
        ));
        return parent::_prepareColumns();
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/categorytoolsgrid', array('_current' => true));
    }

    protected function _getSelectedCategorytoolss() {
        $categorytoolss = $this->getRequest()->getPost('selected_categorytoolss');
        if (is_null($categorytoolss)) {
            $categorytoolss = explode(',', $this->getCategorytoolsgroupData()->getCategorytoolsIds());
            return (sizeof($categorytoolss) > 0 ? $categorytoolss : 0);
        }
        return $categorytoolss;
    }

}

?>