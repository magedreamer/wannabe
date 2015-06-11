<?php
/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Categorytools
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tools_Categorytools_Block_Adminhtml_Categorytools_Grid extends Tools_Categorytools_Block_Adminhtml_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('categorytoolsGrid');
        $this->setDefaultSort('categorytools_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('categorytools/categorytools')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('categorytools_id', array(
            'header' => Mage::helper('categorytools')->__('ID'),
            'align' => 'center',
            'width' => '30px',
            'index' => 'categorytools_id',
        ));
/*
        $this->addColumn('filename', array(
            'header' => Mage::helper('categorytools')->__('Image'),
            'align' => 'center',
            'index' => 'filename',
            'type' => 'categorytools',
            'escape' => true,
            'sortable' => false,
            'width' => '150px',
        ));
*/
        $this->addColumn('title', array(
            'header' => Mage::helper('categorytools')->__('Title'),
            'index' => 'title',
        ));
/*
        $this->addColumn('link', array(
            'header' => Mage::helper('categorytools')->__('Web Url'),
            'width' => '150px',
            'index' => 'link',
        ));        

        $this->addColumn('categorytools_type', array(
            'header' => Mage::helper('categorytools')->__('Type'),
            'width' => '80px',
            'index' => 'categorytools_type',
            'type' => 'options',
            'options' => array(
                0 => 'Image',
                1 => 'Html',
            ),
        ));
*/
                $this->addColumn(
            'category_name',
            array(
                'header' => Mage::helper('tools_feature')->__('Category Name'), 
                'index' => 'category_name',
            )
        );
        $this->addColumn('sort_order', array(
            'header' => Mage::helper('categorytools')->__('Sort Order'),
            'width' => '80px',
            'index' => 'sort_order',
            'align' => 'center',
        ));


        $this->addColumn('status', array(
            'header' => Mage::helper('categorytools')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => 'Enabled',
                2 => 'Disabled',
            ),
        ));

        $this->addColumn('action',
                array(
                    'header' => Mage::helper('categorytools')->__('Action'),
                    'width' => '80',
                    'type' => 'action',
                    'getter' => 'getId',
                    'actions' => array(
                        array(
                            'caption' => Mage::helper('categorytools')->__('Edit'),
                            'url' => array('base' => '*/*/edit'),
                            'field' => 'id'
                        )
                    ),
                    'filter' => false,
                    'sortable' => false,
                    'index' => 'stores',
                    'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('categorytools')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('categorytools')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('categorytools_id');
        $this->getMassactionBlock()->setFormFieldName('categorytools');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('categorytools')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('categorytools')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('categorytools/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('categorytools')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('categorytools')->__('Status'),
                    'values' => $statuses
                )
            )
        ));
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}