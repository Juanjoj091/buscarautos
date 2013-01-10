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

class JFormFieldExpmenucountry extends JFormFieldList {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Expmenucountry';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getOptions($data=0) {
        $options = array();
        $document = JFactory::getDocument();
        $script = '';
        $script .= "
                    window.addEvent('domready', function() {
                    $('jform_request_country').addEvent('change', function(e) {
                    if(this.value > 0){
                    var url = 'index.php?option=com_expautospro&view=expadmanagers&format=ajax&expcountry_id='+this.value;
                    e.stop();
                    log=$('jformrequestexpstate');
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
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('a.id As value, a.name As text');
        $query->from('#__expautos_country AS a');
        $query->where('a.state > 0');
        $query->order('a.name');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }
        if (!$data) {
            array_unshift($options, JHtml::_('select.option', '0', JText::_('JSELECT')));
        }

        return $options;
    }

}
