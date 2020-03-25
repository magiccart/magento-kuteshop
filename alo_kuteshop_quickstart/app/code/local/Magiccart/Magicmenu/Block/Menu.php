<?php
/**
 * Magiccart 
 * @category  Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license   http://www.magiccart.net/license-agreement.html
 * @Author: Magiccart<team.magiccart@gmail.com>
 * @@Create Date: 2014-05-28 10:10:00
 * @@Modify Date: 2016-03-02 14:01:06
 * @@Function:
 */

class Magiccart_Magicmenu_Block_Menu extends Mage_Catalog_Block_Navigation
{

    protected $cfgExt  = array();
    protected function _construct()
    {
        parent::_construct();
        $this->cfgExt = (object)Mage::helper('magicmenu')->getConfig();
    }

    public function getIsHomePage()
    {
        return $this->getUrl('') == $this->getUrl('*/*/*', array('_current'=>true, '_use_rewrite'=>true));
    }

    public function isCategoryActive($catId)
    {
        return $this->getCurrentCategory() ? in_array($catId, $this->getCurrentCategory()->getPathIds()) : false;
    }

    public function getLogo()
    {
        $src = Mage::getStoreConfig('design/header/logo_src');
        $logo = '<li class="level0 logo display"><a class="level-top" href="'.Mage::helper('core/url')->getHomeUrl().'"><img alt="logo" src="' .$this->getSkinUrl($src). '"></a></li>';
        return $logo;
    }

    public function drawHomeMenu()
    {
        $drawHomeMenu = '';
        $active = ($this->getIsHomePage()) ? ' active' : '';
        $drawHomeMenu .= '<li class="level0 home' . $active . '">';
        $drawHomeMenu .= '<a class="level-top" href="'.Mage::helper('core/url')->getHomeUrl().'"><span class="icon-home fa fa-home"></span><span class="icon-text">' .$this->__('Home') .'</span>';
        $drawHomeMenu .= '</a>';
        if($this->cfgExt->topmenu['demo']){
            $demo = '';
            foreach (Mage::app()->getWebsites() as $website) {
                $groups = $website->getGroups();
                if(count($groups) > 1){
                    foreach ($groups as $group) {
                        $store = $group->getDefaultStore();
                        if (!$store->getIsActive()) {
                            $stores = $group->getStores();
                            foreach ($stores as $store) {
                                if ($store->getIsActive()) break;
                                else $store = '';
                            }
                        }                            
                        if($store) $demo .= '<div><a href="'.$store->getUrl(). '"><span class="demo-home">'. $group->getName(). '</span></a></div>';
                    }
                }
            }
            if($demo) $drawHomeMenu .= '<div class="level-top-mega">' .$demo .'</div>';           
        }

        $drawHomeMenu .= '</li>';
        return $drawHomeMenu;
    }

