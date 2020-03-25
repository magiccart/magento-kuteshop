<?php
/**
 * Magiccart 
 * @category 	Magiccart 
 * @copyright 	Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license 	http://www.magiccart.net/license-agreement.html
 * @Author: Magiccart<team.magiccart@gmail.com>
 * @@Create Date: 2014-04-14 15:31:56
 * @@Modify Date: 2015-10-26 18:01:19
 * @@Function:
 */

class Magiccart_Magiccategory_Block_Widget_Categories extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{

    public function getCategories()
    {
        $rootPath = Mage::getModel('catalog/category')->load(Mage::app()->getStore()->getRootCategoryId())->getPath();
        $categories = Mage::getModel('catalog/category')->getCollection()
                        ->addAttributeToSelect('*')
                        ->addAttributeToFilter('is_active','1')
                        ->addAttributeToFilter('include_in_menu','1')
                        ->addFieldToFilter('path', array('like' => "$rootPath/%"))
                        ->addAttributeToSort('level', 'asc')
                        ->addAttributeToSort('position', 'asc')
                        ->addAttributeToFilter('magic_thumbnail', array('neq' => ''));
                        // ->addAttributeToFilter('magic_image', array('neq' => ''));
        return $categories;
    }

}
