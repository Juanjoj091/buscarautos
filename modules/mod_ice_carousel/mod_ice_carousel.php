<?php
/**
 * IceCarousel Extension for Joomla 1.6 By IceTheme
 * 
 * 
 * @copyright	Copyright (C) 2008 - 2011 IceTheme.com. All rights reserved.
 * @license		GNU General Public License version 2
 * 
 * @Website 	http://www.icetheme.com/Joomla-Extensions/icecarousel.html
 * @Support 	http://www.icetheme.com/Forums/IceCarousel/
 *
 */

/* no direct access*/
defined('_JEXEC') or die;

if( !defined('PhpThumbFactoryLoaded') ) {
  require_once dirname(__FILE__).DS.'libs'.DS.'phpthumb'.DS.'ThumbLib.inc.php';
  define('PhpThumbFactoryLoaded',1);
}

// Include the syndicate functions only once
require_once dirname(__FILE__).DS.'helper.php';
if( !modIceCarousel::checkActualCat( $module->id, $params) ){
	$module->showtitle=0;
	//echo JText::_("THIS_PAGE_IS_NOT_PRODUCT_DETAIL");
}
else{
$list = modIceCarousel::getList( $params );
$jshopConfig = null;
if(class_exists( "JSFactory")){
	$jshopConfig = &JSFactory::getConfig();
	JSFactory::loadCssFiles();
}

$group 			= $params->get( 'data_source','content' );
$tmp 		 	= $params->get( 'module_height', 'auto' );
$moduleHeight   =  ( $tmp=='auto' ) ? 'auto' : (int)$tmp.'px';
$tmp 			= $params->get( 'module_width', 'auto' );
$moduleWidth    =  ( $tmp=='auto') ? 'auto': (int)$tmp.'px';
$themeClass 	= $params->get( 'theme' , '');
$openTarget 	= $params->get( 'open_target', 'parent' );
$class 			= !$params->get( 'navigator_pos', 0 ) ? '':'ice-'.$params->get( 'navigator_pos', 0 );
$theme		    =  $params->get( 'theme', '' );
$target = $params->get('open_target','_parent') != 'modalbox'
							? 'target="'.$params->get('open_target','_parent').'"'
							: 'rel="'.$params->get('modal_rel','width:800,height:350').'" class="mb"'; 
//Allow multiplie Id's
if (!isset($GLOBALS['add_icecarousel_toggler'])) { $GLOBALS['add_icecarousel_toggler'] = 1; } else { $GLOBALS['add_icecarousel_toggler']++; }
if (!isset($GLOBALS['add_icecarousel_content'])) { $GLOBALS['add_icecarousel_content'] = 1; } else { $GLOBALS['add_icecarousel_content']++; }


// Variables
$mod_url  		 								= JURI::base() . 'modules/mod_ice_accordion/';
$style           								= $params->get('style', 'default');

$isThumb       = $params->get( 'auto_renderthumb',1);
$itemContent= $isThumb==1?'desc-image':'introtext';
$icecarousel_toggler   						= 'icecarousel_toggler_' . $GLOBALS['add_icecarousel_toggler'];
$icecarousel_content   						= 'icecarousel_content_' . $GLOBALS['add_icecarousel_content'];
$icecarousel_activecolor   					= $params->get('icecarousel_activecolor', '222');
$icecarousel_inactivecolor   					= $params->get('icecarousel_inactivecolor', '888');
/*Paging*/

$maxPages = (int)$params->get( 'max_items_per_page', 3 );
$pages = array_chunk( $list, $maxPages  );
$totalPages = count($pages);
// calculate width of each row.
$itemWidth = 100/$maxPages -0.1;
$isAjax = $params->get('enable_ajax', 0 );
$item_heading = $params->get('item_heading');
$tmp = $params->get( 'module_height', 'auto' );
$moduleHeight   =  ( $tmp=='auto' ) ? 'auto' : (int)$tmp.'px';
$tmp = $params->get( 'module_width', 'auto' );
$moduleWidth    =  ( $tmp=='auto') ? 'auto': (int)$tmp.'px';
/*Paging*/
$module_id = JRequest::getVar("moduleId",0);
$layout = JRequest::getVar("layout","");
$page = JRequest::getVar("p", 1);
$tmp_number_page = $maxPages;
$limitstart	= 0;

/*Joom shopping config*/
$show_preview = $params->get("show_preview",1);
$show_image_label = $params->get("show_image_label",1);
$show_rating = $params->get("show_rating",1);
$show_product_image = $params->get("show_product_image",1);
$show_description = $params->get("show_description",1);
$show_old_price = $params->get("show_old_price",1);
$show_price = $params->get("show_price",1);
$item_layout = "_items";
if($group == "joomshopping"){
	$item_layout = "_products";
}
elseif($group == "ip"){
	if( file_exists(JPATH_SITE.DS.'components'.DS.'com_iproperty'.DS.'iproperty.php'))
	{
		$settings           = ipropertyAdmin::config();
		$item_layout = "_properties";
	}
	else{
		$totalPages = 0;
	}
}
/*End Paging*/
if(modIceCarousel::checkIceAjax() ){
	$lang_tag = JRequest::getVar("lang","en-GB");
	$lang =& JFactory::getLanguage();
	$lang->load( "mod_ice_carousel",JPATH_SITE, $lang_tag, true );
}
$itemLayoutPath = modIceCarousel::getLayoutByTheme($module, $theme, $item_layout);
if( $totalPages > 0){

	if( $module_id == $module->id && $layout == $item_layout ){
		require_once( $itemLayoutPath );
	}
	else{
		// load custom theme'
		$lang =& JFactory::getLanguage();
		$lang_tag =  $lang->getTag();
		if( $theme && $theme != -1 ) {
			require( modIceCarousel::getLayoutByTheme($module, $theme) );
		} else {
			require( JModuleHelper::getLayoutPath($module->module) );	
		}
		modIceCarousel::loadMediaFiles( $params, $module, $theme );
		?>
		 <script type="text/javascript">
			var _icemain =  $('icecarousel<?php echo $module->id;?>'); 
			var object = new IceCarousel ( _icemain,
											  { 
												  fxObject:{
													transition:<?php echo $params->get("transition","Fx.Transitions.Back.easeInOut"); ?>,  
													duration:<?php echo $params->get("duration", 1000); ?>								 },
												  startItem:0,
												  interval:<?php echo $params->get("interval", 5000); ?>,
												  direction :'hrleft', 
												  navItemHeight:<?php echo $params->get('navitem_height', 32) ?>,
												  navItemWidth:<?php echo $params->get('navitem_width', 32) ?>,
												  navItemsDisplay:3,
												  navPos:'<?php echo $params->get( 'navigator_pos', 0 ); ?>',
												  nextButton: '.ice-next',
												  prevButton:  '.ice-previous'
												  <?php 
													if($isAjax ){
														echo ',isAjax:true';
														echo ',url:"'.JURI::base()."modules/mod_ice_carousel/ajax.php?moduleId=".$module->id."&lang=".$lang_tag."&layout=".$item_layout.'&type=ice_carousel"';
														echo ',maxItemSelector:'.$totalPages;
													}
												  ?>
											  } );
					object.registerButtonsControl( 'click', {next:_icemain.getElement('.ice-next'),
															 previous:_icemain.getElement('.ice-previous')} );
					object.start( <?php echo $params->get('auto_start',1)?>, null );
			</script>
		<?php
	}
	if( modIceCarousel::checkIceAjax() ){
		exit();
	}
}
}
?>
