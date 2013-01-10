<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/


defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
require_once JPATH_ADMINISTRATOR.'/components/com_expautospro/helpers/expfields.php';

class JFormFieldExpmenustate extends JFormField {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Expmenustate';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getInput($data=0) {
        
        $country_id = '';
        if($this->form->getValue('request'))
            $country_id = $this->form->getValue('request')->country;
        $document = JFactory::getDocument();
        $script = '';
        $script .= "
                        window.addEvent('domready', function() {
                        $('jformrequestexpstate').addEvent('change', function(e) {
                        if(this.value > 0){
                        var url = 'index.php?option=com_expautospro&view=expadmanagers&format=ajax&state_id='+this.value;
                        e.stop();
                        log=$('jformrequestcity');
                        var x = new Request({
                            url: url, 
                            method: 'post', 
                            onSuccess: function(responseText){
                                var jsondata=eval('('+responseText+')');
                                log.options.length=0;
                                for (var i=0; i<jsondata.length; i++){
                                    log.options[log.options.length] = new Option(jsondata[i].text,jsondata[i].value)
                                }
                            }
                        }).send();
                        }
                        });
                        })
                    ";

        $document->addScriptDeclaration($script);
        if(!$country_id){
            $country_id=0;
        }
        $state_id = $this->value;
        $options=ExpAutosProFields::getExpvariables($country_id,'state','name','catid',$state_id,'name','',$this->name);

        return $options;
    }
    

}
