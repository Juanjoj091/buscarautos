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
    var dealerlink3 = '';
    var expdealermarkers3 = [];
    var expgdlatid3 = ".$gmdlat.";
    var expgdlongid3 = ".$gmdlong.";
    var expgdzoom3 = ".$gmdzoom.";
    var map;
    var dealercustomIcons3 = {
      1: {
        icon: '".JURI::root()."modules/mod_expautospro_googledealer/images/sportscar.png',
        shadow: '".JURI::root()."modules/mod_expautospro_googledealer/images/shadow.png'
      },
      2: {
        icon: '".JURI::root()."modules/mod_expautospro_googledealer/images/sportscar2.png',
        shadow: '".JURI::root()."modules/mod_expautospro_googledealer/images/shadow.png'
      }
    };
    function expdealerchg3(){
    dealerclearOverlays3();
    dealerinitializeads3();
    }

    function dealerinitializeads3() {
      map = new google.maps.Map(document.getElementById('map".$moduleid."'), {
        center: new google.maps.LatLng(expgdlatid3, expgdlongid3),
        zoom: expgdzoom3,
        mapTypeId: 'roadmap'
      });
      var infoWindow = new google.maps.InfoWindow;

      // Change this depending on the name of your PHP file
      downloadUrl('index.php?option=com_expautospro&view=expdealerlist&format=ajax'+dealerlink3, function(data) {
        var poiJson = eval(data.responseText);
        var countads = poiJson.length;
        var gmdicons ='';
        var gmduser = ".$gmduser.";
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
          var icon = dealercustomIcons3[gmdicons] || {};
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: icon.icon,
            shadow: icon.shadow
          });
          expdealermarkers3.push(marker);
          dealerbindInfoWindow3(marker, map, infoWindow, html);
    };
          var markerCluster = new MarkerClusterer(map, expdealermarkers3);

      });
    }

    function dealerbindInfoWindow3(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }
    // Removes the overlays from the map, but keeps them in the array
  function dealerclearOverlays3() {
    for(var i=0; i<expdealermarkers3.length; i++){
        expdealermarkers3[i].setMap(null);
      }
      expdealermarkers3.length = 0;
  }
  
    function expgdfindAddress3(selTag) {
    }
google.maps.event.addDomListener(window, 'load', dealerinitializeads3);
            ";

$document->addScriptDeclaration($script);

?>
<div id="modexpgoogledealers<?php echo $module->id; ?>" class="modexpgoogledealers expgoogledealers<?php echo $moduleclass_sfx; ?>">
    <div class="expgmapdealersonlymaps">
        <div id="map<?php echo $moduleid; ?>" style="width: <?php echo $params->get('width'); ?>px; height: <?php echo $params->get('height'); ?>px"></div>
    </div>
    <div class="expautos_images_clear"></div>
</div>