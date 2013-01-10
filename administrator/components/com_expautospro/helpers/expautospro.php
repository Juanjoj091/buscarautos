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

class ExpAutosProHelper {

    public static function addSubmenu($vName) {
        /*
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_CATEGORIES_TEXT'), 'index.php?option=com_expautospro&view=expcats', $vName == 'expcats'
        );
         * 
         */
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_CATEGORIES_TEXT'),
                'index.php?option=com_categories&extension=com_expautospro',
                $vName == 'categories'
        );
        if ($vName=='categories' || $vName=='category') {
            JToolBarHelper::title('title','expautosprocat');
            JHtml::stylesheet(JURI::root().'administrator/components/com_expautospro/assets/expautospro.css', array(), true, false, false);
        }
        
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_MAKES_TEXT'), 'index.php?option=com_expautospro&view=expmakes', $vName == 'expmakes'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_MODELS_TEXT'), 'index.php?option=com_expautospro&view=expmods', $vName == 'expmods'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_BODYTYPES_TEXT'), 'index.php?option=com_expautospro&view=expbodytypes', $vName == 'expbodytypes'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_DRIVES_TEXT'), 'index.php?option=com_expautospro&view=expdrives', $vName == 'expdrives'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_FUELS_TEXT'), 'index.php?option=com_expautospro&view=expfuels', $vName == 'expfuels'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_TRANSMISSIONS_TEXT'), 'index.php?option=com_expautospro&view=exptrans', $vName == 'exptrans'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_CONDITIONS_TEXT'), 'index.php?option=com_expautospro&view=expcondits', $vName == 'expcondits'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_COLORS_TEXT'), 'index.php?option=com_expautospro&view=expcolors', $vName == 'expcolors'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_COUNTRS_TEXT'), 'index.php?option=com_expautospro&view=expcountrs', $vName == 'expcountrs'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_STATE_TEXT'), 'index.php?option=com_expautospro&view=expstates', $vName == 'expstates'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_CITS_TEXT'), 'index.php?option=com_expautospro&view=expcits', $vName == 'expcits'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_EQCATS_TEXT'), 'index.php?option=com_expautospro&view=expeqcats', $vName == 'expeqcats'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_EQS_TEXT'), 'index.php?option=com_expautospro&view=expeqs', $vName == 'expeqs'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_USERLEVELS_TEXT'), 'index.php?option=com_expautospro&view=expuserlevels', $vName == 'expuserlevels'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_USERS_TEXT'), 'index.php?option=com_expautospro&view=expusers', $vName == 'expusers'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_CURRENCIES_TEXT'), 'index.php?option=com_expautospro&view=expcurrs', $vName == 'expcurrs'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_EXTRAFIELD1S_TEXT'), 'index.php?option=com_expautospro&view=expextrafield1s', $vName == 'expextrafield1s'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_EXTRAFIELD2S_TEXT'), 'index.php?option=com_expautospro&view=expextrafield2s', $vName == 'expextrafield2s'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_EXTRAFIELD3S_TEXT'), 'index.php?option=com_expautospro&view=expextrafield3s', $vName == 'expextrafield3s'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_PAYMENTS_TEXT'), 'index.php?option=com_expautospro&view=exppayments', $vName == 'exppayments'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_EXPORT_TEXT'), 'index.php?option=com_expautospro&view=export', $vName == 'export'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_IMPORT_TEXT'), 'index.php?option=com_expautospro&view=import', $vName == 'import'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_CONFIGURATION'), 'index.php?option=com_expautospro&view=expconfig&layout=edit&id=1', $vName == 'expconfig'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TEXT'), 'index.php?option=com_expautospro&view=expadmanagers', $vName == 'expadmanagers'
        );
    }

    public static function getActions($catName, $categoryId = 0) {
        $user = JFactory::getUser();
        $result = new JObject;

        if (empty($categoryId)) {
                $assetName = 'com_expautospro';
        } else {
                $assetName = 'com_expautospro.category.'.(int) $categoryId;
        }
        /*
        if (empty($categoryId)) {
            $assetName = 'com_expautospro';
        } else {
            $assetName = 'com_expautospro.' . $catName . '.' . (int) $categoryId;
        }
         * 
         */

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }

}

?>
