<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

//no direct access
defined('_JEXEC') or die;

$moduleid = $module->id;
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_googleads/css/mod_expautospro_googleads.css');
$document->addScript('http://maps.google.com/maps/api/js?sensor=false');
if ($params->get('jquery',0) == 1) {
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/jquery.min.js');
} elseif ($params->get('jquery',0) == 2) {
    $document->addScript(JURI::root() . 'modules/mod_expautospro_googleads/js/jquery.min.js');
}

if ($params->get('jq_chained',0)) {
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expsearchtoggle.js');
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/jqexpsearch.js');
}else{
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxrequest.js');
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxchained.js');
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expsearchchained.js');
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expsearchtoggle.js');
}
if ($params->get('jq_chosen',0) && $params->get('jq_chained',0)) {
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/chosen.jquery.min.js');
    $document->addStyleSheet(JURI::root() . 'components/com_expautospro/assets/css/chosen.css');
}
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expdownloadxml.js');
$document->addScript(JURI::root() . 'modules/mod_expautospro_googleads/js/markerclusterer_compiled.js');
$script = '';
$script .= "
    var link = '';
    var expmarkers = [];
    var customIcons = {
      1: {
        icon: '".JURI::root()."modules/mod_expautospro_googleads/images/cars.png',
        shadow: '".JURI::root()."modules/mod_expautospro_googleads/images/shadow.png'
      },
      2: {
        icon: '".JURI::root()."modules/mod_expautospro_googleads/images/new_cars.png',
        shadow: '".JURI::root()."modules/mod_expautospro_googleads/images/shadow.png'
      }
    };
    function expchg1(){
    clearOverlays();
    var explink ='';
    explink += '&expgcatid='+document.getElementById('catid".$moduleid."').value;
    if(".$params->get('usemakes')."){
    explink += '&expgmakeid='+document.getElementById('makeid".$moduleid."').value;
    }
    if(".$params->get('usemodels')."){
    explink += '&expgmodelid='+document.getElementById('modelid".$moduleid."').value;
    }
    if(".$params->get('usesmodel')."){
    explink += '&expgsmodelid='+document.getElementById('modexpsearch_specificmodel".$moduleid."').value;
    }
    if(".$params->get('usebodytype')."){
    explink += '&expgbodytype='+document.getElementById('bodytype".$moduleid."').value;
    }
    if(".$params->get('useyear')."){
    explink += '&expgyearfrom='+document.getElementById('yearfrom".$moduleid."').value;
    explink += '&expgyearto='+document.getElementById('yearto".$moduleid."').value;
    }
    if(".$params->get('useprice')."){
    explink += '&expgpricefrom='+document.getElementById('modexpsearch_pricefrom".$moduleid."').value;
    explink += '&expgpriceto='+document.getElementById('modexpsearch_priceto".$moduleid."').value;
    }
    if(".$params->get('usemileage')."){
    explink += '&expgmileage='+document.getElementById('modexpsearch_mileage".$moduleid."').value;
    }
    if(".$params->get('usefuel')."){
    explink +'&expgfuel='+document.getElementById('fuel".$moduleid."').value;
    }
    if(".$params->get('usedrive')."){
    explink += '&expgdrive='+document.getElementById('drive".$moduleid."').value;
    }
    if(".$params->get('usetrans')."){
    explink += '&expgtrans='+document.getElementById('trans".$moduleid."').value;
    }
     link = explink;
     initializeads1();
    }

    function initializeads1() {
      var map = new google.maps.Map(document.getElementById('map".$moduleid."'), {
        center: new google.maps.LatLng(".$params->get('latitude').", ".$params->get('longitude')."),
        zoom: ".$params->get('zoom').",
        mapTypeId: 'roadmap'
      });
      var infoWindow = new google.maps.InfoWindow;

      // Change this depending on the name of your PHP file
      downloadUrl('index.php?option=com_expautospro&view=expcompare&format=ajax'+link, function(data) {
        var poiJson = eval(data.responseText);
        var countads = poiJson.length;
        var testtt = document.getElementById('expbutton".$moduleid."').value = '';
         document.getElementById('expbutton".$moduleid."').value = '".JText::_('MOD_EXPAUTOSPRO_GOOGLEADS_SEARCH_TEXT')."' + ' ('+ countads + ') ';
    for (var i = 0;i < countads; i += 1) {
        var linkval = '".JURI::root() ."index.php?option=com_expautospro&amp;view=expdetail&amp;id='+poiJson[i].id+'&amp;catid='+poiJson[i].catid+'&amp;makeid='+poiJson[i].make+'&amp;modelid='+poiJson[i].model+'&amp;Itemid=".$itemid."';
        var explink = '".JRoute::_("'+linkval+'")."';
        var expprice = '".ExpAutosProExpparams::price_formatdata("'+poiJson[i].price+'",2,1,1)."';
        if (poiJson[i].img_name) {
            var img_file = '<a href='+explink+'><span></span><img src=" . ExpAutosProExpparams::ImgUrlPatchThumbs() ."'+poiJson[i].img_name+' alt=\'\' /></a>';
        } else {
            var img_file = '<a href='+explink+'><span></span><img src=" . ExpAutosProExpparams::ImgUrlPatch() . "assets/images/no_photo.jpg alt=\'\' /></a>';
        }
        var lat = poiJson[i].latitude;
        var lon = poiJson[i].longitude;
          var type = poiJson[i].catid;
          var point = new google.maps.LatLng(
              parseFloat(poiJson[i].latitude),
              parseFloat(poiJson[i].longitude));
          var html = '';
          html += '<div class=\"expgoogleads\">'+
          '<div class=\"expautos_images_clear\"></div>'+
          '<div class=\"expgoogleads_img\">'+ img_file +'</div>'+
          '<div class=\"expgoogleads_right\">'+
          '<a href=\"'+explink+'\">'+
          '<p class=\"expgoogleads_top\">'+ 
          poiJson[i].make_name  + ' ' 
          + poiJson[i].model_name;
          if(poiJson[i].specificmodel){
          html += ' ' + poiJson[i].specificmodel;
          }
          html += ' - ' + expprice +
          '</p>'+
          '</a>'+
          '<div class=\"expautos_images_clear\"></div>'+
          '<p class=\"expgoogleads_middle\">'+
          poiJson[i].category_name ;
          if(poiJson[i].year > 0){
          html += ' ' + poiJson[i].year; 
          }
          if(poiJson[i].month > 0){
          html += '/' + poiJson[i].month;
          } 
          html += '</p>'+
          '<div class=\"expautos_images_clear\"></div>'+
          '<p class=\"expgoogleads_middle\">';
          if(poiJson[i].bodytype_name){
          html += poiJson[i].bodytype_name + ' ';
          }
          if(poiJson[i].drive_name){
          html += poiJson[i].drive_name + ' ';
          }
          if(poiJson[i].trans_name){
          html += poiJson[i].trans_name + ' ';
          }
          if(poiJson[i].fuel_name){
          html += poiJson[i].fuel_name + ' ';
          }
          if(poiJson[i].extcolor_name){
          html += poiJson[i].extcolor_name + ' '
          }
          '</p>'+
          '</div>'+
          '<div class=\"expautos_images_clear\"></div>'+
          '</div>';
          var icon = customIcons[poiJson[i].catid] || {};
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: icon.icon,
            shadow: icon.shadow
          });
          expmarkers.push(marker);
          bindInfoWindow(marker, map, infoWindow, html);
    };
          var markerCluster = new MarkerClusterer(map, expmarkers);

      });
    }

    function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }
    // Removes the overlays from the map, but keeps them in the array
  function clearOverlays() {
    for(var i=0; i<expmarkers.length; i++){
        expmarkers[i].setMap(null);
      }
      expmarkers.length = 0;
  }
