<?php

/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');
require_once JPATH_COMPONENT . '/helpers/helper.php';

class ExpAutosProControllerExpsource extends JControllerAdmin {

    protected $text_prefix = 'COM_EXPAUTOSPRO_SOURCE';
    
    public function __construct($config = array()) {
        parent::__construct($config);

        $this->registerTask('apply', 'save');
    }

    protected function allowEdit() {
        return JFactory::getUser()->authorise('core.edit', 'com_expautospro');
    }

    protected function allowSave() {
        return $this->allowEdit();
    }

    public function getModel($name = 'ExpSource', $prefix = 'ExpAutosProModel', $config = array()) {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

    public function display($cachable = false, $urlparams = false) {
        $this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=expsource&layout=edit', false));
    }

    public function edit() {
        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel();

        // Access check.
        if (!$this->allowEdit()) {
            return JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'));
        }

        $app->setUserState($context . '.data', null);
        $this->setRedirect('index.php?option=com_expautospro&view=expsource&layout=edit');
        return true;
    }

    public function cancel() {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel();
        $context = 'com_expautospro.edit.expsource';

        $app->setUserState($context . '.data', null);
        $this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=expconfig&id=1', false));
    }

    public function save() {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $app = JFactory::getApplication();
        $data = JRequest::getVar('jform', array(), 'post', 'array');
        $context = 'com_expautospro.edit.expsource';
        $task = $this->getTask();
        $model = $this->getModel();

        
          // Access check.
          if (!$this->allowSave()) {
          return JError::raiseWarning(403, JText::_('JERROR_SAVE_NOT_PERMITTED'));
          }

        // Validate the posted data.
        $form = $model->getForm();

        if (!$form) {
            JError::raiseError(500, $model->getError());
            return false;
        }
        $data = $model->validate($form, $data);

        // Check for validation errors.
        if ($data === false) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            // Save the data in the session.
            $app->setUserState($context . '.data', $data);

            // Redirect back to the edit screen.
            $this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=expsource&layout=edit&tmpl=component&skin=' . $model->getState('fileskin'), false));
            return false;
        }

        // Attempt to save the data.
        if (!$model->save($data)) {
            // Save the data in the session.
            $app->setUserState($context . '.data', $data);

            // Redirect back to the edit screen.
            $this->setMessage(JText::sprintf('JERROR_SAVE_FAILED', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=expsource&layout=edit&tmpl=component&skin=' . $data['fileskin'], false));
            return false;
        }

        $this->setMessage(JText::_('COM_EXPAUTOSPRO_SOURCE_FILE_SAVE_SUCCESS'));

        // Redirect the user and adjust session state based on the chosen task.
        switch ($task) {
            case 'apply':
                // Reset the record data in the session.
                $app->setUserState($context . '.data', null);

                // Redirect back to the edit screen.
                $this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=expsource&layout=edit&tmpl=component&refresh=1&skin=' . $data['fileskin'], false));
                break;

            default:
                // Clear the record id and data from the session.
                $app->setUserState($context . '.data', null);
                // Redirect to the list screen.
                $this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=expconfig&id=1', false));
                break;
        }
    }

}