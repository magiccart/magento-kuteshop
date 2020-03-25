<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: Magiccart<team.magiccart@gmail.com>
 * @@Create Date: 2014-08-07 22:10:30
 * @@Modify Date: 2015-10-20 14:33:36
 * @@Function:
 */
?>
<?php

class Magiccart_Megashop_Model_System_Config_Category extends Varien_Object
{
 
    const PREFIX_ROOT = '*';    
    const REPEATER = '*';
    const PREFIX_END = '';
    protected $_options = array();
 
    public function toOptionArray()
    {
        $categories = Mage::getModel('adminhtml/system_config_source_category')->toOptionArray();
        $options = array();
        foreach ($categories as $category) {
            $this->_options = array();
            if($category['value']) {
                $_categories = Mage::getModel('catalog/category')->getCategories($category['value']);
                if($_categories){
                    // $rootOption = array('label' => $category['label']);
                    foreach ($_categories as $_category) {
                        $this->_options[] = array(
                            'label' => self::PREFIX_ROOT .$_category->getName(),
                            'value' => $_category->getEntityId()
                        );
                        if ($_category->hasChildren()) $this->_getChildOptions($_category->getChildren());
                    }
                    // $rootOption['value'] = $this->_options;
                    // $options[] = $rootOption;
                    if($this->_options){
                        $options[] = array(
                            'label' => $category['label'],
                            'value' => $this->_options
                        );
                    }
                }
            }
        }
        return $options;
    }
 
    protected function _getChildOptions($categories)
    {
        foreach ($categories as $category) {
            $prefix = str_repeat(self::REPEATER, $category->getLevel() * 1) . self::PREFIX_END;
            $this->_options[] = array(
                'label' => $prefix . $category->getName(),
                'value' => $category->getEntityId()
            );
            if ($category->hasChildren()) $this->_getChildOptions($category->getChildren());
        }
    }

}
