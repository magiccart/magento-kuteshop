<?php
class Magiccart_Shopbrand_Block_Adminhtml_Brand_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('brandGrid');
		$this->setDefaultSort('brand_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection() // Convert string stores id
	{
		$collection = Mage::getModel('shopbrand/brand')->getCollection();
     	 foreach($collection as $link) {
        	if($link->getStores() && $link->getStores() != 0 ){
        		$link->setStores(explode(',',$link->getStores()));
        	}
        	else{
        		$link->setStores(array('0'));
        	}
        }
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

    protected function _filterStoreCondition($collection, $column){
    	if (!$value = $column->getFilter()->getValue()) {
    		return;
    	}
    	$this->getCollection()->addStoreFilter($value);
    }

	public function _prepareColumns()
	{
		$this->addColumn('brand_id', array(
			'header'	=> Mage::helper('shopbrand')->__('ID'),
			'align'		=> 'right',
			'width'		=> '50px',
			'index'		=>'brand_id',
		));

		$this->addColumn('title', array(
			'header'	=> Mage::helper('shopbrand')->__('Title'),
			'align'		=> 'left',
			'index'		=> 'title',
		));

		$this->addColumn('urlkey', array(
			'header'	=> Mage::helper('shopbrand')->__('URL Key'),
			'align'		=> 'left',
			'index'		=> 'urlkey',
		));

		$this->addColumn('image', array(
			'header'	=> Mage::helper('shopbrand')->__('Brand Image'),
			'align'		=> 'left',
			'type'		=> 'image',
			'renderer'	=> 'Magiccart_Shopbrand_Block_Adminhtml_Brand_Render_Image',
			'widht'		=> 64,
			'index'		=> 'image',
		));

       if (!Mage::app()->isSingleStoreMode()) {
    $this->addColumn('stores', array(
        'header'        => Mage::helper('shopbrand')->__('Store View'),
        'index'         => 'stores',
        'type'          => 'store',
        'store_all'     => true,
        'store_view'    => true,
        'sortable'      => true,
        'filter_condition_callback' => array($this,'_filterStoreCondition'),
    ));
}

		$this->addColumn('porder', array(
			'header'	=> Mage::helper('shopbrand')->__('Order'),
			'width'		=> '50px',
			'index'		=> 'order',
		));
		
		$this->addColumn('status', array(
			'header'	=> Mage::helper('shopbrand')->__('Status'),
			'align'		=> '80px',
			'index'		=> 'status',
			'type'		=> 'options',
			'options'	=> array(
				1	=> 'Enabled',
				2	=> 'Disable',
			),
		));

		$this->addColumn('action', array(
			'header'	=> Mage::helper('shopbrand')->__('Action'),
			'width'		=> '100px',
			'type'		=> 'action',
			'getter'	=> 'getId',
			'actions'	=> array(
								array(
									'caption'	=> Mage::helper('shopbrand')->__('Edit'),
									'url'		=> array('base'	=> '*/*/edit'),
									'field'		=> 'id'
									)
								),
			'filter'	=> false,
			'sortable'	=> false,
			'index'		=> 'stores',
			'is_system'	=> true,

		));

		$this->addExportType('*/*/exportCsv', Mage::helper('shopbrand')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('shopbrand')->__('XML'));

		return parent::_prepareColumns();
	}

	public function _prepareMassaction()
	{
		$this->setMassactionIdField('brand_id');
		$this->getMassactionBlock()->setFormFieldName('shopbrand');

		$this->getMassactionBlock()->addItem('delete', array(
			'label'		=> Mage::helper('shopbrand')->__('Delete'),
			'url'		=> $this->getUrl('*/*/massDelete'),
			'confirm'	=> Mage::helper('shopbrand')->__('Are you sure?')
		));
		
		$statuses = Mage::getSingleton('shopbrand/status')->getOptionArray(); // option Action for change status
		array_unshift($statuses, array('label' => '', 'value' => ''));
		$this->getMassactionBlock()->addItem('status', array(
			'label'		=> Mage::helper('shopbrand')->__('Change status'),
			'url' 		=> $this->getUrl('*/*/massStatus', array('_current' => true)),
			'additional'=> array(
				'visibility' => array(
					'name'	=> 'status',
					'type'	=> 'select',
					'class'	=> 'required-entry',
					'label' => Mage::helper('shopbrand')->__('Status'),
					'values'=> $statuses
				)
			)
		));

		return $this;
	}

	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
}

