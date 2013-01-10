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
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class ExpAutosProController extends JController {

	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/helper.php';
                
		ExpAutosProHelper::addSubmenu(JRequest::getCmd('view', 'expautospro'));

		$view	= JRequest::getCmd('view', 'expautospro');
		$layout = JRequest::getCmd('layout', 'default');
		$id		= JRequest::getInt('id');

		if ($view == 'category' && $layout == 'edit' && !$this->checkEditId('com_expautospro.edit.category', $id)) {

			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=categories', false));

			return false;
		}
		else if ($view == 'make' && $layout == 'edit' && !$this->checkEditId('com_expautospro.edit.make', $id)) {

			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=make', false));

			return false;
		}
		else if ($view == 'model' && $layout == 'edit' && !$this->checkEditId('com_expautospro.edit.model', $id)) {

			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=model', false));

			return false;
		}
		else if ($view == 'expcit' && $layout == 'edit' && !$this->checkEditId('com_expautospro.edit.expcit', $id)) {

			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=expcits', false));

			return false;
		}

		parent::display();

		return $this;
	}
}
?>
