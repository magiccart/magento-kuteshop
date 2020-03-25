<?php
// Product grid
class Magiccart_Shopbrand_Block_Adminhtml_Brand_Edit_Tab_Product extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('productGrid');
		$this->setUseAjax(true); // Using ajax grid is important
		$this->setDefaultSort('entity_id');
		$this->setDefaultFilter(array('in_products'=>1)); // By default we have added a filter for the rows, that in_products value to be 1
		$this->setSaveParametersInSession(false);  //Dont save paramters in session or else it creates problems
	}

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('*');
            //->addAttributeToFilter('type_id',array("virtual","downloadable","simple"));
        if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
        	$collection->joinField('qty',
        			'cataloginventory/stock_item',
        			'qty',
        			'product_id=entity_id',
        			'{{table}}.stock_id=1',
        			'left');
        }
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

	protected function _addColumnFilterToCollection($column)
	{
		// Set product filter for in product flag
		if ($column->getId() == 'in_products') {
			$ids = $this->_getSelectedProducts();
			if (empty($ids)) {
				$ids = 0;
			}
			if ($column->getFilter()->getValue()) {
				$this->getCollection()->addFieldToFilter('entity_id', array('in'=>$ids));
			} else {
				if($ids) {
					$this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$ids));
				}
			}
		} else {
			parent::_addColumnFilterToCollection($column);
		}
		return $this;
	}

	protected function _prepareColumns()
	{
		$this->addColumn('in_products', array(
                'header_css_class'  => 'a-center',
                'type'              => 'checkbox',
                'name'              => 'product',
                'values'            => $this->_getSelectedProducts(),
                'align'             => 'center',
                'index'             => 'entity_id'
        ));

		$this->addColumn('entity_id', array(
				'header'	=> Mage::helper('catalog')->__('ID'),
				'width'		=> '50px',
				'type'		=> '', //'type'		=> 'number',
				'index'		=> 'entity_id',
		));

		$this->addColumn('name', array(
				'header'	=> Mage::helper('catalog')->__('Name'),
				'index'		=> 'name',
		));

		$this->addColumn('type', array(
				'header'	=> Mage::helper('catalog')->__('Type'),
				'width'		=> '120px',
				'index'		=> 'type_id',
				'type'		=> 'options',
				'options'	=> Mage::getSingleton('catalog/product_type')->getOptionArray(),
			));

		$sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
			->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
			->load()
			->toOptionHash();

		$this->addColumn('set_name', array(
				'header'	=> Mage::helper('catalog')->__('Attrib. Set Name'),
				'width'		=> '100px',
				'index'		=> 'attribute_set_id',
				'type'		=> 'options',
				'options'	=> $sets,
		));

		$this->addColumn('sku', array(
				'header'	=> Mage::helper('catalog')->__('SKU'),
				'width'		=> '80px',
				'index'		=> 'sku',
		));

		if(Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
			$this->addColumn('qty', array(
					'header'	=> Mage::helper('catalog')->__('Qty'),
					'width'		=> '80px',
					'type'		=> 'number',
					'index'		=> 'qty',
			));
		}

		$this->addColumn('visibility', array(
				'header'	=> Mage::helper('catalog')->__('Visibility'),
				'width'		=> '100px',
				'index'		=> 'visibility',
				'type'		=> 'options',
				'options'	=> Mage::getModel('catalog/product_visibility')->getOptionArray(),
		));

		// $this->addColumn('status', array(
		// 		'header'	=> Mage::helper('catalog')->__('Status'),
		// 		'width'		=> '70px',
		// 		'index'		=> 'status',
		// 		'type'		=> 'options',
		// 		'options'	=> Mage::getSingleton('catalog/product_status')->getOptionArray(),
		// ));

		// if(!Mage::app()->isSingleStoreMode()) {
		// 	$this->addColumn('websites', array(
		// 			'header'	=> Mage::helper('catalog')->__('Websites'),
		// 			'width'		=> '100px',
		// 			'sortable'	=> false,
		// 			'index'		=> 'websites',
		// 			'type'		=> 'options',
		// 			'options'	=> Mage::getModel('core/website')->getCollection()->toOptionHash(),
		// 	));
		// }

        $this->addColumn('position', array(
            'header'            => Mage::helper('catalog')->__('Position'),
            'name'              => 'position',
            'width'             => 60,
            'type'              => 'number',
            'validate_class'    => 'validate-number',
            'index'             => 'position',
            'editable'          => true,
            'edit_only'         => true
        ));

		return parent::_prepareColumns();
	}

	protected function _getSelectedProducts()   // Used in grid to return selected products values.
	{
		$products = array_keys($this->getSelectedProducts());
		return $products;
	}

	public function getGridUrl()
	{
		return $this->_getData('grid_url') ? $this->_getData('grid_url') : $this->getUrl('*/*/productgrid', array('_current'=>true));
	}

	public function getSelectedProducts()
	{
		// Product Data
		$tm_id = $this->getRequest()->getParam('id');
		if(!isset($tm_id)) $tm_id = 0;
		$model  = Mage::getModel('shopbrand/brand')->load($tm_id);
		$productIds = explode(",", $model->getProductIds());
		$productIds = array_flip($productIds);
		return $productIds;
	}


}

