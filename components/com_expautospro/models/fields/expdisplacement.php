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

class JFormFieldExpdisplacement extends JFormFieldList {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Expdisplacement';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    
    public function getInput($data=0) {
        $expparams = ExpAutosProExpparams::getExpParams('config', 1);
            $maxnum = $expparams->get('c_admanager_add_maxdisplacement');
            $maxnum2 = 10;
            $expdisp_val1='';
            $expdisp_val2='';
            $expdisp = explode(".", $this->value);
            $expdisp_val1 = $expdisp[0];
            if(isset($expdisp[1]) && $expdisp[1] != '')
                $expdisp_val2 = $expdisp[1];
            //print_r($expdisp_val2);
            $return = '';
            
            
        if (!$data) {
            $options[] = JHtml::_('select.option', '', JText::_('COM_EXPAUTOSPRO_SELECT_TEXT'));
            for ($i = 0; $i < $maxnum+1; $i++) {
                $options[] = JHTML::_('select.option', $i, $i);
            }
            $options2[] = JHtml::_('select.option', '', JText::_('COM_EXPAUTOSPRO_SELECT_TEXT'));
            for ($i = 0; $i < $maxnum2+1; $i++) {
                $options2[] = JHTML::_('select.option', $i, $i);
            }            
            $expclass = $this->element['class'] ? ' class="'.(string) $this->element['class'].' expdisplacement inputbox" size="1"' : ' class="expdisplacement inputbox" size="1"';
            $return .= JHtml::_('select.genericlist', $options, 'jform[displacement][1]', $expclass, 'value', 'text', $expdisp_val1);
            $return .= " . ";
            $return .= JHtml::_('select.genericlist', $options2, 'jform[displacement][2]', $expclass, 'value', 'text', $expdisp_val2);
        }

        return $return;
    }

}
