<?php
/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

// no direct access
defined('_JEXEC') or die;
//print_r($expparams->get('c_admanager_fpcat_allowcat'));
//print_r($expparams->get('c_admanager_fpcat_catlinkto'));
//print_r($list);
$document = JFactory::getDocument();

$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_categories/css/sitemapstyler.css');
//$document->addScript(JURI::root() . 'modules/mod_expautospro_categories/js/sitemapstyler.js');
$module_id = $module->id;
$defcollapce = $params->get('style_collapse',1);
?>
<ul id="expsitemap<?php echo $module_id;?>" class="expcategories-module expcatmod <?php echo $moduleclass_sfx; ?>">
<?php
require JModuleHelper::getLayoutPath('mod_expautospro_categories', $params->get('layout', 'default').'_items');
?></ul>
<script>
    var sitemap = document.getElementById("expsitemap<?php echo $module_id;?>")
    if(sitemap){
		
        this.listItem = function(li){
            if(li.getElementsByTagName("ul").length > 0){
                var ul = li.getElementsByTagName("ul")[0];
                var span = document.createElement("span");
                if(<?php echo $defcollapce;?>){
                    ul.style.display = "none";
                    span.className = "collapsed";
                    if(li.getElementsByClassName("expactive").length > 0){
                        ul.style.display = "block";
                        span.className = "expanded";
                    }
                }else{
                    ul.style.display = "block";
                    span.className = "expanded";
                }
                span.onclick = function(){
                    ul.style.display = (ul.style.display == "none") ? "block" : "none";
                    this.className = (ul.style.display == "none") ? "collapsed" : "expanded";
                };
                li.appendChild(span);
            };
        };
		
        var items = sitemap.getElementsByTagName("li");
        for(var i=0;i<items.length;i++){
            listItem(items[i]);
        };
		
    };  
</script>
