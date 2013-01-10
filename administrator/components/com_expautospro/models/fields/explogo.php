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

class JFormFieldExplogo extends JFormField {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Explogo';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getInput() {
        
        $table_id = (int)$this->form->getValue('id');
        $db		= JFactory::getDbo();
        $query	= $db->getQuery(true);

        $query->select('logo');
        $query->from('#__expautos_expuser');
        $query->where('id = '.$table_id);

        // Get the options.
        $db->setQuery($query);

        $result = $db->loadResult();

        // Check for a database error.
        if ($db->getErrorNum()) {
                JError::raiseWarning(500, $db->getErrorMsg());
        }
        if ($result) {
            $logoimg = '<li><img src="' . ExpAutosProImages::ImgUrlPatchLogo() . $result . '" /></li>';
            $logoimg .= '<li>';
            $logoimg .='<label id="jform_delete_logo-lbl" for="jform_delete_logo" title="">';
            $logoimg .= JText::_('COM_EXPAUTOSPRO_USER_DELETELOGO_TEXT') ;
            $logoimg .='</label>';
            $logoimg .='<input type="checkbox" name="delete_logo"/>';
            $logoimg .= "</li>";
            $logoimg .= "<input type='hidden' name='logo_name' value='".$result."' />";
        } else {
            $logoimg = '<li><input type="file" name="logo" value="" /></li>';
        }
        return $logoimg;
    }

}