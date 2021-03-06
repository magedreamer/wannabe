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


class Tools_Search_Block_Advancedsearch_Result extends AW_Advancedsearch_Block_Result
{

    public function setListOrders()
    {
        $category = Mage::getSingleton('catalog/layer')
            ->getCurrentCategory();
        /* @var $category Mage_Catalog_Model_Category */
        $availableOrders = $category->getAvailableSortByOptions();
        unset($availableOrders['position']);
        $availableOrders = array_merge(
            array('relevance' => $this->__('Relevance')),
            $availableOrders
        );

        $catalogConfig = Mage::getSingleton('catalog/config');
        foreach ($availableOrders as $code => $title) {
            if ($title === null) {
                $attribute = $catalogConfig->getAttribute(Mage_Catalog_Model_Product::ENTITY, $code);
                if ($attribute && $attribute->getData('frontend_label')) {
                    $availableOrders[$code] = $this->__($attribute->getData('frontend_label'));
                }
            }
        }
        $this->getListBlock()
            ->setAvailableOrders($availableOrders)
            ->setDefaultDirection('desc')
            ->setSortBy('relevance');
        return $this;
    }
}
