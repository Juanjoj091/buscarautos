<?php

/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class ExpAutosProViewExpSource extends JView {

    protected $form;
    protected $item;
    protected $source;
    protected $state;

    public function display($tpl = null) {
        // Initialiase variables.
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->source = $this->get('Source');
        $this->state = $this->get('State');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        //$this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar() {
        JRequest::setVar('hidemainmenu', true);
        JHtml::stylesheet('administrator/components/com_expautospro/assets/expautospro.css');

        $user = JFactory::getUser();
        $userId = $user->get('id');
        $checkedOut = !(isset($this->item->checked_out) == 0 || isset($this->item->checked_out) == $userId);

        JToolBarHelper::title(JText::_('COM_EXPAUTOSPRO_SOURCE_EDIT_TITLE'), 'expautospro.png');

        JToolBarHelper::apply('expsource.apply', 'JTOOLBAR_APPLY');
        JToolBarHelper::save('expsource.save', 'JTOOLBAR_SAVE');


        if (empty($this->item->id)) {
            JToolBarHelper::cancel('expsource.cancel', 'JTOOLBAR_CANCEL');
        } else {
            JToolBarHelper::cancel('expsource.cancel', 'JTOOLBAR_CLOSE');
        }

        JToolBarHelper::divider();
        //JToolBarHelper::help('expsource.html', $com = true);
    }

}
