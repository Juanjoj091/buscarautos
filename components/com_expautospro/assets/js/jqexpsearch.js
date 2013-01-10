
/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

function expchangejq(val,expurl,valto,modid,esend){
    var expurlall = expurl+val;
    jqexpgetchained(expurlall,valto,modid,esend)
}

function jqexpgetchained(expurlall,valto,modid,esend){
    //alert(modid);
    jQuery.ajax({
        url: expurlall, 
        dataType:(esend) ? "html" : "json",
        beforeSend : function(){
            if(typeof jQuery().button == 'function') {
            jQuery('#'+modid).button('loading');
            }
            jQuery('#'+valto).find('option').remove().end();
        },
        success: function (data) { 
            console.log(data);
            if(esend){
                jQuery('#'+valto).html(data);
            }else{
                jQuery.each(data, function(index, element) {
                    jQuery('#'+valto).prop('disabled', false);
                    jQuery('#'+valto)
                    .append(jQuery('<option>', {
                        text: element.text,
                        value: element.value
                    }));
                });
            }
        },
        complete : function(){
            if(typeof jQuery().button == 'function') {
                jQuery('#'+modid).button('reset');
            }
            jQuery('#'+valto).trigger('liszt:updated');
        }
    });

}

function jqexptoggle(blockid,linkid,showtext,hidetext){
        jQuery('#'+blockid).slideToggle('slow');
        jQuery('#'+linkid).text(jQuery('#'+linkid).text() == showtext ? hidetext : showtext);
        return false;
}
