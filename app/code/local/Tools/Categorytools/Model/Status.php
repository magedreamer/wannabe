<?php
/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Categorytools
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tools_Categorytools_Model_Status extends Varien_Object {
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    static public function getOptionArray() {
        return array(
            self::STATUS_ENABLED => Mage::helper('categorytools')->__('Enabled'),
            self::STATUS_DISABLED => Mage::helper('categorytools')->__('Disabled')
        );
    }

    static public function getAnimationArray() {
        $animations = array();
        $animations = array(
            array(
                'value' => 'Fade/Appear',
                'label' => Mage::helper('categorytools')->__('Fade / Appear'),
            ),
            array(
                'value' => 'Shake',
                'label' => Mage::helper('categorytools')->__('Shake'),
            ),

            array(
                'value' => 'Pulsate',
                'label' => Mage::helper('categorytools')->__('Pulsate'),
            ),
            array(
                'value' => 'Puff',
                'label' => Mage::helper('categorytools')->__('Puff'),
            ),
            array(
                'value' => 'Grow',
                'label' => Mage::helper('categorytools')->__('Grow'),
            ),
            array(
                'value' => 'Shrink',
                'label' => Mage::helper('categorytools')->__('Shrink'),
            ),
            array(
                'value' => 'Fold',
                'label' => Mage::helper('categorytools')->__('Fold'),
            ),         
            array(
                'value' => 'Squish',
                'label' => Mage::helper('categorytools')->__('Squish'),
            ),
   
            array(
                'value' => 'BlindUp',
                'label' => Mage::helper('categorytools')->__('Blindup'),
            ),
             array(
                'value' => 'BlindDown',
                'label' => Mage::helper('categorytools')->__('BlindDown'),
            ),            
            array(
                'value' => 'DropOut',
                'label' => Mage::helper('categorytools')->__('DropOut'),
            ),
        );
        array_unshift($animations, array('label' => '--Select--', 'value' => ''));
        return $animations;
    }

    static public function getPreAnimationArray() {
        $animations = array();
        $animations = array(

            array(
                'value' => 'Image Simple',
                'label' => Mage::helper('categorytools')->__('Image simple'),
            ),

            array(
                'value' => 'Image Slide Show',
                'label' => Mage::helper('categorytools')->__('Image Slide Show'),
            ),
            array(
                'value' => 'Text Fade Categorytools',
                'label' => Mage::helper('categorytools')->__('Text Fade Categorytools'),
            ),
            array(
                'value' => 'Square Transition Categorytools',
                'label' => Mage::helper('categorytools')->__('Square Transition Categorytools'),
            ),
            array(
                'value' => 'Play And Pause Slide Show',
                'label' => Mage::helper('categorytools')->__('Play And Pause Slide Show'),
            ),
            array(
                'value' => 'Numbered Categorytools',
                'label' => Mage::helper('categorytools')->__('Numbered Categorytools'),
            ),
            array(
                'value' => 'image glider',
                'label' => Mage::helper('categorytools')->__('Image Slider'),
            ),
            array(
                'value' => 'image vertical slider',
                'label' => Mage::helper('categorytools')->__('Image Vertical Slider'),
            ),

             /*array(
                'value' => 'image spring slider',
                'label' => Mage::helper('categorytools')->__('Image Spring Slider'),
            ),*/
        );
        array_unshift($animations, array('label' => '--Select--', 'value' => ''));
        return $animations;
    }

}