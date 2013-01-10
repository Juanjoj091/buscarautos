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
require_once JPATH_SITE . '/components/com_expautospro/helpers/expparams.php';

class JFormFieldExptime extends JFormFieldList {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Exptime';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    
    public function getOptions() {
        $expparams = ExpAutosProExpparams::getExpParams('config', 1);
        $return = '';
            $options[] = JHtml::_('select.option', '0', JText::_('COM_EXPAUTOSPRO_SELECT_TIME_TEXT'));
            $options[] = JHtml::_('select.option', '1', JText::_('COM_EXPAUTOSPRO_SELECT_TIME_VAL_1DAY_TEXT'));
            $options[] = JHtml::_('select.option', '7', JText::_('COM_EXPAUTOSPRO_SELECT_TIME_VAL_7DAYS_TEXT'));
            $options[] = JHtml::_('select.option', '14', JText::_('COM_EXPAUTOSPRO_SELECT_TIME_VAL_14DAYS_TEXT'));
            $options[] = JHtml::_('select.option', '30', JText::_('COM_EXPAUTOSPRO_SELECT_TIME_VAL_30DAYS_TEXT'));

        return $options;
    }

}
