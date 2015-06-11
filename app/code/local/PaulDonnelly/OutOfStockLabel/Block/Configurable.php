<?php
 
class PaulDonnelly_OutOfStockLabel_Block_Configurable extends Mage_Catalog_Block_Product_View_Type_Configurable
{   
    /*public function getAllowProducts() {
        if (!$this->hasAllowProducts()) {
            $products = array();
            $allProducts = $this->getProduct()->getTypeInstance(true)->getUsedProducts(null, $this->getProduct());
            foreach ($allProducts as $product) {
                $products[] = $product;
            }
            $this->setAllowProducts($products);
        }
        return $this->getData('allow_products');
    }*/
 protected $_options;
    public function getStockStatus()
    {

        $jsonConfig = parent::getJsonConfig();
        $config = Zend_Json::decode($jsonConfig);
  $i = 0;
        foreach ($config['attributes'] as $key1 => $attr)
        {
            foreach ($attr['options'] as $key2 => $options)
            {
                $this->_options[$i][$options['id']] = $options['products'];
                foreach ($allProducts as $product)
                {
                    $key = array();
                    for ($i = 0; $i < count($this->_options); $i++)
                    {
                        foreach ($this->_options[$i] as $iOptionId => $productIds)
                        {
                            if (in_array($product->getId(), $productIds))
                            {
                             $key[] = $iOptionId;
                            }
                        }
                    }
                }
            }
        }


            $aStockStatus = array();
            $allProducts = $this->getProduct()->getTypeInstance(true)
                ->getUsedProducts(null, $this->getProduct());

            foreach ($allProducts as $product)
            {
                $key = array();
                for ($i = 0; $i < count($this->_options); $i++)
                {
                  
                    foreach ($this->_options[$i] as $iOptionId => $productIds)
                    {
                        if (in_array($product->getId(), $productIds))
                        {
                            $key[] = $iOptionId;
                        }
                    }
                }
              
                $stockStatus = '';
                $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product);
  
                if (!$product->isInStock())
                {
                    $stockStatus = 'outstock';
                } else 
                {
                    $stockStatus = 'in stock';
                }
           
                if ($key)
                {
                    $aStockStatus[implode(',', $key)] = array(
                        'is_in_stock'   => $product->isSaleable(),
                        'custom_status' => '',
                        'product_id'    => $product->getId(),
                        'stockalert'    => 'stockalert',
                    );
                }
            }

            foreach ($aStockStatus as $k=>$v){
                if (!$v['is_in_stock'] && !$v['custom_status']){
                   // $v['custom_status'] = 'Out of Stock';
                    $aStockStatus[$k] = $v;
                }   
            }
          return Zend_Json::encode($aStockStatus) ;
        }

    



     public function getAllowProducts()
    {
        if (!$this->hasAllowProducts()) {
            $products = array();
            $allProducts = $this->getProduct()->getTypeInstance(true)
                ->getUsedProducts(null, $this->getProduct());
            foreach ($allProducts as $product) {
                /**
                * Should show all products (if setting set to Yes), but not allow "out of stock" to be added to cart
                */
               
                    if ($product->getStatus() != Mage_Catalog_Model_Product_Status::STATUS_DISABLED)
                    {
                        $products[] = $product;
                    }
                
            }
            $this->setAllowProducts($products);
        }
        return $this->getData('allow_products');
    }
}