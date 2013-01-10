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
require_once JPATH_SITE . '/components/com_expautospro/helpers/helper.php';

class ExpAutosProFields {

    public static function getExpvariables($fieldId,$database_name,$select_name='name',$whereId='catid',$val_id=0,$ordering_name='name',$change_name='',$el_class='',$expst=0,$form_name='') {

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
        if(!$expst)
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
        //print_r($this->name);
        $attr = '';
        if($el_class)
        $attr .= ' class="'.(string) $el_class.'"';
        if($change_name)
        $attr .= ' onchange="'.$change_name.'"';
        if (!isset($data)) {
            array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_EXPAUTOSPRO_SELECT_TEXT')));

            $return = JHtml::_('select.genericlist', $options, $form_name, trim($attr), 'value', 'text', $val_id);
        }

        return $return;
    }
    
    public static function getExpSelect($fieldId,$database_name,$select_name='name',$whereId='catid',$val_id=0,$ordering_name='name',$change_name='',$expcount='') {

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
        //if(isset($this->element['class']))
        //$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
        if($change_name)
        $attr .= ' onchange="'.$change_name.'(this.value);"';
        array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_EXPAUTOSPRO_SELECT_TEXT')));
        if($expcount && $select_name){
            $count_opt = count($options);
            for ($i = 0, $n = $count_opt; $i < $n; $i++) {
                $expcount_num = '';
                $expcount = null;
                 if($options[$i]->value){
                    $expcount = ExpAutosProHelper::getExpcount('admanager', $database_name, $options[$i]->value);
                }
                if($options[$i]->value){
                    $expcount_num = " (".$expcount.")";
                }
                $options[$i]->text = $options[$i]->text.$expcount_num;
            }
        }
        return json_encode($options);
    }
    
    public static function getExpJson($select, $database_name, $where,$ordering_name='name',$joinleft='') {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select($select);
        $query->from('#__expautos_'.$database_name.' AS a');
        if($joinleft){
            $query->join('LEFT', '#__expautos_'.$joinleft);
        }
        $query->where($where);
        $query->order('a.'.$ordering_name);

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }
        return json_encode($options);
    }
    
    public static function getExpuserid($userid){
        if($userid){
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('id');
            $query->from('#__expautos_expuser');
            $query->where('userid = ' . (int)$userid);
            $db->setQuery($query);
            $result = $db->loadResult();
            return $result;
        }
    }
    
    public static function expaddcheck($catid,$userid=''){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id');
        $query->from('#__expautos_admanager');
        $query->where('id = '.(int)$catid);
        if($userid)
        $query->where('user = '.(int)$userid);
        $db->setQuery($query);
        $expuserid = $db->loadResult();
        
        return $expuserid;
    }
    
    public static function exp_convertsize($fs) {
        if ($fs >= 1073741824)
            $fs = round($fs / 1073741824 * 100) / 100 . " " . JText::_('COM_EXPAUTOSPRO_CP_GB_TEXT');
        elseif ($fs >= 1048576)
            $fs = round($fs / 1048576 * 100) / 100 . " " . JText::_('COM_EXPAUTOSPRO_CP_MB_TEXT');
        elseif ($fs >= 1024)
            $fs = round($fs / 1024 * 100) / 100 . " " . JText::_('COM_EXPAUTOSPRO_CP_KB_TEXT');
        else
            $fs = $fs . " " . JText::_('COM_EXPAUTOSPRO_CP_B_TEXT');
        return $fs;
    }

}

?>