google.maps.event.addDomListener(window, 'load', expchg1);
            ";

$document->addScriptDeclaration($script);
$exp_sef = '';
if($exp_sef){
    $action_link=JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;Itemid='.$itemid);
}else{
    $action_link=JRoute::_('index.php');
}
?>
<div id="modexpgoogleads<?php echo $module->id; ?>" class="modexpgoogleads expgoogleads<?php echo $moduleclass_sfx; ?>">
    <div class="expgmapleft">
        <div id="map<?php echo $moduleid; ?>" style="width: <?php echo $params->get('width'); ?>px; height: <?php echo $params->get('height'); ?>px"></div>
    </div>
    <div class="expgmapright">
        <form action="<?php echo $action_link; ?>" method="get">
            <fieldset class="expgoogleads_fieldset">
            <?php if (!$exp_sef): ?>
                <input type="hidden" name="option" value="com_expautospro" />
                <input type="hidden" name="view" value="explist" />
            <?php endif; ?>
            <?php if($params->get('catid')):?>
                <input type="hidden" id="catid<?php echo $moduleid; ?>" name="catid" value="<?php echo $params->get('catid'); ?>" />
                <?php else:?>
                <p class="expautospro_googleads">
                    <label for="modexpsearch_cats"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEADS_FORM_CATEGORIES_TEXT'); ?></label>
                    <?php echo $category; ?>
                </p>
            <?php endif;?>
            <p class="expautospro_googleads">
                <label for="modexpsearch_makes"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEADS_FORM_MAKES_TEXT'); ?></label>
                <?php echo $make; ?>
            </p>
            <p class="expautospro_googleads">
                <label for="modexpsearch_models"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEADS_FORM_MODELS_TEXT'); ?></label>
                <?php echo $model; ?>
            </p>
            <?php if ($params->get('usesmodel')): ?>
                <p class="expautospro_googleads">
                    <label for="modexpsearch_specificmodel"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEADS_FORM_SPICIFICMODELS_TEXT'); ?></label>
                    <?php echo $specificmodel; ?>
                </p>
            <?php endif; ?>
            <?php if ($params->get('usebodytype')): ?>
                <p class="expautospro_googleads">
                    <label for="modexpsearch_bodytype"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEADS_BODYTYPE_TEXT'); ?></label>
                    <?php echo $bodytype; ?>
                </p>
            <?php endif; ?>
            <?php if ($params->get('useyear')): ?>
                <p class="expautospro_googleads expdoubleads">
                    <span class="expdoubleads_first">
                        <label for="modexpsearch_yearfrom" class="expyearads_label"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEADS_YEARFROM_TEXT'); ?></label>
                        <?php echo $yearfrom; ?>
                    </span>
                    <span>
                        <label for="modexpsearch_yearto" class="expyearads_label"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEADS_YEARTO_TEXT'); ?></label>
                        <?php echo $yearto; ?>
                    </span>
                </p>
                <div class="expclear"></div>
            <?php endif; ?>
            <?php if ($params->get('useprice')): ?>
                <p class="expautospro_googleads expdoubleads">
                    <span class="expdoubleads_first">
                        <label for="modexpsearch_pricefrom" class="expdoubleads_label"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEADS_PRICEFROM_TEXT'); ?></label>
                        <?php echo $pricefrom; ?>
                    </span>
                    <span>
                        <label for="modexpsearch_priceto" class="expdoubleads_label"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEADS_PRICETO_TEXT'); ?></label>
                        <?php echo $priceto; ?>
                    </span>
                </p>
                <div class="expclear"></div>
            <?php endif; ?>
            <?php if ($params->get('usemileage')): ?>
                <p class="expautospro_googleads">
                    <label for="modexpsearch_mileage"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEADS_FORM_MILEAGE_TEXT'); ?></label>
                    <?php echo $mileage; ?>
                </p>
            <?php endif; ?>
            <?php if ($params->get('usefuel')): ?>
                <p class="expautospro_googleads">
                    <label for="modexpsearch_fuel"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEADS_FORM_FUEL_TEXT'); ?></label>
                    <?php echo $fuel; ?>
                </p>
            <?php endif; ?>
            <?php if ($params->get('usedrive')): ?>
                <p class="expautospro_googleads">
                    <label for="modexpsearch_drive"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEADS_FORM_DRIVE_TEXT'); ?></label>
                    <?php echo $drive; ?>
                </p>
            <?php endif; ?>
            <?php if ($params->get('usetrans')): ?>
                <p class="expautospro_googleads">
                    <label for="modexpsearch_trans"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEADS_FORM_TRANSMISSION_TEXT'); ?></label>
                    <?php echo $trans; ?>
                </p>
            <?php endif; ?>
            <input id="expbutton<?php echo $moduleid; ?>" type="submit" name="Submit" class="button" value="<?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEADS_SEARCH_TEXT');?>" />
            <input type="hidden" name="Itemid" value="<?php echo $itemid; ?>" />
            <?php echo JHtml::_('form.token'); ?>
            </fieldset>
        </form>
    </div>
<div class="expautos_images_clear"></div>
    <?php if($params->get('jq_chosen',0) && $params->get('jq_chained',0)):?>
        <script type="text/javascript">
            jQuery(".chzn-expselectgoogleads").chosen();
        </script>
    <?php endif;?>
</div>