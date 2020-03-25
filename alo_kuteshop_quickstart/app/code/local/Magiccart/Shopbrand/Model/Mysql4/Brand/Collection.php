<?php
class Magiccart_Shopbrand_Model_Mysql4_Brand_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('shopbrand/brand');
	}

   public function addStoreFilter($store, $withAdmin = true){
    
    	if ($store instanceof Mage_Core_Model_Store) {
    		$store = array($store->getId());
    	}
    
    	if (!is_array($store)) {
    		$store = array($store);
    	}
    
    	$this->addFilter('stores', array('in' => $store));
    
    	return $this;
    }
}