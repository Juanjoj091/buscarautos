<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/


// no direct access
defined('_JEXEC') or die('Restricted access');

class ExpAutosProFields {

    public static function getExpvariables($fieldId,$database_name,$select_name='name',$whereId='catid',$val_id=0,$ordering_name='name',$change_name='',$form_name='') {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        if(!$fieldId){
            $fieldId='0';
        }
        if(!$val_id){
            $val_id='0';
        }
        if($database_name == 'cities'){
            $query->select('DISTINCT '.$select_name.' As text, id As value');
        }else{
            $query->select('id As value, '.$select_name.' As text');
        }
        $query->from('#__expautos_'.$database_name.' AS a');
        $query->where('a.'.$whereId.' = ' . $fieldId);
        $query->where('a.state > 0');
        if($database_name == 'cities'){
            $query->group($select_name);
        }
        $query->order('a.'.$ordering_name);

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }
        $onchange = '';
        if($change_name)
        $onchange .= ' onchange="'.$change_name.'"';
        $return = '';
        if (!isset($data)) {
            array_unshift($options, JHtml::_('select.option', '0', JText::_('JSELECT')));

            $return = JHtml::_('select.genericlist', $options, $form_name, $onchange, 'value', 'text', $val_id);
        }

        return $return;
    }
    
    public static function getExpSelect($fieldId,$database_name,$select_name='name',$whereId='catid',$val_id=0,$ordering_name='name',$change_name='') {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        if(!$fieldId){
            $fieldId='0';
        }
        if(!$val_id){
            $val_id='0';
        }

        
        if($database_name == 'cities'){
            $query->select('DISTINCT '.$select_name.' As text, id As value');
        }else{
            $query->select('id As value, '.$select_name.' As text');
        }
        $query->from('#__expautos_'.$database_name.' AS a');
        $query->where('a.'.$whereId.' = ' . $fieldId);
        $query->where('a.state > 0');
        if($database_name == 'cities'){
            $query->group($select_name);
        }
        $query->order('a.'.$ordering_name);

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }
        $attr = '';
        //$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
        if($change_name)
        $attr .= ' onchange="'.$change_name.'(this.value);"';
            array_unshift($options, JHtml::_('select.option', '0', JText::_('JSELECT')));
        return json_encode($options);
    }

}

?>