<?php
class Tools_Swatch_Model_Mysql4_Option extends Mage_Core_Model_Mysql4_Abstract
{
    const PUBLISHER_CODE = 'swatch';
    
    public function _construct()
    {
        $this->_init('swatch/option','option_id');
    }

    public function getAttributeIdOfSwatch(){
        $select = $this->_getReadAdapter()->select()
                    ->from('eav_attribute','attribute_id')
                    ->where('attribute_code = ?', self::PUBLISHER_CODE);
        return $this->_getReadAdapter()->fetchOne($select);
    }

    public function getOptionValuesOfSwatch(){

        $attri = $this->getAttributeIdOfSwatch();
        if($attri){
            $select = $this->_getReadAdapter()->select()
                        ->from(array("op" => $this->getMainTable()),'op.option_id')
                        ->join(array('opv'=>'eav_attribute_option_value'), 'op.option_id = opv.option_id','opv.value')
                        ->where('op.attribute_id = ?', $attri)
                        ->where('opv.store_id = ?', 0);
            
            return $this->_getReadAdapter()->fetchAll($select);
        }
        return false;
    }

}


?>
