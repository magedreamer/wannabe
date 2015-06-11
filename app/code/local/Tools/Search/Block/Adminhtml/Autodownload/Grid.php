<?php
class Tools_Search_Block_Adminhtml_Autodownload_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
	      parent::__construct();
	      $this->setId('autodownloadGrid');
	      $this->setDefaultSort('id');
	      $this->setDefaultDir('DESC');
	      $this->setSaveParametersInSession(true);
	}
  
	protected function _prepareCollection()
	{
		$collection = Mage::getModel('search/autodownload')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns()
	{
		$this->addColumn('id', array(
	          'header'    => Mage::helper('search')->__('ID'),
	          'align'     =>'right',
	          'width'     => '50px',
	          'index'     => 'id',
		));
	
		$this->addColumn('isbn', array(
	          'header'    => Mage::helper('search')->__('ISBN'),
	          'align'     =>'left',
	          'index'     => 'isbn',
		));
		
		$this->addColumn('datetime', array(
	          'header'    => Mage::helper('search')->__('Date'),
	          'align'     =>'left',
			  'type'      => 'datetime',
	          'index'     => 'datetime',
		));
	
		$this->addColumn('status', array(
	          'header'    => Mage::helper('search')->__('Status'),
	          'align'     => 'left',
	          'width'     => '180px',
	          'index'     => 'status',
	          'type'      => 'options',
	          'options'   => array(
	              1 => "Download",
				  2 => "Don't Download",
	              3 => "Success",
	              4 => "Fail",
	              5 => "Downloading",
	              6 => "Exist",
	              7 => "Not Result"

	          ),
		));
		  
			
		$this->addExportType('*/*/exportCsv', Mage::helper('search')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('search')->__('XML'));
		  
		return parent::_prepareColumns();
	}
	
	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('id');
		$this->getMassactionBlock()->setFormFieldName('autodownload');
	
		$this->getMassactionBlock()->addItem('delete', array(
	             'label'    => Mage::helper('search')->__('Delete'),
	             'url'      => $this->getUrl('*/*/massDelete'),
	             'confirm'  => Mage::helper('search')->__('Are you sure?')));
		
	
		$statuses = Mage::getSingleton('search/status')->getDownloadOptionArray();
	
		array_unshift($statuses, array('label'=>'', 'value'=>''));
		$this->getMassactionBlock()->addItem('status', array(
	             'label'=> Mage::helper('search')->__('Change status'),
	             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
		                'additional' => array(
	                    'visibility' => array(
	                         'name' => 'status',
	                         'type' => 'select',
	                         'class' => 'required-entry',
	                         'label' => Mage::helper('search')->__('Status'),
	                         'values' => $statuses
	                     )
	             )
			));
		return $this;
	}
	
	public function getRowUrl($row)
	{
		return ;//$this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
}