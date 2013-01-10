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
require_once JPATH_COMPONENT . '/helpers/expfields.php';
require_once JPATH_COMPONENT . '/helpers/expparams.php';

class ExpAutosProControllerExpadd extends JControllerForm {

    public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true)) {
        return parent::getModel($name, $prefix, array('ignore_request' => false));
    }

    public function edit() {
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $addid = (int) JRequest::getInt('id', null, '', 'array');
        // Set the user id for the user to edit in the session.
        $app->setUserState('com_expautospro.edit.expadd.id', $addid);
        if (!(int) $user->get('id')) {
            //JError::raiseError(403, $model->getError());
            //return false;
        }
        // Get the model.
        $model = $this->getModel('Expadd', 'ExpAutosProModel');
        if (!$model->checkexpuser($user->id)) {
            $errors = $model->getError();
            $this->setMessage(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_INSERT_DEALERINFO_TEXT'));
            $this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=expuser&layout=edit', false));
            return false;
        }
        if ($user->get('id')) {
            $model->checkin($addid);
        }
        $this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=expadd&layout=edit', false));
    }

    public function save() {
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        
        $app = JFactory::getApplication();
        $model = $this->getModel('Expadd', 'ExpAutosProModel');
        $user = JFactory::getUser();
        $userId = (int) $user->get('id');
        $data = JRequest::getVar('jform', array(), 'post', 'array');
        if(!$user->id){
            return false;
        }
        if(!$data['id']){
            $expgrfields = $model->getExpgroupfields();
            if((int)$expgrfields->get('g_newmodelpublished') == 0 && $data['expyourmodel']){
                $expdatastate = (int)$expgrfields->get('g_newmodelpublished');
            }else{
                $expdatastate = (int)$expgrfields->get('g_published');
            }
            $data['state'] = $expdatastate;
        }

        $data['user'] = $userId;
        $data['displacement'] = $data['displacement'][1] . "." . $data['displacement'][2];
        $formvalid = $model->expvalid($data);
        if (!$formvalid) {
            $errors = $model->getError();
            $app->enqueueMessage(JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_DEALERINFO_ERROR_TEXT') . $model->getError(), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=expadd&layout=edit&id=' . (int)$data['id'], false));
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
            $app->setUserState('com_expautospro.edit.expadd.data', $data);

            // Redirect back to the edit screen.
            $dataId = (int) $app->getUserState('com_expautospro.edit.expadd.id');
            $this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=expadd&layout=edit&id=' . $data['id'], false));
            return false;
        }

        // Attempt to save the data.
        $return = $model->save($data);

        // Check in the profile.
        $userId = (int) $app->getUserState('com_expautospro.edit.expadd.id');
        if ($userId) {
            $model->checkin($dataId);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_expautospro.edit.expadd.id', null);

        // Redirect to the list screen.
        $expisnew = (int) JRequest::getInt('expisnew', 0);
        $this->setMessage(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_INFO_SAVE_SUCCESS_TEXT'));
        $this->setRedirect(JRoute::_(($redirect = $app->getUserState('com_expautospro.edit.expimages.redirect')) ? $redirect : 'index.php?option=com_expautospro&view=expimages&task=expimages.edit&expisnew='.$expisnew.'&id=' . (int) $return, false));

        // Flush the data from the session.
        $app->setUserState('com_expautospro.edit.expadd.data', null);
    }

}