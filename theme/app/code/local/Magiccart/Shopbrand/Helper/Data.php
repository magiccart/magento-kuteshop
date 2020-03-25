<?php
class Magiccart_Shopbrand_Helper_Data extends Mage_Core_Helper_Abstract
{

    const SECTIONS      = 'shopbrand';   // module name
    const GROUPS        = 'general';        // setup general
    const GROUPS_PLUS   = 'product';        // custom group

    public function getGeneralCfg($cfg=null) 
    {
        $config = Mage::getStoreConfig(self::SECTIONS.'/'.self::GROUPS);
        if(isset($config[$cfg])) return $config[$cfg];
        return $config;
    }

    public function getProductCfg($cfg=null) 
    {
        $config = Mage::getStoreConfig(self::SECTIONS.'/'.self::GROUPS_PLUS);
        if(isset($config[$cfg])) return $config[$cfg];
        return $config;
    }

    public function getBrandMediaUrl()
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
    }
    
    public function getBrandMediaPath()
    {
        return Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . '/';
    }
    
    public function recursiveReplace($search, $replace, $subject)
    {
        if (!is_array($subject)) {
            return $subject;
        }

        foreach ($subject as $key => $value) {
            if (is_string($value)) {
                $subject[$key] = str_replace($search, $replace, $value);
            } elseif (is_array($value)) {
                $subject[$key] = self::recursiveReplace($search, $replace, $value);
            }
        }

        return $subject;
    }

}

