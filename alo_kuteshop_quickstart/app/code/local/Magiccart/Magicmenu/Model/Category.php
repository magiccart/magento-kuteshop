<?php
/**
 * Magiccart 
 * @category 	Magiccart 
 * @copyright 	Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license 	http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2015-05-27 11:28:31
 * @@Modify Date: 2015-05-27 11:46:37
 * @@Function:
 */
class Magiccart_Magicmenu_Model_Category extends Mage_Catalog_Model_Category
{
	protected function _construct()
    {
        $this->_init('catalog/category');
    }
}
