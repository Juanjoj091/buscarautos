<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class ExpAutosProViewExport extends JView {

    protected $link;

    public function display($tpl = null) {
        $exporttable = JRequest::getString('exporttable', 0);
        $select_database = array(
            JHtml::_('select.option', 'categories', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_CATEGORIES_TEXT')),
            JHtml::_('select.option', 'expautos_make', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_MAKES_TEXT')),
            JHtml::_('select.option', 'expautos_model', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_MODEL_TEXT')),
            JHtml::_('select.option', 'expautos_bodytype', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_BODYTYPE_TEXT')),
            JHtml::_('select.option', 'expautos_drive', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_DRIVE_TEXT')),
            JHtml::_('select.option', 'expautos_fuel', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_FUEL_TEXT')),
            JHtml::_('select.option', 'expautos_trans', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_TRANS_TEXT')),
            JHtml::_('select.option', 'expautos_condition', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_CONDITION_TEXT')),
            JHtml::_('select.option', 'expautos_color', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_COLOR_TEXT')),
            JHtml::_('select.option', 'expautos_country', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_COUNTRY_TEXT')),
            JHtml::_('select.option', 'expautos_state', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_STATE_TEXT')),
            JHtml::_('select.option', 'expautos_cities', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_SITIES_TEXT')),
            JHtml::_('select.option', 'expautos_catequipment', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_CATEQUIP_TEXT')),
            JHtml::_('select.option', 'expautos_equipment', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_EQUIP_TEXT')),
            JHtml::_('select.option', 'expautos_userlevel', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_USERLEVEL_TEXT')),
            JHtml::_('select.option', 'expautos_expuser', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_USER_TEXT')),
            JHtml::_('select.option', 'expautos_currency', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_CURRENCY_TEXT')),
            JHtml::_('select.option', 'expautos_extrafield1', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_EXTRAFIELD1_TEXT')),
            JHtml::_('select.option', 'expautos_extrafield2', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_EXTRAFIELD2_TEXT')),
            JHtml::_('select.option', 'expautos_extrafield3', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_EXTRAFIELD3_TEXT')),
            JHtml::_('select.option', 'expautos_payment', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_PAYMENT_TEXT')),
            JHtml::_('select.option', 'expautos_config', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_CONFIG_TEXT')),
            JHtml::_('select.option', 'expautos_admanager', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_ADMANAGER_TEXT')),
            JHtml::_('select.option', 'expautos_images', JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_IMAGE_TEXT'))
        );
        $select_databases = JHtml::_('select.options', $select_database, 'value', 'text', $exporttable);
        $this->buildpositions = $select_databases;
        
        switch ($exporttable) {
            case 'categories':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_CATEGORIES_TEXT');
                break;
            case 'expautos_make':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_MAKES_TEXT');
                break;
            case 'expautos_model':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_MODEL_TEXT');
                break;
            case 'expautos_bodytype':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_BODYTYPE_TEXT');
                break;
            case 'expautos_drive':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_DRIVE_TEXT');
                break;
            case 'expautos_fuel':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_FUEL_TEXT');
                break;
            case 'expautos_trans':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_TRANS_TEXT');
                break;
            case 'expautos_condition':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_CONDITION_TEXT');
                break;
            case 'expautos_color':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_COLOR_TEXT');
                break;
            case 'expautos_country':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_COUNTRY_TEXT');
                break;
            case 'expautos_state':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_STATE_TEXT');
                break;
            case 'expautos_cities':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_SITIES_TEXT');
                break;
            case 'expautos_catequipment':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_CATEQUIP_TEXT');
                break;
            case 'expautos_equipment':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_EQUIP_TEXT');
                break;
            case 'expautos_userlevel':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_USERLEVEL_TEXT');
                break;
            case 'expautos_expuser':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_USER_TEXT');
                break;
            case 'expautos_currency':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_CURRENCY_TEXT');
                break;
            case 'expautos_extrafield1':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_EXTRAFIELD1_TEXT');
                break;
            case 'expautos_extrafield2':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_EXTRAFIELD2_TEXT');
                break;
            case 'expautos_extrafield3':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_EXTRAFIELD3_TEXT');
                break;
            case 'expautos_payment':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_PAYMENT_TEXT');
                break;
            case 'expautos_config':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_CONFIG_TEXT');
                break;
            case 'expautos_admanager':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_ADMANAGER_TEXT');
                break;
            case 'expautos_images':
                $title_text = JText::_('COM_EXPAUTOSPRO_CSV_EXPORT_IMAGE_TEXT');
                break;
            default:
               $title_text = JText::_('COM_EXPAUTOSPRO_CSV_SELECTDATABASE_TEXT');
        }
        require_once JPATH_COMPONENT . '/helpers/helper.php';
        JHtml::stylesheet('administrator/components/com_expautospro/assets/expautospro.css');
        JToolBarHelper::title( JText::_('COM_EXPAUTOSPRO_EXPORT_TEXT')."::".$title_text, 'expexport.png');
        if($exporttable){
        JToolBarHelper::custom('export.catexportxml', 'export.png', 'export_f2.png', 'COM_EXPAUTOSPRO_BUTTON_EXPORTXML_TEXT', false);
        JToolBarHelper::custom('export.catexportcsv', 'export.png', 'export_f2.png', 'COM_EXPAUTOSPRO_BUTTON_EXPORTCSV_TEXT', false);
        }
        JToolBarHelper::divider();

        JToolBarHelper::help('export.html', $com = true);
        
        if ($exporttable == "expautos_model") {
            require_once JPATH_COMPONENT . '/models/fields/expdatabase.php';
            $this->lists = JHtml::_('select.options', JFormFieldExpdatabase::getOptions('make'), 'value', 'text');
        } elseif ($exporttable == "expautos_state") {
            require_once JPATH_COMPONENT . '/models/fields/categor.php';
            $this->lists = JHtml::_('select.options', JFormFieldCategor::getOptions('country'), 'value', 'text');
        } elseif ($exporttable == "expautos_cities") {
            require_once JPATH_COMPONENT . '/models/fields/categor.php';
            $this->lists = JHtml::_('select.options', JFormFieldCategor::getOptions('state'), 'value', 'text');
        } elseif ($exporttable == "expautos_equipment") {
            require_once JPATH_COMPONENT . '/models/fields/categor.php';
            $this->lists = JHtml::_('select.options', JFormFieldCategor::getOptions('catequipment'), 'value', 'text');
        } elseif ($exporttable == "expautos_expuser") {
            require_once JPATH_COMPONENT . '/models/fields/juser.php';
            $this->lists = JHtml::_('select.options', JFormFieldJuser::getOptions(), 'value', 'text');
        }
        parent::display($tpl);
    }
}
