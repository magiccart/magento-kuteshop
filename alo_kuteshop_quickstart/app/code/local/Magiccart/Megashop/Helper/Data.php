<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2014-03-14 20:26:27
 * @@Modify Date: 2015-07-09 17:01:22
 * @@Function:
 */
?>
<?php
class Magiccart_Megashop_Helper_Data extends Mage_Core_Helper_Abstract
{

    const SECTIONS      = 'megashop';   // module name
    const GROUPS        = 'general';        // setup general
    const GROUPS_PLUS   = 'product';        // custom group
    const FEATURED      = 'featured';       // attribute featured
    
    public function getGeneralCfg($cfg=null) 
    {
        $config = Mage::getStoreConfig(self::SECTIONS.'/'.self::GROUPS);
        if(isset($config[$cfg])) return $config[$cfg];
        return $config;
    }

    public function getProductCfg($cfg=null)
    {
        $config =  Mage::getStoreConfig(self::SECTIONS .'/'.self::GROUPS_PLUS);
        if(isset($config[$cfg])) return $config[$cfg];
        return $config;
    }

    public function getBaseTmpMediaUrl()
    {
        return Mage::getBaseUrl('media') . 'magiccart/megashop/';
    }
    
    public function getBaseTmpMediaPath()
    {
        return Mage::getBaseDir('media') .DS. 'magiccart' .DS. 'megashop' .DS;
    }

    public function resizeImg($fileName,$width='',$height=null)
    {
        $imageURL = $this->getBaseTmpMediaUrl() .$fileName;

        $imagePath = $this->getBaseTmpMediaPath() .str_replace('/', DS,$fileName);
        
        $extra =$width . 'x' . $height;
        $newPath = $this->getBaseTmpMediaPath() ."cache".DS.$extra.str_replace('/', DS,$fileName);
        //if width empty then return original size image's URL
        if ($width != '' && $height != '') {
            //if image has already resized then just return URL
            if (file_exists($imagePath) && is_file($imagePath) && !file_exists($newPath)) {
                $imageObj = new Varien_Image($imagePath);
                $imageObj->constrainOnly(TRUE);
                $imageObj->keepAspectRatio(FALSE);
                $imageObj->keepTransparency(true);
                $imageObj->keepFrame(FALSE);
                $imageObj->quality(100);
                //$width, $height - sizes you need (Note: when keepAspectRatio(TRUE), height would be ignored)
                $imageObj->resize($width, $height);
                $imageObj->save($newPath);
            }
            $resizedURL = $this->getBaseTmpMediaUrl() ."cache".'/'.$extra.'/'.$fileName;
         } else {
            $resizedURL = $imageURL;
         }
         return $resizedURL;
    }

}

