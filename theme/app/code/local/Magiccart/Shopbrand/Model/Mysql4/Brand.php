<?php
class Magiccart_Shopbrand_Model_Mysql4_Brand extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('shopbrand/brand', 'brand_id');
	}
}