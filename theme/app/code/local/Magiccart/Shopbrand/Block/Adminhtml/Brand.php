<?php
class Magiccart_Shopbrand_Block_Adminhtml_Brand extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'adminhtml_brand';
		$this->_blockGroup = 'shopbrand';
		$this->_headerText = Mage::helper('shopbrand')->__('Brand Manager');
		$this->addButtionLabel = Mage::helper('shopbrand')->__('Add Brand');
		parent::__construct();
	}
}