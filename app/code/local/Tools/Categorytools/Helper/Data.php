<?php

/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Categorytools
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tools_Categorytools_Helper_Data extends Mage_Core_Helper_Abstract {

    protected static $egridImgDir = null;
    protected static $egridImgURL = null;
    protected static $egridImgThumb = null;
    protected static $egridImgThumbWidth = null;
    protected $_allowedExtensions = Array();

    public function __construct() {
        self::$egridImgDir = Mage::getBaseDir('media') . DS;
        self::$egridImgURL = Mage::getBaseUrl('media');
        self::$egridImgThumb = "thumb/";
        self::$egridImgThumbWidth = 100;
    }

    public function updateDirSepereator($path) {
        return str_replace('\\', DS, $path);
    }

    public function getImageUrl($image_file) {
        $url = false;
        if (file_exists(self::$egridImgDir . self::$egridImgThumb . $this->updateDirSepereator($image_file)))
            $url = self::$egridImgURL . self::$egridImgThumb . $image_file;
        else
            $url = self::$egridImgURL . $image_file;
        return $url;
    }

    public function getFileExists($image_file) {
        $file_exists = false;
        $file_exists = file_exists(self::$egridImgDir . $this->updateDirSepereator($image_file));
        return $file_exists;
    }

    public function getImageThumbSize($image_file) {
        $img_file = $this->updateDirSepereator(self::$egridImgDir . $image_file);
        if ($image_file == '' || !file_exists($img_file))
            return false;
        list($width, $height, $type, $attr) = getimagesize($img_file);
        $a_height = (int) ((self::$egridImgThumbWidth / $width) * $height);
        return Array('width' => self::$egridImgThumbWidth, 'height' => $a_height);
    }

    function deleteFiles($image_file) {
        $pass = true;
        if (!unlink(self::$egridImgDir . $image_file))
            $pass = false;
        if (!unlink(self::$egridImgDir . self::$egridImgThumb . $image_file))
            $pass = false;
        return $pass;
    }

    public function cleanCache($tags = array()) {
        return parent::_cleanCache($tags);
    }

    public function showLableSaleOff($_product) {

        $html = '';
        if ($_product->getEntityId() > 0) {
            $_taxHelper = Mage::helper('tax');
            $_simplePricesTax = ($_taxHelper->displayPriceIncludingTax() || $_taxHelper->displayBothPrices());
            $_regularPrice = $_taxHelper->getPrice($_product, $_product->getPrice(), $_simplePricesTax);

            $_finalPrice = $_taxHelper->getPrice($_product, $_product->getFinalPrice());
            if ($_regularPrice != $_finalPrice) {
                //    $html = '<span class="slOf_flag">';
                //   $html .='<span class="slOf_sale">-' . round(sprintf("%01.2f",@(($_regularPrice - $_finalPrice)/$_regularPrice)*100)) . '%';
                //   $html .='</span></span>';
                $html = '-'.round(sprintf("%01.2f", @(($_regularPrice - $_finalPrice) / $_regularPrice) * 100)) . '%';
            }
        }
        return $html;
    }

}
