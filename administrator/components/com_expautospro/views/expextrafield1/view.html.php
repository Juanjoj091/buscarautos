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

class ExpAutosProViewExpextrafield1 extends JView {

    protected $form;
    protected $item;
    protected $state;

    public function display($tpl = null) {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar() {
        JRequest::setVar('hidemainmenu', true);
        JHtml::stylesheet('administrator/components/com_expautospro/assets/expautospro.css');

        $user = JFactory::getUser();
        $userId = $user->get('id');
        $isNew = ($this->item->id == 0);
        $checkedOut = !(isset($this->item->checked_out) == 0 || isset($this->item->checked_out) == $userId);
        $canDo = ExpAutosProHelper::getActions($this->state->get('filter.make_id'));

        JToolBarHelper::title($isNew ? JText::_('COM_EXPAUTOSPRO_EXTRAFIELD1_NEW_TITLE') : JText::_('COM_EXPAUTOSPRO_EXTRAFIELD1_EDIT_TITLE'), 'expautosproextrafield1.png');

        if (!$checkedOut && ($canDo->get('core.edit') || $canDo->get('core.create'))) {
            JToolBarHelper::apply('expextrafield1.apply', 'JTOOLBAR_APPLY');
            JToolBarHelper::save('expextrafield1.save', 'JTOOLBAR_SAVE');

            if ($canDo->get('core.create')) {
                JToolBarHelper::custom('expextrafield1.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
            }
        }

        if (!$isNew && $canDo->get('core.create')) {
            JToolBarHelper::custom('expextrafield1.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
        }

        if (empty($this->item->id)) {
            JToolBarHelper::cancel('expextrafield1.cancel', 'JTOOLBAR_CANCEL');
        } else {
            JToolBarHelper::cancel('expextrafield1.cancel', 'JTOOLBAR_CLOSE');
        }

        JToolBarHelper::divider();
        JToolBarHelper::help('expextrafield1.html', $com = true);
    }

}
