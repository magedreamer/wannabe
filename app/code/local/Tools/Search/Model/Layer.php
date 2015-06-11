<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Advancedsearch
 * @version    1.2.2
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 */


class Tools_Search_Model_Layer extends AW_Advancedsearch_Model_Layer
{
    public function getProductCollection($searchbool = null)
    {
        if($searchbool) {
            $stimer = explode( ' ', microtime() );
            $stimer = $stimer[1] + $stimer[0];
            Mage::register('stimer', $stimer);
        }
        if(isset($this->_productCollections[$this->getCurrentCategory()->getId()])) {
            $collection = $this->_productCollections[$this->getCurrentCategory()->getId()];
        } else {
            $collection = $this->prepareProductCollection(null);
            $this->_productCollections[$this->getCurrentCategory()->getId()] = $collection;
        }
        if($searchbool) {
            $etimer = explode( ' ', microtime() );
            $etimer = $etimer[1] + $etimer[0];
            echo '<p style="margin:auto; text-align:center">';
            printf( "Script timer: <b>%f</b> seconds.", ($etimer-Mage::registry('stimer')) );
            echo '</p>';
            //Zend_Debug::dump('chinhtest'.' advancedsearch');
        }
        return $collection;
    }
}
