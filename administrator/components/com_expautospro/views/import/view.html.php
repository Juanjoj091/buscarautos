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

class ExpAutosProViewImport extends JView {

    protected $link;

    function display($tpl = null) {
        $link = JRequest::getString('link', 0);
        $this->link = $link;
        JToolBarHelper::custom('import.catimportxml', 'upload.png', 'export_f2.png', 'COM_EXPAUTOSPRO_BUTTON_EXPORTXML_TEXT', false);
        JToolBarHelper::custom('import.catimportcsv', 'upload.png', 'export_f2.png', 'COM_EXPAUTOSPRO_BUTTON_EXPORTCSV_TEXT', false);
        JHtml::stylesheet('administrator/components/com_expautospro/assets/expautospro.css');
        JToolBarHelper::title(JText::_('COM_EXPAUTOSPRO_BUTTON_IMPORTCSV_TEXT'), 'expimport.png');
        JToolBarHelper::back();
        $exporttable = '';

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

        parent::display($tpl);
    }

}
