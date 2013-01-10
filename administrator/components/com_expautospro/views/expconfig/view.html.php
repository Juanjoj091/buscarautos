<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport( 'joomla.html.html.tabs' );
require_once JPATH_COMPONENT . '/helpers/helper.php';

class ExpAutosProViewExpconfig extends JView {

    protected $form;
    protected $item;
    protected $state;

    public function display($tpl = null) {
        $this->form = $this->get('Form');
        $this->item = 1;
        $this->state = $this->get('State');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar() {
        //JRequest::setVar('hidemainmenu', true);
        JHtml::stylesheet('administrator/components/com_expautospro/assets/expautospro.css');

        $user = JFactory::getUser();
        $userId = $user->get('id');
        $checkedOut = !(isset($this->item->checked_out) == 0 || isset($this->item->checked_out) == $userId);
        $canDo = ExpAutosProHelper::getActions($this->state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_EXPAUTOSPRO_CONFIGS_TITLE_TEXT'), 'expautosproexpconfig.png');

        if (!$checkedOut && ($canDo->get('core.edit') || $canDo->get('core.create'))) {
            JToolBarHelper::apply('expconfig.apply', 'JTOOLBAR_APPLY');
        }
        JToolBarHelper::divider();
        $bar = JToolBar::getInstance('toolbar');
        $bar->appendButton('Link', 'upload', 'COM_EXPAUTOSPRO_IMPORT_TEXT', 'index.php?option=com_expautospro&amp;view=import&amp;link=config');

        $bar = JToolBar::getInstance('toolbar');
        $bar->appendButton('Link', 'export', 'COM_EXPAUTOSPRO_EXPORT_TEXT', 'index.php?option=com_expautospro&amp;view=export&amp;link=config');
        JToolBarHelper::divider();
        JToolBarHelper::help('expconfig.html', $com = true);
    }

}