    public function drawMainMenu()
    {
        if($this->hasData('drawMainMenu')) return $this->getData('drawMainMenu');
        // Mage::log('your debug', null, 'yourlog.log');
        $desktopHtml = array();
		$mobileHtml  = array();
        $catListTop = $this->getCatTop();
        $contentCatTop  = $this->getContentCatTop();
        $extData    = array();
        foreach ($contentCatTop as $ext) {
            $extData[$ext->getCatId()] = $ext->getData();
        }
        $i = 1; $last = count($catListTop);
        $dropdownIds = explode(',', $this->cfgExt->general['dropdown']);
        foreach ($catListTop as $catTop) :
			$idTop    = $catTop->getEntityId();
            $hasChild = $catTop->hasChildren() ? ' hasChild' : '';
            $isDropdown = in_array($idTop, $dropdownIds) ? ' dropdown' : '';
            $active   = $this->isCategoryActive($idTop) ? ' active' : '';
            $urlTop      =  '<a class="level-top" href="' .$catTop->getUrl(). '">' .$this->getThumbnail($catTop). '<span>' .$this->__($catTop->getName()) . $this->getCatLabel($catTop). '</span><span class="boder-menu"></span></a>';
            $classTop    = ($i == 1) ? 'first' : ($i == $last ? 'last' : '');
            $classTop   .= $active . $hasChild .$isDropdown;

            // drawMainMenu
            if($isDropdown){ // Draw Dropdown Menu
				// $childHtml = $this->getTreeCategories($idTop);
				$childHtml = $this->getTreeCategoriesExt($idTop); // include magic_label
                $desktopHtml[$idTop] = '<li class="level0 cat ' . $classTop . '">' . $urlTop . $childHtml . '</li>';
                $mobileHtml[$idTop]  = '<li class="level0">' . $urlTop . $childHtml . '</li>';
                $i++;
                continue;
            }
			// Draw Mega Menu 
            $data =''; $options='';
            if(isset($extData[$idTop])) $data   = $extData[$idTop];
            $blocks = array('top'=>'', 'left'=>'', 'right'=>'', 'bottom'=>'');
            if($data){
                foreach ($blocks as $key => $value) {
                    $proportions = $key .'_proportions';
                    $weight = (isset($data[$proportions])) ? $data[$proportions]:'';
                    $html = $this->getStaticBlock($data[$key]);
                    if($html) $blocks[$key] = "<div class='mage-column mega-block-$key'>".$html.'</div>';
                }
                $remove = array('top'=>'', 'left'=>'', 'right'=>'', 'bottom'=>'', 'short_desc'=>'', 'cat_id'=>'');
                foreach ($remove as $key => $value) {
                    unset($data[$key]);
                }
                $opt     = json_encode($data);
                $options = $opt ? " data-options='$opt'" : '';
            }

			$desktopTmp = $mobileTmp  = '';
			if($hasChild || $blocks['top'] || $blocks['left'] || $blocks['right'] || $blocks['bottom']) :
				$desktopTmp .= '<div class="level-top-mega">';  /* Wrap Mega */
					$desktopTmp .='<div class="content-mega">';  /*  Content Mega */
						$desktopTmp .= $blocks['top'];
						$desktopTmp .= '<div class="content-mega-horizontal">';
							$desktopTmp .= $blocks['left'];
							if($hasChild) :
								$desktopTmp .= '<ul class="level0 mage-column cat-mega">';
								$mobileTmp .= '<ul>';
								$childTop  = $this->getTopChild($idTop);
								foreach ($childTop as $child) {
									$id = $child->getId();
									$class = ' level1';
									$class .= $this->isCategoryActive($child->getId()) ? ' active' : '';
									$url =  '<a href="'. $child->getUrl().'"><span>'.$this->__($child->getName()) . $this->getCatLabel($child) . '</span></a>';
									// $childHtml = $this->getTreeCategories($id); // not include magic_label
									$childHtml = $this->getTreeCategoriesExt($id); // include magic_label
									// $childHtml = $this->getTreeCategoriesExtra($id); // include magic_label
									// $childHtml = $this->getCatByPath($id, $child->getPath()); // include magic_label
									$desktopTmp .= '<li class="children' . $class . '">' . $this->getImage($child) . $url . $childHtml . '</li>';
									$mobileTmp  .= '<li>' . $url . $childHtml . '</li>';
								}
								$desktopTmp .= '<li>'  .$blocks['bottom']. '</li>';
								$desktopTmp .= '</ul>'; // end cat-mega
								$mobileTmp .= '</ul>';
							endif;
							$desktopTmp .= $blocks['right'];
						$desktopTmp .= '</div>';
						//$desktopTmp .= $blocks['bottom'];
					$desktopTmp .= '</div>';  /* End Content mega */
				$desktopTmp .= '</div>';  /* Warp Mega */
			endif;
            $desktopHtml[$idTop] = '<li class="level0 cat ' . $classTop . '"' . $options .'>' .$urlTop . $desktopTmp . '</li>';
            $mobileHtml[$idTop]  = '<li class="level0">' . $urlTop . $mobileTmp . '</li>';
            $i++;
        endforeach;
        $menu['desktop'] = $desktopHtml;
        $menu['mobile'] = implode("\n", $mobileHtml);
        $this->setData('drawMainMenu', $menu);
        return $menu;
    }

