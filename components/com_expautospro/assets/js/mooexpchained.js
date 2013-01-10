
/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

var expchained = function(valurl,valto,e){
    log=$(valto);
    e.stop();
    if(log){
        log.empty().addClass('ajax-loading');
        var url = valurl;
        var x = new Request({
            url: url, 
            method: 'post', 
            onSuccess: function(responseText){
                var jsondata=eval("("+responseText+")");
                log.options.length=0;
                for (var i=0; i<jsondata.length; i++){
                    log.options[log.options.length] = new Option(jsondata[i].text,jsondata[i].value)
                }
                    
            }
        }).send();
    }
}


