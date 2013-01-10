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

jimport('joomla.application.component.controllerform');
require_once JPATH_COMPONENT . '/helpers/expfields.php';

class ExpAutosProControllerExpuser extends JControllerForm {

    public function edit() {
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $expuserId = (int) ExpAutosProFields::getExpuserid($user->id);
        // Set the user id for the user to edit in the session.
        $app->setUserState('com_expautospro.edit.expuser.id', $expuserId);
        // Get the model.
        $model = $this->getModel('Expuser', 'ExpAutosProModel');
        if (!(int) $user->get('id')) {
            JError::raiseError(403, $model->getError());
            return false;
        }
        if ($user->get('id')) {
            $model->checkin($expuserId);
        }
        $this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=expuser&layout=edit', false));
    }

    public function save() {
        // Check for request forgeries.
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('Expuser', 'ExpAutosProModel');
        $user = JFactory::getUser();
        $userId = (int) $user->get('id');

        // Get the user data.
        $data = JRequest::getVar('jform', array(), 'post', 'array');

        // Force the ID to this user.
        $data['userid'] = $userId;

        $formvalid = $model->expvalid($data);
        if (!$formvalid) {
            $errors = $model->getError();
            $app->enqueueMessage(JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_DEALERINFO_ERROR_TEXT') . $model->getError(), 'warning');

            $this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=expuser&layout=edit&id=' . $data['id'], false));
            return false;
        }


        // Validate the posted data.
        $form = $model->getForm();
        if (!$form) {
            JError::raiseError(500, $model->getError());
            return false;
        }

        // Validate the posted data.
        $data = $model->validate($form, $data);
        // Check for errors.
        if ($data === false) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if (JError::isError($errors[$i])) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            // Save the data in the session.
            $app->setUserState('com_expautospro.edit.expuser.data', $data);
            // Redirect back to the edit screen.
            $userId = (int) $app->getUserState('com_expautospro.edit.expuser.id');
            $this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=expuser&layout=edit&id=' . $userId, false));
            return false;
        }

        // Attempt to save the data.
        $return = $model->save($data);

        // Check in the profile.
        $userId = (int) $app->getUserState('com_expautospro.edit.expuser.id');
        if ($userId) {
            $model->checkin($userId);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_expautospro.edit.expuser.id', null);

        // Redirect to the list screen.
        $this->setMessage(JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_DEALERINFO_INFO_SAVE_SUCCESS'));
        if ($this->getReturnPage()) {
            $this->setRedirect(JRoute::_($this->getReturnPage()));
        } else {
            $this->setRedirect(JRoute::_(($redirect = $app->getUserState('com_expautospro.edit.expuser.redirect')) ? $redirect : 'index.php?option=com_expautospro&view=expuser&task=expuser.edit&id=' . (int) $return, false));
        }
        // Flush the data from the session.
        $app->setUserState('com_expautospro.edit.expuser.data', null);
    }

    protected function getReturnPage() {
        $return = JRequest::getVar('return', null, '', 'base64');

        if ($return && JUri::isInternal(base64_decode($return))) {
            return base64_decode($return);
        } else {
            return false;
        }
    }

}