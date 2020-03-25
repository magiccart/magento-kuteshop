<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: Magiccart<team.magiccart@gmail.com>
 * @@Create Date: 2014-08-07 22:10:30
 * @@Modify Date: 2017-03-11 22:35:31
 * @@Function:
 */
?>
<?php
class Magiccart_Magiccategory_Model_System_Config_Column
{
    public function toOptionArray()
    {
        return array(
            array('value' => 1,   'label'=>Mage::helper('adminhtml')->__('1 item(s) /row')),
            array('value' => 2,   'label'=>Mage::helper('adminhtml')->__('2 item(s) /row')),
            array('value' => 3,   'label'=>Mage::helper('adminhtml')->__('3 item(s) /row')),
            array('value' => 4,   'label'=>Mage::helper('adminhtml')->__('4 item(s) /row')),
            array('value' => 5,   'label'=>Mage::helper('adminhtml')->__('5 item(s) /row')),
            array('value' => 6,   'label'=>Mage::helper('adminhtml')->__('6 item(s) /row')),
            array('value' => 7,   'label'=>Mage::helper('adminhtml')->__('7 item(s) /row')),
            array('value' => 8,   'label'=>Mage::helper('adminhtml')->__('8 item(s) /row')),
            array('value' => 9,   'label'=>Mage::helper('adminhtml')->__('9 item(s) /row')),
            array('value' => 10,  'label'=>Mage::helper('adminhtml')->__('10 item(s) /row')),
        );
    }
}
