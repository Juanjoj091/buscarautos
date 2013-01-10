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

jimport('joomla.application.component.controllerform');

class ExpAutosProControllerExpextrafield1 extends JControllerForm
{

	protected function allowAdd($data = array())
	{
		// Initialise variables.
		$user		= JFactory::getUser();
		$categoryId	= JArrayHelper::getValue($data, 'catid', JRequest::getInt('filter_category_id'), 'int');
		$allow		= null;

		if ($categoryId) {
			$allow	= $user->authorise('core.create', $this->option.'.expextrafield1.'.$categoryId);
		}

		if ($allow === null) {
			return parent::allowAdd($data);
		} else {
			return $allow;
		}
	}

	protected function allowEdit($data = array(), $key = 'id')
	{
		$recordId	= (int) isset($data[$key]) ? $data[$key] : 0;
		$categoryId = 0;

		if ($recordId) {
			$categoryId = (int) $this->getModel()->getItem($recordId)->catid;
		}

		if ($categoryId) {
			return JFactory::getUser()->authorise('core.edit', $this->option.'.expextrafield1.'.$categoryId);
		} else {
			return parent::allowEdit($data, $key);
		}
	}

	// Method to run copy/move opterations.
	public function make_build_contr()
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app	= JFactory::getApplication();
		$model	= $this->getModel('Expextrafield1');
		$makeaction		= JRequest::getString('make_action',0);
		$buildcat		= JRequest::getInt('build_cat',0);
		$cid			= JRequest::getVar('cid', array(), 'post', 'array');

		// Preset the redirect
		$this->setRedirect('index.php?option=com_expautospro&view=expextrafield1s');

		// Attempt to run the batch operation.
		if ($model->makes_build($buildcat, $cid, $makeaction)) {
			$this->setMessage(JText::_('COM_EXPAUTOSPRO_COPYMOVE_SUCCESS'));
			return true;
		}
		else {
			$this->setMessage(JText::_(JText::sprintf('COM_EXPAUTOSPRO_ERROR_COPYMOVE_FAILED', $model->getError())));
			return false;
		}
	}
}