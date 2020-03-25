<?php
/**
 * Magiccart 
 * @category  Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license   http://www.magiccart.net/license-agreement.html
 * @Author: Magiccart<team.magiccart@gmail.com>
 * @@Create Date: 2014-07-28 17:49:00
 * @@Modify Date: 2015-10-07 10:36:26
 * @@Function:
 */

class Magiccart_Shopbrand_Block_Widget_Shopbrand extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{

    protected function _construct()
    {
        $data = Mage::helper('shopbrand')->getProductCfg();
        $this->addData($data);
        $this->config = Mage::helper('shopbrand')->getGeneralCfg();
        parent::_construct();
    }

    public function getProductCfg()
    {

        $options = array('limit', 'productDelay', 'widthImages', 'heightImages', 'timer', 'action', 'rows');
        $ajax = array();
        foreach ($options as $option) {
            $ajax[$option] = $this->getData($option);
        }
        return $ajax;
    }

    public function getTabActive()
    {
        if($this->hasData('active')) return $this->getData('active');
        $active = $this->getBrands()->getFirstItem();
        $brandId = $active->getBrandId();
        $this->setData('active', $brandId);
        if($brandId) return $brandId;
    }

    public function getContentActive($template)
    {
        return $this->getLayout()
               ->createBlock('shopbrand/product_grid')
               ->setActive($this->getTabActive()) //or ->setData('active', $this->getTabActive())
               ->setCfg($this->getData())
               ->setTemplate($template)
               ->toHtml();
    }

    public function getBrands()
    {
        $brands = $this->hasData('brands') ? $this->getData('brands') : '';
        $store = Mage::app()->getStore()->getStoreId();
        if (!$brands) {
            $brands = Mage::getModel('shopbrand/brand')->getCollection()
                            ->addFieldToFilter('stores',array( array('finset' => 0), array('finset' => $store)))
                            ->addFieldToFilter('status', 1);
            $brands->getSelect()->order('order','ASC');   
            $this->setData('brands', $brands);
        }
        return $brands;
    }

    public function getDevices()
    {
        $devices = array('portrait'=>480, 'landscape'=>640, 'tablet'=>768, 'desktop'=>992);
        return $devices;
    }

    public function getItemsDevice()
    {
        $screens = $this->getDevices();
        $screens['visibleItems']  = 993;
        $itemOnDevice = array();
        // $itemOnDevice['320'] = '1';
        foreach ($screens as $screen => $size) {
            // $fn = 'get'.ucfirst($screen);
            // $itemOnDevice[$size] = $this->{$fn}();
            $itemOnDevice[$size] = $this->getData($screen);
        }
        return $itemOnDevice;
    }

    public function setFlexiselArray()
    {
        
        //var_dump($this->getData());die;
        if($this->getData('slide')){
            $optTrue = array(
                // 'infiniteLoop',
                'pager',
                'controls',
            );
            $options = array(
                'speed',
                'auto',
                'pause',
                'moveSlides',
                'visibleItems',
            );
            $script = array();
            $script['infiniteLoop'] = 0;
            $script['maxSlides'] = $script['visibleItems'] = $this->getData('visibleItems');
            $script['slideWidth'] = $this->getData('widthImages');
            if($this->getData('vertical')){
                $script['mode'] = 'vertical';
                $script['minSlides'] = $this->getData('visibleItems');
            }
            if($this->getData('marginColumn')) $script['slideMargin'] = (int) $this->getData('marginColumn');
            foreach ($optTrue as $opt) {
                $script[$opt] = $this->getData($opt) ? 1 : 0;
            }
            foreach ($options as $opt) {
                $cfg = $this->getData($opt);
                if($cfg) $script[$opt] = (int) $cfg;
            }
            $responsiveBreakpoints = $this->getDevices();
            // $script['responsiveBreakpoints']['mobile'] = array('changePoint'=> 320, 'visibleItems'=> 1);
            foreach ($responsiveBreakpoints as $opt => $screen) {
                $cfg = $this->getData($opt);
                if($cfg) $script['responsiveBreakpoints'][(int) $screen] = (int) $cfg;
                if($cfg > $script['maxSlides']) $script['maxSlides'] = $cfg;
            }
            return $script;
        }
    }

    public function generateRandomString($length = 10) {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
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

    public function getBrandSlider()
    {
        $options = array(
            'auto',
            'speed',
            'controls',
            'pager',
            // 'maxSlides',
            'slideWidth',
        );
        $script = 'moveSlides: 1,';
		$script .= 'infiniteLoop : false,';
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