    public function drawExtraMenu()
    {
        $extMenu    = $this->getExtraMenu();
        $count = count($extMenu);
        $drawExtraMenu = '';
        if($count){
            $i = 1; $class = 'first';
            $currentUrl = $this->helper('core/url')->getCurrentUrl();
            foreach ($extMenu as $ext) { 
                $link = $ext->getLink();
                $url = (filter_var($link, FILTER_VALIDATE_URL)) ? $link : $this->getUrl($link);
                $active = ( $link && $url == $currentUrl) ? ' active' : '';
                $html = $this->getStaticBlock($ext->getExtContent());
                if($html) $active .=' hasChild';
                $drawExtraMenu .= "<li class='level0 ext $active $class'>";
                    if($link) $drawExtraMenu .= '<a class="level-top" href="' .$url. '"><span>' .$this->__($ext->getName()) . $this->getCatLabel($ext). '</span></a>';
                    else $drawExtraMenu .= '<span class="level-top"><span>' .$this->__($ext->getName()) . $this->getCatLabel($ext). '</span></span>';
                    if($html) $drawExtraMenu .= '<div class="level-top-mega">'.$html.'</div>';
                $drawExtraMenu .= '</li>';
                $i++;
                $class = ($i == $count) ? 'last' : '';  
            }
        }
        return $drawExtraMenu;
    }

    public function getCatTop()
    {
        $collection = Mage::getModel('magicmenu/category')->getCollection()
                        ->addAttributeToSelect(array('entity_id','name','magic_label','short_desc','url_path','magic_thumbnail'))
                        ->addAttributeToFilter('parent_id', Mage::app()->getStore()->getRootCategoryId())
                        ->addAttributeToFilter('include_in_menu', 1)
                        ->addIsActiveFilter()
                        ->addLevelFilter(2)
                        ->addAttributeToSort('position', 'asc'); //->addOrderField('name');
        return $collection;
    }

    public function getTopChild($parentId)
    {
        $collection = Mage::getModel('magicmenu/category')->getCollection()
                        ->addAttributeToSelect(array('entity_id','name','magic_label','short_desc','url_path','magic_image'))
                        ->addAttributeToFilter('parent_id', $parentId)
                        ->addAttributeToFilter('include_in_menu', 1)
                        ->addAttributeToFilter('level',3)
                        ->addIsActiveFilter()
                        ->addAttributeToSort('position', 'asc'); //->addOrderField('name');
        return $collection;
    }

    public function getExtraMenu()
    {
        $store = Mage::app()->getStore()->getStoreId();
        $collection = Mage::getModel('magicmenu/menu')->getCollection()
                        ->addFieldToSelect(array('link','name','magic_label','short_desc','ext_content','order'))
                        // ->addFieldToFilter('stores',array(array('like' => '0%'),array('like' => "%$store%")))
                        ->addFieldToFilter('extra', 1)
                        ->addFieldToFilter('status', 1);
        $collection->getSelect()->where('find_in_set(0, stores) OR find_in_set(?, stores)', $store)->order('order');
        return $collection;        
    }

    public function getStaticBlock($id)
    {
        $block = Mage::getModel('cms/block')->load($id);
        return $this->getLayout()->createBlock('cms/block')->setBlockId($block->getIdentifier())->toHtml();
    }

    public function getContentCatTop()
    {
        $store = Mage::app()->getStore()->getStoreId();
        $collection = Mage::getModel('magicmenu/menu')->getCollection()
                        ->addFieldToSelect(array(
                                'cat_id','cat_columns','cat_proportions','short_desc','top',
                                'right','right_proportions','bottom','left','left_proportions'
                            ))
                        ->addFieldToFilter('stores',array( array('finset' => 0), array('finset' => $store)))
                        ->addFieldToFilter('status', 1);
        return $collection;
    }

    public function  getTreeCategories($parentId) 
    {
        $html= '';
        $storeUrl = $this->getUrl();
        $categories = Mage::getModel('catalog/category')->getCategories($parentId);
        foreach($categories as $category) {
            $level = $category->getLevel();
            $childClass = $category->hasChildren() ? ' hasChild' : '';
            $html .= '<li class="level' .($level -2) .$childClass. '"><a href="' . $storeUrl.$category->getRequestPath(). '"><span>' .$category->getName() . "</span></a>\n";
            if($childClass) $html .=  $this->getTreeCategories($category->getId());
            $html .= '</li>';
        }
        $html = '<ul class="level' .($level -3). '">' . $html . '</ul>';
        return  $html;
    }

