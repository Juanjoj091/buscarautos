<?php

/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */


defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldExpmenumake extends JFormFieldList {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Expmenumake';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getOptions($data = 0) {
        // Initialize variables.
        $options = array();
        $usemodel = (string) $this->element['expmodel'];
        $expcatid = '';
        if(is_object($this->form->getValue('request')))
            $expcatid = (int)$this->form->getValue('request')->catid;
        $document = JFactory::getDocument();
        $script = '';
        $script .= "
                        window.addEvent('domready', function() {
                        ";

        $script .= "
                    $('jform_request_catid').addEvent('change', function(e) {
                    if(this.value > 0){
                            var url = 'index.php?option=com_expautospro&view=expadmanagers&format=ajax&expcatid_id='+this.value;
                            log=$('jform_request_makeid');
                            e.stop();
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
                            var url2 = 'index.php?option=com_expautospro&view=expadmanagers&format=ajax&btype=1&expcatid_id='+this.value;
                            log2=$('jform_request_bodytype');
                            e.stop();
                            if(log2 != null){
                            var x2 = new Request({
                                url: url2, 
                                method: 'post', 
                                onSuccess: function(responseText){
                                    var jsondata=eval('('+responseText+')');
                                    log2.options.length=0;
                                    for (var i=0; i<jsondata.length; i++){
                                        log2.options[log2.options.length] = new Option(jsondata[i].text,jsondata[i].value)
                                    }
                                }
                            }).send();
                            }
                        }
                        });";
        if ($usemodel) {

            $script .= " $('jform_request_makeid').addEvent('change', function(e) {
                        var url = 'index.php?option=com_expautospro&view=expadmanagers&format=ajax&expmake_id='+this.value;
                        e.stop();
                        log=$('jformrequestmodelid');
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
                        });";
        }
        $script .= "
                        })
                    ";

        $document->addScriptDeclaration($script);
        if ($this->value) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('id As value, name As text');
            $query->from('#__expautos_make');
            $query->where('catid = '.$expcatid);
            $query->where('state > 0');
            $query->order('ordering');

            // Get the options.
            $db->setQuery($query);

            $options = $db->loadObjectList();

            // Check for a database error.
            if ($db->getErrorNum()) {
                JError::raiseWarning(500, $db->getErrorMsg());
            }
        }
        if (!$data) {
            array_unshift($options, JHtml::_('select.option', '0', JText::_('JSELECT')));
        }

        return $options;
    }

}
