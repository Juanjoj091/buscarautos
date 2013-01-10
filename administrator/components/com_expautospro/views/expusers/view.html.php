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

class ExpAutosProViewExpusers extends JView {

    protected $categories;
    protected $items;
    protected $pagination;
    protected $state;

    public function display($tpl = null) {
        // Initialise variables.
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $positions_option = array(
            JHtml::_('select.option', 'utop', JText::_('COM_EXPAUTOSPRO_ADMANAGERS_STATUS_TOP_TEXT')),
            JHtml::_('select.option', 'ucommercial', JText::_('COM_EXPAUTOSPRO_ADMANAGERS_STATUS_COMMERCIAL_TEXT')),
            JHtml::_('select.option', 'uspecial', JText::_('COM_EXPAUTOSPRO_ADMANAGERS_STATUS_SPECIAL_TEXT'))
        );
        $positions_options = JHtml::_('select.options', $positions_option, 'value', 'text', $this->state->get('filter.positions'));
        $this->buildpositions = $positions_options;

        $this->addToolbar();
        require_once JPATH_COMPONENT . '/models/fields/expgroups.php';
        parent::display($tpl);
    }

    protected function addToolbar() {
        require_once JPATH_COMPONENT . '/helpers/helper.php';
        JHtml::stylesheet('administrator/components/com_expautospro/assets/expautospro.css');

        $canDo = ExpAutosProHelper::getActions('expuser', $this->state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_EXPAUTOSPRO_USERS_TITLE_TEXT'), 'expautosproexpuser.png');
        if ($canDo->get('core.create')) {
            JToolBarHelper::addNew('expuser.add', 'JTOOLBAR_NEW');
        }

        if (($canDo->get('core.edit'))) {
            JToolBarHelper::editList('expuser.edit', 'JTOOLBAR_EDIT');
        }

        if ($canDo->get('core.edit.state')) {
            if ($this->state->get('filter.state') != 2) {
                JToolBarHelper::divider();
                JToolBarHelper::custom('expusers.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
                JToolBarHelper::custom('expusers.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
                JToolBarHelper::divider();
                JToolBarHelper::custom('expusers.utop_publish', 'publish.png', 'publish_f2.png', 'COM_EXPAUTOSPRO_BUTTON_TOP_TEXT', true);
                JToolBarHelper::custom('expusers.utop_unpublish', 'unpublish.png', 'unpublish_f2.png', 'COM_EXPAUTOSPRO_BUTTON_UNTOP_TEXT', true);
                JToolBarHelper::custom('expusers.ucommercial_publish', 'publish.png', 'publish_f2.png', 'COM_EXPAUTOSPRO_BUTTON_COMMERCIAL_TEXT', true);
                JToolBarHelper::custom('expusers.ucommercial_unpublish', 'unpublish.png', 'unpublish_f2.png', 'COM_EXPAUTOSPRO_BUTTON_UNCOMMERCIAL_TEXT', true);
                JToolBarHelper::custom('expusers.uspecial_publish', 'publish.png', 'publish_f2.png', 'COM_EXPAUTOSPRO_BUTTON_SPECIAL_TEXT', true);
                JToolBarHelper::custom('expusers.uspecial_unpublish', 'unpublish.png', 'unpublish_f2.png', 'COM_EXPAUTOSPRO_BUTTON_UNSPECIAL_TEXT', true);
            }

            if ($this->state->get('filter.state') != -1) {
                JToolBarHelper::divider();
                if ($this->state->get('filter.state') != 2) {
                    JToolBarHelper::archiveList('expusers.archive', 'JTOOLBAR_ARCHIVE');
                } else if ($this->state->get('filter.state') == 2) {
                    JToolBarHelper::unarchiveList('expusers.publish', 'JTOOLBAR_UNARCHIVE');
                }
            }
        }
        /*
          if ($canDo->get('core.edit.state')) {
          JToolBarHelper::custom('expusers.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
          }
         */
        if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete')) {
            JToolBarHelper::deleteList('', 'expusers.delete', 'JTOOLBAR_EMPTY_TRASH');
            JToolBarHelper::divider();
        } else if ($canDo->get('core.edit.state')) {
            JToolBarHelper::trash('expusers.trash', 'JTOOLBAR_TRASH');
            JToolBarHelper::divider();
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_expautospro');
            JToolBarHelper::divider();
        }
        /*
        $bar = JToolBar::getInstance('toolbar');
        $bar->appendButton('Link', 'upload', 'COM_EXPAUTOSPRO_BUTTON_IMPORTCSV_TEXT', 'index.php?option=com_expautospro&amp;view=import&amp;link=expuser');

        $bar = JToolBar::getInstance('toolbar');
        $bar->appendButton('Link', 'export', 'COM_EXPAUTOSPRO_BUTTON_EXPORTCSV_TEXT', 'index.php?option=com_expautospro&amp;view=export&amp;link=expuser');
        JToolBarHelper::divider();
         * 
         */
        
        JToolBarHelper::help('expusers.html', $com = true);
    }

}