    public function  getTreeCategoriesExt($parentId) // include Magic_Label
    { 
        $categories = Mage::getModel('magicmenu/category')->getCollection()
                        ->addAttributeToSelect(array('name','magic_label','url_path'))
                        ->addAttributeToFilter('include_in_menu', 1)
                        ->addAttributeToFilter('parent_id',array('eq' => $parentId))
						->addIsActiveFilter()
                        ->addAttributeToSort('position', 'asc'); 
        $html = '';
        foreach($categories as $category)
        {
            $level = $category->getLevel();
            $childHtml = $this->getTreeCategoriesExt($category->getId());
            $childClass = $childHtml ? ' hasChild' : '';
            $html .= '<li class="level' .($level -2) .$childClass. '"><a href="' . $category->getUrl(). '"><span>' .$category->getName() . $this->getCatLabel($category) . "</span></a>\n" . $childHtml . '</li>';
        }
        if($html) $html = '<ul class="level'.($level -3).'">' .$html. '</ul>';
        return $html;
    }

    public function  getTreeCategoriesExtra($parentId) // include Magic_Label
    {
        $html = '';
        $categories = Mage::getModel('catalog/category')->getCategories($parentId);
        foreach($categories as $category) {
            $cat = Mage::getModel('catalog/category')->load($category->getId());
            $count = $cat->getProductCount();
            $level = $cat->getLevel();
            $childClass = $category->hasChildren() ? ' hasChild' : '';
            $html .= '<li class="level' .($level -2) .$childClass. '"><a href="' . $cat->getUrl(). '"><span>' .$cat->getName() . "(".$count.")" . $this->getCatLabel($cat). "</span></a>\n";
            if($childClass) $html .=  $this->getTreeCategories($category->getId());
            $html .= '</li>';
        }
        $html = '<ul class="level' .($level -3). '">' . $html . '</ul>';
        return  $html;
    }

    public function getCatByPath($parentId, $path)
    {
        $catList = Mage::getModel('catalog/category')->getCollection()
                        ->addAttributeToSelect(array('name', 'magic_label', 'url_path'))
                        ->addAttributeToFilter('include_in_menu', 1)
                        ->addAttributeToFilter('entity_id', array('neq' => $parentId))
                        ->addFieldToFilter('path', array('like' => "$path/%"))
                        ->addAttributeToSort('level', 'asc')
                        ->addAttributeToSort('position', 'asc')
                        ->addFieldToFilter('is_active', array('eq'=>'1'))
                        //->getSelect()->limit(5)
                        //->load(5) // display SQL
                        ->load();
                        //->toArray();
        $html = '';
        if(count($catList)){
            $html .='<ul>';
            // $i = 0;
            foreach ($catList as $cat) {
              //if($i >= $this->getGeneralCfg('limit_items')) break;
                $active = $this->isCategoryActive($cat->getEntityId()) ? ' active' : '';
                $html .= '<li class="nav'.$active.'"><a href="'. $cat->getUrl(). '">'.$this->__($cat->getName()) . $this->getCatLabel($cat) .'</a></li>';
                // $i++;
            }
            $html .= '</ul>';                             
        }
        return $html;

    }

    public function getCatLabel($cat)
    {
        $html = '';
        $label = explode(',', $cat->getMagicLabel());
        foreach ($label as $lab) {
          if($lab) $html .= '<span class="cat_label '.$lab.'">'.$this->__(trim($lab)) .'</span>';
        }
        $short_desc = trim($cat->getShortDesc());
        if($short_desc) $html .= '<span class="short_desc">'.$this->__($short_desc) .'</span>';

        return $html;
    }

    public function getImage($object)
    {
        if($file = $object->getMagicImage()){
            $image  = Mage::helper('magicmenu/image');
            $width  = 170; //$this->config['widthThumb'];
            $height = 100; //$this->config['heightThumb'];
            $img    = $image->resizeImg('/'.$file, $width, $height);
            if($img) return '<a class="a-image" href="' .$object->getUrl(). '"><img class="img-responsive" alt="' .$object->getName(). '" src="'.$img.'"></a>';
        }
    }

    public function getThumbnail($object)
    {
        if($file = $object->getMagicThumbnail()){
            $image  = Mage::helper('magicmenu/image');
            $width  = 50; //$this->config['widthThumb'];
            $height = 50; //$this->config['heightThumb'];
            $img    = $image->resizeImg('/'.$file);
            if($img) return '<img class="img-responsive" alt="' .$object->getName(). '" src="'.$img.'">';
        }
    }

}
