<?php
/**
 * 

 * @package    Uni_Upload


 */
class Tools_Upload_Block_Adminhtml_Upload_Grid extends Tools_Upload_Block_Adminhtml_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('uploadGrid');
        $this->setDefaultSort('upload_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('upload/upload')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('upload_id', array(
            'header' => Mage::helper('upload')->__('ID'),
            'align' => 'center',
            'width' => '30px',
            'index' => 'upload_id',
        ));

        $this->addColumn('filename', array(
            'header' => Mage::helper('upload')->__('Image'),
            'align' => 'center',
            'index' => 'filename',
            'type' => 'upload',
            'escape' => true,
            'sortable' => false,
            'width' => '150px',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('upload')->__('Title'),
            'index' => 'title',
        ));

        $this->addColumn('link', array(
            'header' => Mage::helper('upload')->__('Web Url'),
            'width' => '150px',
            'index' => 'link',
        ));        

        $this->addColumn('upload_type', array(
            'header' => Mage::helper('upload')->__('Type'),
            'width' => '80px',
            'index' => 'upload_type',
            'type' => 'options',
            'options' => array(
                0 => 'Image',
                1 => 'Html',
            ),
        ));

        $this->addColumn('sort_order', array(
            'header' => Mage::helper('upload')->__('Sort Order'),
            'width' => '80px',
            'index' => 'sort_order',
            'align' => 'center',
        ));


        $this->addColumn('status', array(
            'header' => Mage::helper('upload')->__('Status'),
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
                    'header' => Mage::helper('upload')->__('Action'),
                    'width' => '80',
                    'type' => 'action',
                    'getter' => 'getId',
                    'actions' => array(
                        array(
                            'caption' => Mage::helper('upload')->__('Edit'),
                            'url' => array('base' => '*/*/edit'),
                            'field' => 'id'
                        )
                    ),
                    'filter' => false,
                    'sortable' => false,
                    'index' => 'stores',
                    'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('upload')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('upload')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('upload_id');
        $this->getMassactionBlock()->setFormFieldName('upload');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('upload')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('upload')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('upload/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('upload')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('upload')->__('Status'),
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