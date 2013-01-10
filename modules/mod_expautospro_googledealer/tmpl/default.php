<?php
/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

//no direct access
defined('_JEXEC') or die;

$moduleid = $module->id;

if(!empty($postuserid->latitude) && !empty($postuserid->longitude)){
    $gmdlat = $postuserid->latitude;
    $gmdlong = $postuserid->longitude;
    $gmduser = $postuserid->user;
    $gmdzoom = 14;
}else{
    $gmdlat = $params->get('latitude');
    $gmdlong = $params->get('longitude');
    $gmduser = 0;
    $gmdzoom = $params->get('zoom');
}

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_googledealer/css/mod_expautospro_googledealer.css');
$document->addScript('http://maps.google.com/maps/api/js?sensor=false');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxrequest.js');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxchained.js');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expsearchchained.js');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expsearchtoggle.js');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expdownloadxml.js');
$document->addScript(JURI::root() . 'modules/mod_expautospro_googledealer/js/markerclusterer_compiled.js');
$script = '';
$script .= "
    var dealerlink = '';
    var expdealermarkers = [];
    var expgdlatid = ".$gmdlat.";
    var expgdlongid = ".$gmdlong.";
    var expgdzoom = ".$gmdzoom.";
    var geocoder = new google.maps.Geocoder();
    var map;
    var dealercustomIcons = {
      1: {
        icon: '".JURI::root()."modules/mod_expautospro_googledealer/images/sportscar.png',
        shadow: '".JURI::root()."modules/mod_expautospro_googledealer/images/shadow.png'
      },
      2: {
        icon: '".JURI::root()."modules/mod_expautospro_googledealer/images/sportscar2.png',
        shadow: '".JURI::root()."modules/mod_expautospro_googledealer/images/shadow.png'
      }
    };
    function expdealerchg1(){
    dealerclearOverlays();
    var expdllink = '';
    expdllink += '&expgdcountry='+document.getElementById('country".$moduleid."').value;
    if(".$params->get('useexpstate')."){
    expdllink += '&expgdstate='+document.getElementById('expstate".$moduleid."').value;
    if(".$params->get('usecity')."){
    expdllink += '&expgdcity='+document.getElementById('city".$moduleid."').value;
    }
    }
    if(".$params->get('showdealersfield')."){
    expdllink += '&expgduserid='+document.getElementById('userid".$moduleid."').value;
    }
    if(".$params->get('usezipcode')."){
    expdllink += '&expgdzipcode='+document.getElementById('modexpsearch_zipcode".$moduleid."').value;
    }
     dealerlink = expdllink;
     dealerinitializeads1();
    }

    function dealerinitializeads1() {
      map = new google.maps.Map(document.getElementById('map".$moduleid."'), {
        center: new google.maps.LatLng(expgdlatid, expgdlongid),
        zoom: expgdzoom,
        mapTypeId: 'roadmap'
      });
      var infoWindow = new google.maps.InfoWindow;

      // Change this depending on the name of your PHP file
      downloadUrl('index.php?option=com_expautospro&view=expdealerlist&format=ajax'+dealerlink, function(data) {
        var poiJson = eval(data.responseText);
        var countads = poiJson.length;
        var gmdicons ='';
        var gmduser = ".$gmduser.";
        var testtt = document.getElementById('expbutton".$moduleid."').value = '';
         document.getElementById('expbutton".$moduleid."').value = '".JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_SEARCH_TEXT')."' + ' ('+ countads + ') ';
    for (var i = 0;i < countads; i += 1) {
        if (gmduser == poiJson[i].userid){
            gmdicons = 2;
        } else {
            gmdicons = 1;
        }
        var linkval = '".JURI::root() ."index.php?option=com_expautospro&amp;view=expdealerdetail&amp;userid='+poiJson[i].userid+'&amp;Itemid=".$itemid."';
        var explink = '".JRoute::_("'+linkval+'")."';
        var linkadsval = '".JURI::root() ."index.php?option=com_expautospro&amp;view=explist&amp;userid='+poiJson[i].userid+'&amp;Itemid=".$itemid."';
        var expadslink = '".JRoute::_("'+linkadsval+'")."';
        if (poiJson[i].logo) {
            var img_file = '<a href='+explink+'><span></span><img width=".$params->get('logo_width')." height=".$params->get('logo_height')." src=" . ExpAutosProExpparams::ImgUrlPatchLogo() ."'+poiJson[i].logo+' alt=\'\' /></a>';
        } else {
            var img_file = '<a href='+explink+'><span></span><img width=".$params->get('logo_width')." height=".$params->get('logo_height')." src=" . ExpAutosProExpparams::ImgUrlPatch() . "assets/images/no_logo.jpg alt=\'\' /></a>';
        }
        var lat = poiJson[i].latitude;
        var lon = poiJson[i].longitude;
          var type = poiJson[i].catid;
          var point = new google.maps.LatLng(
              parseFloat(poiJson[i].latitude),
              parseFloat(poiJson[i].longitude));
          var html = ''
          html += '<div class=\"expgoogledealer\">'+
          '<div class=\"expgoogledealer_img\">'+ img_file +'</div>'+
          '<div class=\"expgoogledealer_right\">';
          if(".$params->get('showcompany')." && poiJson[i].companyname){
          html += '<p class=\"expgoogledealer_middle\">'+
          '<span class=\'expgmd_span\'>".JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_COMPANYNAME_TEXT')."</span>' + poiJson[i].companyname +
          '</p>';
          }
          if(".$params->get('showphone')." && poiJson[i].phone){
          html += '<p class=\"expgoogledealer_middle\">'+
          '<span class=\'expgmd_span\'>".JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_PHONE_TEXT')."</span>' + poiJson[i].phone +
          '</p>';
          }
          if(".$params->get('showcellphone')." && poiJson[i].mobphone){
          html += '<p class=\"expgoogledealer_middle\">'+
          '<span class=\'expgmd_span\'>".JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_CELLPHONE_TEXT')."</span>' + poiJson[i].mobphone +
          '</p>';
          }
          if(".$params->get('showfax')." && poiJson[i].fax){
          html += '<p class=\"expgoogledealer_middle\">'+
          '<span class=\'expgmd_span\'>".JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_FAX_TEXT')."</span>' + poiJson[i].fax +
          '</p>';
          }
          if(".$params->get('showweb')." && poiJson[i].web){
          html += '<p class=\"expgoogledealer_middle\">'+
          '<span class=\'expgmd_span\'>".JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_WEB_TEXT')."</span>' + poiJson[i].web +
          '</p>';
          }
          html += '<p class=\"expgoogledealer_middle\">'+
          '<a href=\''+explink+'\'>".JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_MOREINFO_TEXT')."</a>' + 
          '</p>';
          html += '<p class=\"expgoogledealer_middle\">'+
          '<a href=\''+expadslink+'\'>".JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_ALLADS_TEXT')."</a>' + 
          '</p>';
          html += '</div>'+
          '<div class=\"expautos_images_clear\"></div>'+
          '</div>';
          var icon = dealercustomIcons[gmdicons] || {};
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: icon.icon,
            shadow: icon.shadow
          });
          expdealermarkers.push(marker);
          dealerbindInfoWindow(marker, map, infoWindow, html);
    };
          var markerCluster = new MarkerClusterer(map, expdealermarkers);

      });
    }

    function dealerbindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }
    // Removes the overlays from the map, but keeps them in the array
  function dealerclearOverlays() {
    for(var i=0; i<expdealermarkers.length; i++){
        expdealermarkers[i].setMap(null);
      }
      expdealermarkers.length = 0;
  }
  
    function expgdfindAddress1(selTag) {
        if(selTag.value > 0){
            var val = selTag.options[selTag.selectedIndex].text;
            expgdsearchByAdress(val,expgdzoom);
        }
    }
    
    
    function expgdsearchByAdress(vartext,zoomvalexp){
        var address = vartext;
        geocoder.geocode( { 'address': address}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            if(typeof latid != 'undefined' && typeof longid != 'undefined'){
                document.getElementById(expgdlatid).value = results[0].geometry.location.lat();
                document.getElementById(expgdlongid).value = results[0].geometry.location.lng();
            }
            map.setCenter(results[0].geometry.location);
            map.setZoom(zoomvalexp);
          } else {
            alert('Error :' + status);
          }
        });
    }
