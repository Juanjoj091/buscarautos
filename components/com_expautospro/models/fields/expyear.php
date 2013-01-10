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

class JFormFieldExpyear extends JFormFieldList {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Expyear';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getInput($data=0) {
        
        $expreqfield = ExpAutosProExpparams::getExpParams('config',1);
        $minyear = $expreqfield->get('c_admanager_minyear');
        if (!$data) {
            $options[] = JHtml::_('select.option', '0', JText::_('COM_EXPAUTOSPRO_SELECT_TEXT'));
            for($i = date("Y") + 1; $i >= $minyear; $i--){
                $options[] = JHTML::_('select.option', $i, $i);
            }
            $attr = '';
            $attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
            $return = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value);
        }

        return $return;
    }

}
