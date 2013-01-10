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
require_once JPATH_COMPONENT.'/helpers/expimages.php';

class JFormFieldExpfile extends JFormField {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Expfile';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getInput() {
        
        $table_id = (int)$this->form->getValue('id');
        $result = ExpAutosProImages::getExpParams('admanager', $table_id);
        $result = $result->get('expfile');
        if ($result) {
            $logoimg = '<li><span class="readonly">';
            $logoimg .= '<a href="'.ExpAutosProImages::FilesUrlPatch().$result.'" target="_blank">'.$result.'</a>';
            $logoimg .= '</span></li>';
            $logoimg .= '<li>';
            $logoimg .='<label id="jform_delete_photo-lbl" for="jform_delete_photo" title="">';
            $logoimg .= JText::_('COM_EXPAUTOSPRO_USER_DELETEFILE_TEXT');
            $logoimg .='</label>';
            $logoimg .='<input type="checkbox" name="delete_expfile"/>';
            $logoimg .= "</li>";
            $logoimg .= "<input type='hidden' name='expfile_name' value='".$result."' />";
        } else {
            $logoimg = '<li><input type="file" name="expfile" value="" /></li>';
        }
        return $logoimg;
    }

}