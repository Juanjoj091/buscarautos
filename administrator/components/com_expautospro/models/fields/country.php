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
require_once JPATH_COMPONENT . '/helpers/expimages.php';

class JFormFieldCountry extends JFormFieldList {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Country';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getInput($data=0) {
        // Initialize variables.
        $expparams = ExpAutosProImages::getExpParams('config',1);
        $val_id = $this->form->getValue('country');
        $options = array();
        $document = JFactory::getDocument();
        $script = '';
        $script .= "
                    window.addEvent('domready', function() {
                    $('jformcountry').addEvent('change', function(e) {
                    var url = 'index.php?option=com_expautospro&view=expadmanagers&format=ajax&expcountry_id='+this.value;
                    expchained(url,'jformexpstate',e);
                    });
                    })
                ";

        $document->addScriptDeclaration($script);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        if ($data) {
            $databasename = (string) $data;
        } else {
            $databasename = (string) $this->element['database'];
        }
        $query->select('id As value, name As text');
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
        // $onchange	= ' onchange="change_cat(this.value);"';
        $onchange	= '';
        if($expparams->get('c_admanager_useradd_showgooglemaps'))
            $onchange	= ' onchange="findAddress(this);return false;"';
        
            array_unshift($options, JHtml::_('select.option', '0', JText::_('JSELECT')));
            $return = JHtml::_('select.genericlist', $options, $this->name, $onchange, 'value', 'text', $val_id);

        return $return;
    }

}
