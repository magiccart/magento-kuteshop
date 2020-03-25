<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2014-08-07 22:10:30
 * @@Modify Date: 2017-03-11 18:48:18
 * @@Function:
 */

class Magiccart_Shopbrand_Model_System_Config_Row
{
    public function toOptionArray()
    {
        return array(
            array('value' => 1,   'label'=>Mage::helper('adminhtml')->__('1 row')),
            array('value' => 2,   'label'=>Mage::helper('adminhtml')->__('2 row(s)')),
            array('value' => 3,   'label'=>Mage::helper('adminhtml')->__('3 row(s)')),
            array('value' => 4,   'label'=>Mage::helper('adminhtml')->__('4 row(s)')),
            array('value' => 5,   'label'=>Mage::helper('adminhtml')->__('5 row(s)')),
        );
    }
}
