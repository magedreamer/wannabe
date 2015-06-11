<?php
class Tools_Swatch_Block_Adminhtml_Swatch_Grid extends Tools_Swatch_Block_Adminhtml_Widget_Grid
{
      public function __construct()
      {
          parent::__construct();
          $this->setId('swatchGrid');
          $this->setDefaultSort('id');
          $this->setDefaultDir('ASC');
          $this->setSaveParametersInSession(true);
      }

      protected function _prepareCollection()
      {
          $collection = Mage::getModel('swatch/swatch')->getCollection();
          $this->setCollection($collection);
          return parent::_prepareCollection();
      }

      protected function _prepareColumns()
      {

          $this->addColumn('id', array(
              'header'    => Mage::helper('swatch')->__('ID'),
              'align'     =>'center',
              'width'     => '50px',
              'index'     => 'id',
          ));
          
          $this->addColumn('image', array(
            'header' => Mage::helper('swatch')->__('Image'),
            'align' => 'center',
            'index' => 'image',
            'type' => 'swatch',
            'escape' => true,
            'sortable' => false,
            'width' => '150px',
        ));

        $this->addColumn('swatch_name', array(
               'header'    => Mage::helper('swatch')->__('swatch'),
               'align'     =>'left',
               'index'     => 'swatch_name',
        ));

        return parent::_prepareColumns();
      }

      public function getRowUrl($row)
      {
          return $this->getUrl('*/*/edit', array('id' => $row->getId()));
      }
      
}
?>