google.maps.event.addDomListener(window, 'load', expdealerchg1);
            ";

$document->addScriptDeclaration($script);

$app = JFactory::getApplication();
$exp_sef = $app->getCfg('sef');
if ($exp_sef) {
    $action_link = JRoute::_('index.php?option=com_expautospro&amp;view=expdealerlist&amp;Itemid=' . $itemid);
} else {
    $action_link = JRoute::_('index.php');
}
?>
<div id="modexpgoogledealers<?php echo $module->id; ?>" class="modexpgoogledealers expgoogledealers<?php echo $moduleclass_sfx; ?>">
    <div class="expgmapdealersleft">
        <div id="map<?php echo $moduleid; ?>" style="width: <?php echo $params->get('width'); ?>px; height: <?php echo $params->get('height'); ?>px"></div>
    </div>
    <div class="expgmapdealersright">
        <form action="<?php echo $action_link; ?>" method="get">
            <?php if (!$exp_sef): ?>
                <input type="hidden" name="option" value="com_expautospro" />
                <input type="hidden" name="view" value="expdealerlist" />
            <?php endif; ?>
            <?php if ($params->get('usecountry')): ?>
                <?php if (!$params->get('country')): ?>
                    <p class="expautospro_gmdealers">
                        <label for="modexpsearch_country"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_FORM_COUNTRY_TEXT'); ?></label>
                        <?php echo $country; ?>
                    </p>
                <?php endif; ?>
                <?php if ($params->get('useexpstate')): ?>
                    <p class="expautospro_gmdealers">
                        <label for="modexpsearch_expstate"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_FORM_EXPSTATE_TEXT'); ?></label>
                        <?php echo $expstate; ?>
                    </p>
                    <?php if ($params->get('usecity')): ?>
                        <p class="expautospro_gmdealers">
                            <label for="modexpsearch_city"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_FORM_CITY_TEXT'); ?></label>
                            <?php echo $city; ?>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($params->get('showdealersfield')): ?>
                <p class="expautospro_gmdealers">
                    <label for="modexpsearch_dealers"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_FORM_DEALERS_TEXT'); ?></label>
                    <?php echo $dealers; ?>
                </p>
            <?php endif; ?>
            <?php if ($params->get('usezipcode')): ?>
                <p class="expautospro_gmdealers">
                    <label for="modexpsearch_zipcode"><?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_FORM_ZIPCODE_TEXT'); ?></label>
                    <?php echo $zipcode; ?>
                </p>
            <?php endif; ?>
            <input id="expbutton<?php echo $moduleid; ?>" type="submit" name="Submit" class="button" value="<?php echo JText::_('MOD_EXPAUTOSPRO_GOOGLEDEALERS_SEARCH_TEXT') ?>" />
            <div class="expautos_images_clear"></div>
            <?php if ($params->get('country')): ?>
                <input type="hidden" name="country" id="country<?php echo $moduleid; ?>" value="<?php echo $params->get('country'); ?>" />
            <?php endif; ?>
            <input type="hidden" name="Itemid" value="<?php echo $itemid; ?>" />
            <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>
    <div class="expautos_images_clear"></div>
</div>