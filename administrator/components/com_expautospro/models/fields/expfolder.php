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
require_once JPATH_COMPONENT . '/helpers/expimages.php';

class JFormFieldExpfolder extends JFormFieldList {

    protected $type = 'Expfolder';

    public function getInput($data = 0) {
        // Initialize variables.
        $options = array();
        $doc = JFactory::getDocument();
        //$val_id = $this->form->getValue('c_admanager_fpcat_skin');
        //print_r($this->name);
        $folder_name = $this->element['fname'];
        $mark_skins = ExpAutosProImages::FoldersName($folder_name);
        foreach ($mark_skins as $skin) {
            $options[] = JHTML::_('select.option', $skin, $skin);
        }
        $return = JHtml::_('select.genericlist', $options, $this->name, '', 'value', 'text', $this->value);
        if ($this->element['expparams']) {
            JHtml::_('behavior.framework');
            JHtml::_('behavior.modal', 'a.modal');

            $link = 'index.php?option=com_expautospro&view=expsource&layout=edit&tmpl=component&skin=';
            $js = "
                function jSelectskin(valid,valname,valfolder) {
                var skinname = document.getElementsByName(valname)[0].value;
                var expskin = valfolder + '/' + skinname;
                SqueezeBox.fromElement('" . $link . "'+expskin, {handler:'iframe', size: {x: 600, y: 450}, url:'" . $link . "'})
                }";
            $doc->addScriptDeclaration($js);

            $return .= '<input type="button" value="' . JText::_('COM_EXPAUTOSPRO_BUTTON_CHANGE_PARAMS_DESC') . '" onclick="jSelectskin(\'' . $this->id . '\',\'' . $this->name . '\',\'' . $folder_name . '\');" />';
        }

        $jsd = "
                function deleteskin(valid,valname,valfolder) {
                var skinname = document.getElementsByName(valname)[0].value;
                if(confirm('".JText::sprintf('COM_EXPAUTOSPRO_CONFIG_SKIN_WANTREMOVE_TEXT',"'+skinname+'")."')){
                var expskin = valfolder+'/'+skinname;
                document.getElementById('skinpatch_'+valid).value = expskin;
                Joomla.submitform('expconfig.expdelskin');
                document.getElementById('skinpatch_'+valid).value = '';
                }else{
                  return false;
                 } 
                }
                ";
        $doc->addScriptDeclaration($jsd);

        $return .= '<input type="button" value="' . JText::_('COM_EXPAUTOSPRO_CONFIG_SKIN_DELETEBUTTON_TEXT') . '" onclick="deleteskin(\'' . $this->id . '\',\'' . $this->name . '\',\'' . $folder_name . '\');return false;" />';
        $return .= '<input type="hidden" id="skinpatch_' . $this->id . '" name="skinpatch[]" value="" />';

        return $return;
    }

}
?>
