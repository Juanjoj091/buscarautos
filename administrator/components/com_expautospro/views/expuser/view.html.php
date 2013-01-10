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
require_once JPATH_COMPONENT . '/helpers/expimages.php';

class ExpAutosProViewExpuser extends JView {

    protected $form;
    protected $item;
    protected $state;

    public function display($tpl = null) {
        $expparams = ExpAutosProImages::getExpParams('config',1);
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');
        $this->expparams = $expparams;

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }
                //$show_googlemap = $expparams->get('c_admanager_useradd_showgooglemaps');
		$document = JFactory::getDocument();
		$document->addScript(JURI::root() . "/administrator/components/com_expautospro/assets/js/mooexpchained.js");

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
        $canDo = ExpAutosProHelper::getActions($this->state->get('filter.category_id'));

        JToolBarHelper::title($isNew ? JText::_('COM_EXPAUTOSPRO_USER_NEW_TITLE') : JText::_('COM_EXPAUTOSPRO_USER_EDIT_TITLE'), 'expautosproexpuser.png');

        if (!$checkedOut && ($canDo->get('core.edit') || $canDo->get('core.create'))) {
            JToolBarHelper::apply('expuser.apply', 'JTOOLBAR_APPLY');
            JToolBarHelper::save('expuser.save', 'JTOOLBAR_SAVE');
        }

        if (empty($this->item->id)) {
            JToolBarHelper::cancel('expuser.cancel', 'JTOOLBAR_CANCEL');
        } else {
            JToolBarHelper::cancel('expuser.cancel', 'JTOOLBAR_CLOSE');
        }

        JToolBarHelper::divider();
        JToolBarHelper::help('expuser.html', $com = true);
    }

}
