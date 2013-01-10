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

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_chart/css/mod_expautospro_chart.css');

if ($params->get('jquery') == 1) {
    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/jquery.min.js');
} elseif ($params->get('jquery') == 2) {
    $document->addScript(JURI::root() . 'modules/mod_expautospro_chart/js/jquery.min.js');
}
$document->addScript(JURI::root() . 'modules/mod_expautospro_chart/js/jquery.jqplot.min.js');
$document->addScript(JURI::root() . 'modules/mod_expautospro_chart/js/shCore.min.js');
$document->addScript(JURI::root() . 'modules/mod_expautospro_chart/js/shBrushJScript.min.js');
$document->addScript(JURI::root() . 'modules/mod_expautospro_chart/js/shBrushXml.min.js');
$document->addScript(JURI::root() . 'modules/mod_expautospro_chart/js/jqplot.highlighter.min.js');
//$document->addScript(JURI::root() . 'modules/mod_expautospro_chart/js/jqplot.pointLabels.min.js');
$document->addScript(JURI::root() . 'modules/mod_expautospro_chart/js/jqplot.cursor.min.js');
$document->addScript(JURI::root() . 'modules/mod_expautospro_chart/js/jqplot.dateAxisRenderer.min.js');
$moduleid = $module->id;
//print_r($expchart_text);
?>
<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="<?php echo JURI::root() . 'modules/mod_expautospro_chart/js/excanvas.min.js';?>"></script><![endif]-->
<script class="code" type="text/javascript">
$(document).ready(function(){
$("#expchart<?php echo $moduleid;?>").bind("jqplotClick", function(ev, gridpos, datapos, neighbor) {
    if (neighbor && neighbor.data[5]) {
        window.location = neighbor.data[5];
    }
});
    
    
  var line1=[<?php echo $expcharts;?>];
  var line2=[<?php echo $expcurrentchart;?>];
  var plot1 = $.jqplot('expchart<?php echo $moduleid;?>', [line1,line2], {
      title:'<?php echo $expchart_text;?>',
      axes:{
        xaxis:{
          tickOptions:{
            formatString:'%.0f'
          },
            label:'<?php echo JText::_(JText::sprintf('MOD_EXPAUTOSPRO_CHART_LABEL_MILIAGE_TEXT', $params->get('chartmiles'))); ?>'
        },
        yaxis:{
          tickOptions:{
            formatString:'<?php echo $params->get('chartpricebefore');?>%.0f <?php echo $params->get('chartpriceafter');?>'
            },
            label:'<?php echo JText::_('MOD_EXPAUTOSPRO_CHART_LABEL_PRICE_TEXT');?>'
        }
      },
      seriesDefaults: {
      pointLabels:{ show:true, location:'s', ypadding:3 }
    },
      highlighter: {
        show: true,
        sizeAdjust: 7.5,
        yvalues: 4,
    formatString:'<table class="jqplot-highlighter thumbnail"><tr><td><?php echo JText::_('MOD_EXPAUTOSPRO_CHART_LABEL_MILIAGE_TABLE_TEXT');?></td><td>%s</td></tr><tr><td><?php echo JText::_('MOD_EXPAUTOSPRO_CHART_LABEL_PRICE_TABLE_TEXT');?></td><td>%s</td></tr><tr><td><?php echo JText::_('MOD_EXPAUTOSPRO_CHART_LABEL_BODYTYPE_TABLE_TEXT');?></td><td>%s</td></tr><tr><td><?php echo JText::_('MOD_EXPAUTOSPRO_CHART_LABEL_TRANS_TABLE_TEXT');?></td><td>%s</td></tr><tr><th COLSPAN="2">%s</th></tr></table>'
      },
      legend:{show:true},
      series:[
            {label:'<?php echo JText::_('MOD_EXPAUTOSPRO_CHART_LABEL_SERIES_OTHERS_TEXT');?>',
            showLine:false },
            {label:'<?php echo JText::_('MOD_EXPAUTOSPRO_CHART_LABEL_SERIES_CURRENT_TEXT');?>',
            showLine:false },
          ],
      cursor: {
        show: <?php echo $params->get('zoom');?>,
        zoom:true,
        showTooltip:false
      }
  });
});
</script>
<div id="modexpchart<?php echo $moduleid;?>" class="expchart <?php echo $moduleclass_sfx;?>">
    <div id="expchart<?php echo $moduleid;?>" style="width:<?php echo $params->get('chartwidth');?>px;height:<?php echo $params->get('chartheight');?>px;"></div>
</div>