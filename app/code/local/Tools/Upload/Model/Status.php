<?php
/**
 * 

 * @package    Uni_Upload


 */
class Tools_Upload_Model_Status extends Varien_Object {
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    static public function getOptionArray() {
        return array(
            self::STATUS_ENABLED => Mage::helper('upload')->__('Enabled'),
            self::STATUS_DISABLED => Mage::helper('upload')->__('Disabled')
        );
    }

    static public function getAnimationArray() {
        $animations = array();
        $animations = array(
            array(
                'value' => 'Fade/Appear',
                'label' => Mage::helper('upload')->__('Fade / Appear'),
            ),
            array(
                'value' => 'Shake',
                'label' => Mage::helper('upload')->__('Shake'),
            ),

            array(
                'value' => 'Pulsate',
                'label' => Mage::helper('upload')->__('Pulsate'),
            ),
            array(
                'value' => 'Puff',
                'label' => Mage::helper('upload')->__('Puff'),
            ),
            array(
                'value' => 'Grow',
                'label' => Mage::helper('upload')->__('Grow'),
            ),
            array(
                'value' => 'Shrink',
                'label' => Mage::helper('upload')->__('Shrink'),
            ),
            array(
                'value' => 'Fold',
                'label' => Mage::helper('upload')->__('Fold'),
            ),         
            array(
                'value' => 'Squish',
                'label' => Mage::helper('upload')->__('Squish'),
            ),
   
            array(
                'value' => 'BlindUp',
                'label' => Mage::helper('upload')->__('Blindup'),
            ),
             array(
                'value' => 'BlindDown',
                'label' => Mage::helper('upload')->__('BlindDown'),
            ),            
            array(
                'value' => 'DropOut',
                'label' => Mage::helper('upload')->__('DropOut'),
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
                'label' => Mage::helper('upload')->__('Image simple'),
            ),

            array(
                'value' => 'Image Slide Show',
                'label' => Mage::helper('upload')->__('Image Slide Show'),
            ),
            array(
                'value' => 'Text Fade Upload',
                'label' => Mage::helper('upload')->__('Text Fade Upload'),
            ),
            array(
                'value' => 'Square Transition Upload',
                'label' => Mage::helper('upload')->__('Square Transition Upload'),
            ),
            array(
                'value' => 'Play And Pause Slide Show',
                'label' => Mage::helper('upload')->__('Play And Pause Slide Show'),
            ),
            array(
                'value' => 'Numbered Upload',
                'label' => Mage::helper('upload')->__('Numbered Upload'),
            ),
            array(
                'value' => 'image glider',
                'label' => Mage::helper('upload')->__('Image Slider'),
            ),
            array(
                'value' => 'image vertical slider',
                'label' => Mage::helper('upload')->__('Image Vertical Slider'),
            ),

             /*array(
                'value' => 'image spring slider',
                'label' => Mage::helper('upload')->__('Image Spring Slider'),
            ),*/
        );
        array_unshift($animations, array('label' => '--Select--', 'value' => ''));
        return $animations;
    }

}