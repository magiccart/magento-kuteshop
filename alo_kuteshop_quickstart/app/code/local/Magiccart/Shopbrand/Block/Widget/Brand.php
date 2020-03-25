<?php
/**
 * Magiccart 
 * @category  Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license   http://www.magiccart.net/license-agreement.html
 * @Author: Magiccart<team.magiccart@gmail.com>
 * @@Create Date: 2014-07-28 17:49:00
 * @@Modify Date: 2015-10-01 12:06:16
 * @@Function:
 */

class Magiccart_Shopbrand_Block_Widget_Brand extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{

	public $config = array();

    protected function _construct()
    {
        parent::_construct();

        $this->config = Mage::helper('shopbrand')->getGeneralCfg();
    }
  
	public function getBrand()
	{
        $store = Mage::app()->getStore()->getStoreId();
        $brands = Mage::getModel('shopbrand/brand')->getCollection()
                    ->addFieldToFilter('stores',array( array('finset' => 0), array('finset' => $store)))
                    ->addFieldToFilter('status', 1);
	    return $brands;
	}

	public function getImage($object)
	{
	    $image = Mage::helper('shopbrand/image');
	    $width = $this->config['widthImages'];
	    $height= $this->config['heightImages'];
	    $floder = $width.'x'.$height;
	    $image->setFolder($floder);
	    $image->setWidth($width);
	    $image->setHeight($height); 
	    $image->setQuality(100); // not require
	    return $image->init($object, 'image');
	}

    public function getDevices()
    { 
        return array('portrait'=>480, 'landscape'=>640, 'tablet'=>768, 'desktop'=>992);
    }

    public function getBrandSlider()
    {
        $options = array(
            'auto',
            'speed',
            'controls',
            'pager',
            'moveSlides',
            'slideWidth',
        );
        $script = 'infiniteLoop : false,';
        foreach ($options as $opt) {
            $cfg  =  $this->config["$opt"];
            $script    .= "$opt: $cfg, ";
        }
        if(isset($this->config['marginColumn'])) $script .= 'slideMargin: ' .$this->config['marginColumn']. ', ';
        if($this->config['vertical']) $script .= "mode: 'vertical',";
        if($this->config['visibleItems']) $script .= 'visibleItems: ' . $this->config['visibleItems'] . ', ';
        $maxSlides = $this->config['visibleItems'] ?  $this->config['visibleItems'] : 1;
        $enableResponsiveBreakpoints = true ;//$this->config['enableResponsiveBreakpoints'] ;
        if($enableResponsiveBreakpoints){
            $script .= 'responsiveBreakpoints: {';
            $responsiveBreakpoints = $this->getDevices();
            foreach ($responsiveBreakpoints as $opt => $screen) {
                $cfg = $this->config[$opt];
                if($cfg) $script .= "$screen : $cfg ,";
                if($cfg > $maxSlides) $maxSlides = $cfg;
            }
            $script .= "},";
        }
        $script .= 'maxSlides: ' . $maxSlides . ', ';

        return $script;

    }

}

