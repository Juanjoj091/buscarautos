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
require_once JPATH_COMPONENT.'/helpers/expimages.php';

class ExpAutosProViewExpadmanagers extends JView {

    protected $categories;
    protected $items;
    protected $pagination;
    protected $state;

    public function display($tpl = null) {
        // Initialise variables.
        $this->categories = $this->get('CategoryOrders');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $build_options = array(
            JHtml::_('select.option', 'c', JText::_('JGLOBAL_BATCH_COPY')),
            JHtml::_('select.option', 'm', JText::_('JGLOBAL_BATCH_MOVE'))
        );
        $build_radiolist = JHTML::_('select.radiolist', $build_options, 'make_action', '', 'value', 'text', 'c');
        $this->buildradiolist = $build_radiolist;

        $positions_option = array(
            JHtml::_('select.option', 'ftop', JText::_('COM_EXPAUTOSPRO_ADMANAGERS_STATUS_TOP_TEXT')),
            JHtml::_('select.option', 'fcommercial', JText::_('COM_EXPAUTOSPRO_ADMANAGERS_STATUS_COMMERCIAL_TEXT')),
            JHtml::_('select.option', 'special', JText::_('COM_EXPAUTOSPRO_ADMANAGERS_STATUS_SPECIAL_TEXT')),
            JHtml::_('select.option', 'solid', JText::_('COM_EXPAUTOSPRO_ADMANAGERS_STATUS_SOLID_TEXT')),
            JHtml::_('select.option', 'expreserved', JText::_('COM_EXPAUTOSPRO_ADMANAGERS_STATUS_RESERVED_TEXT'))
        );
        $positions_options = JHtml::_('select.options', $positions_option, 'value', 'text', $this->state->get('filter.positions'));
        $this->buildpositions = $positions_options;

        $this->addToolbar();
        require_once JPATH_COMPONENT . '/models/fields/juser.php';
        require_once JPATH_COMPONENT . '/models/fields/categor.php';
        require_once JPATH_COMPONENT . '/models/fields/expdatabase.php';
        parent::display($tpl);
    }

    protected function addToolbar() {
        require_once JPATH_COMPONENT . '/helpers/helper.php';
        JHtml::stylesheet('administrator/components/com_expautospro/assets/expautospro.css');

        $canDo = ExpAutosProHelper::getActions('expadmanager', $this->state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TITLE'), 'expautosproadmanager.png');
        if ($canDo->get('core.create')) {
            JToolBarHelper::addNew('expadmanager.add', 'JTOOLBAR_NEW');
        }

        if (($canDo->get('core.edit'))) {
            JToolBarHelper::editList('expadmanager.edit', 'JTOOLBAR_EDIT');
        }

        if ($canDo->get('core.edit.state')) {
            if ($this->state->get('filter.state') != 2) {
                JToolBarHelper::divider();
                JToolBarHelper::custom('expadmanagers.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
                JToolBarHelper::custom('expadmanagers.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
                JToolBarHelper::divider();
                JToolBarHelper::custom('expadmanagers.ftop_publish', 'publish.png', 'publish_f2.png', 'COM_EXPAUTOSPRO_BUTTON_TOP_TEXT', true);
                JToolBarHelper::custom('expadmanagers.ftop_unpublish', 'unpublish.png', 'unpublish_f2.png', 'COM_EXPAUTOSPRO_BUTTON_UNTOP_TEXT', true);
                JToolBarHelper::custom('expadmanagers.fcommercial_publish', 'publish.png', 'publish_f2.png', 'COM_EXPAUTOSPRO_BUTTON_COMMERCIAL_TEXT', true);
                JToolBarHelper::custom('expadmanagers.fcommercial_unpublish', 'unpublish.png', 'unpublish_f2.png', 'COM_EXPAUTOSPRO_BUTTON_UNCOMMERCIAL_TEXT', true);
                JToolBarHelper::custom('expadmanagers.special_publish', 'publish.png', 'publish_f2.png', 'COM_EXPAUTOSPRO_BUTTON_SPECIAL_TEXT', true);
                JToolBarHelper::custom('expadmanagers.special_unpublish', 'unpublish.png', 'unpublish_f2.png', 'COM_EXPAUTOSPRO_BUTTON_UNSPECIAL_TEXT', true);
                JToolBarHelper::custom('expadmanagers.solid_publish', 'publish.png', 'publish_f2.png', 'COM_EXPAUTOSPRO_BUTTON_SOLID_TEXT', true);
                JToolBarHelper::custom('expadmanagers.solid_unpublish', 'unpublish.png', 'unpublish_f2.png', 'COM_EXPAUTOSPRO_BUTTON_UNSOLID_TEXT', true);
                //JToolBarHelper::custom('expadmanagers.expreserved_publish', 'publish.png', 'publish_f2.png', 'COM_EXPAUTOSPRO_ADMANAGERS_STATUS_RESERVED_TEXT', true);
                //JToolBarHelper::custom('expadmanagers.expreserved_unpublish', 'unpublish.png', 'unpublish_f2.png', 'COM_EXPAUTOSPRO_ADMANAGERS_STATUS_UNRESERVED_TEXT', true);
            }

            if ($this->state->get('filter.state') != -1) {
                JToolBarHelper::divider();
                if ($this->state->get('filter.state') != 2) {
                    JToolBarHelper::archiveList('expadmanagers.archive', 'JTOOLBAR_ARCHIVE');
                } else if ($this->state->get('filter.state') == 2) {
                    JToolBarHelper::unarchiveList('expadmanagers.publish', 'JTOOLBAR_UNARCHIVE');
                }
            }
        }
        /*
          if ($canDo->get('core.edit.state')) {
          JToolBarHelper::custom('expadmanagers.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
          }
         */
        if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete')) {
            JToolBarHelper::deleteList('', 'expadmanagers.delete', 'JTOOLBAR_EMPTY_TRASH');
            JToolBarHelper::divider();
        } else if ($canDo->get('core.edit.state')) {
            JToolBarHelper::trash('expadmanagers.trash', 'JTOOLBAR_TRASH');
            JToolBarHelper::divider();
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_expautospro');
            JToolBarHelper::divider();
        }
        /*
        $bar = JToolBar::getInstance('toolbar');
        $bar->appendButton('Link', 'upload', 'COM_EXPAUTOSPRO_BUTTON_IMPORTCSV_TEXT', 'index.php?option=com_expautospro&amp;view=import&amp;link=admanager');

        $bar = JToolBar::getInstance('toolbar');
        $bar->appendButton('Link', 'export', 'COM_EXPAUTOSPRO_BUTTON_EXPORTCSV_TEXT', 'index.php?option=com_expautospro&amp;view=export&amp;link=admanager');
        JToolBarHelper::divider();
         * 
         */

        JToolBarHelper::help('expadmanagers.html', $com = true);
    }

}
