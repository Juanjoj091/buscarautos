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
//require_once JPATH_COMPONENT . '/helpers/expparams.php';
require_once JPATH_SITE . '/components/com_expautospro/helpers/expparams.php';

class JFormFieldCountry extends JFormFieldList {


    protected $type = 'Country';


    public function getInput($data=0) {
        // Initialize variables.
        $expview = JRequest::getVar('view');
        $user = JFactory::getUser();
        $UserId = $user->id;
        $listgroupparams = '';
        if ($UserId > 0) {
            $listgroupparams = ExpAutosProExpparams::getExpDealersParams($UserId);
        }
        if($this->form->getValue('catid')){
            $expcat = $this->form->getValue('catid');
        }else{
            $expcat = JRequest::getInt('expcat', 0);
        }
        $expcatParams = ExpAutosProExpparams::getCatParams($expcat);
        $expparams = ExpAutosProExpparams::getExpParams('config', 1);
        $val_id = $this->form->getValue('country');
        $options = array();
        if(($expview == 'expadd' && $expcatParams->get('usestate')) || ($expview == 'expuser' && $listgroupparams->get('c_ustate'))){
            $document = JFactory::getDocument();
            $script = '';
            $script .= "
                function change_chained(val){
                var url = 'index.php?option=com_expautospro&view=expuser&format=ajax&expcountry_id='+val;
                    ajaxgetchained(url,'jformexpstate')
                }
                    ";

            $document->addScriptDeclaration($script);
        }
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
        $attr = '';
        $attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
        $attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
        $attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
        $attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';
        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }
        $exponchange = ' onchange="';
        if(($expview == 'expadd' && $expcatParams->get('usestate')) || ($expview == 'expuser' && $listgroupparams->get('c_ustate')))
            $exponchange .= ' change_chained(this.value);';
        if(($expview == 'expuser' && $expparams->get('c_admanager_useradd_showgooglemaps')) || ($expview == 'expadd' && $expcatParams->get('usegooglemaps')))
            $exponchange .= 'findAddress(this);return false;';
        $exponchange .= '"';
        if (!$data) {
            array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_EXPAUTOSPRO_SELECT_TEXT')));

            $return = JHtml::_('select.genericlist', $options, $this->name, trim($attr).trim($exponchange), 'value', 'text', $val_id);
        }

        return $return;
    }

}
