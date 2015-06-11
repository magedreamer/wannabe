<?php
class Tools_Swatch_Model_Mysql4_Swatch extends Mage_Core_Model_Mysql4_Abstract
{
    const LOG_FILENAME = 'swatch.log';

    public function _construct()
    {
        $this->_init('swatch/swatch','id');
    }
/*
    public function checkIsSwatch($option_id){
        if($option_id){
            $select = $this->_getReadAdapter()->select()
                    ->from($this->getMainTable(),'id')
                    ->where('option_id = ?',$option_id);
            return $this->_getReadAdapter()->fetchOne($select);
        }
        return false;
    }
    
    public function updateSwatch($data,$id){
        if($id){
             return $this->_getWriteAdapter()->update($this->getMainTable(),$data,'id='.$id);
        }
        return false;
    }

    public function copyOptionToSwatch(){
        $options = Mage::getResourceModel('swatch/option')->getOptionValuesOfSwatch();
        //Mage::log($options,6,'o.log');
        $swatch = Mage::getSingleton('swatch/swatch');
        if($options){
            foreach($options as $option){
                if(!$id = $this->checkIsSwatch($option['option_id'])){
                    try{
                        $data['option_id']= $option['option_id'];
                        $data['attribute_id']= Mage::getResourceModel('swatch/option')->getAttributeIdOfSwatch();
                        $data['swatch_name']= $option['value'];
                        $data['created_at']= Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
                        $swatch->setData($data);
                        $swatch->save();
                        $swatch->setData('');
                    }catch (Exception $e){
                        Mage::log($e->getMessage(),3,self::LOG_FILENAME);
                    }
                }else{
                    $data['swatch_name']= $option['value'];
                    $this->updateSwatch($data,$id);
                }
            }
            return true;
        }
        return false;
    }

    public function getSwatchByOption($option_id){
        if($option_id){
            $select = $this->_getReadAdapter()->select()
                    ->from($this->getMainTable())
                    ->where('option_id = ?',$option_id);
            return $this->_getReadAdapter()->fetchRow($select);
        }
        return false;
    }
    */
}

?>
