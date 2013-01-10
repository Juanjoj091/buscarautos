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

class ExpAutosProControllerExpimages extends JControllerForm {

    public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true)) {
        return parent::getModel($name, $prefix, array('ignore_request' => false));
    }

    public function edit() {
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $addid = (int) JRequest::getInt('id', null, '', 'array');
        $expisnew = (int) JRequest::getInt('expisnew', 0);
        // Set the user id for the user to edit in the session.
        $app->setUserState('com_expautospro.edit.expimages.id', $addid);
        if (!(int) $user->get('id')) {
            //JError::raiseError(403, $model->getError());
            //return false;
        }

        $model = $this->getModel('Expimages', 'ExpAutosProModel');
        if(!$model->expaddvalid($addid,$user->id)){
            JError::raiseError(403, $model->getError());
            return false;
        }
        
        if ($user->get('id')) {
            $model->checkin($expuserId);
        }
        $this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=expimages&expisnew='.$expisnew.'&id='.(int)$addid, false));
    }

    public function save() {
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $addid = (int) JRequest::getInt('id', null, '', 'array');
        $expcatid = (int) JRequest::getInt('catid', null, '', 'array');
        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('Expimages', 'ExpAutosProModel');
        $user = JFactory::getUser();
        $userId = (int) $user->get('id');
        
        if(!$model->expaddvalid($expcatid,$user->id)){
            JError::raiseError(403, $model->getError());
            return false;
        }

        $data = JRequest::getVar('jform', array(), 'post', 'array');

        $form = $model->getForm();
        if (!$form) {
            JError::raiseError(500, $model->getError());
            return false;
        }

        $data = $model->validate($form, $data);

        if ($data === false) {
            $errors = $model->getErrors();
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if (JError::isError($errors[$i])) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            $app->setUserState('com_expautospro.edit.expimages.data', $data);

            $userId = (int) $app->getUserState('com_expautospro.edit.expimages.id');
            $this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=expimages&id=' . (int)$addid, false));
            return false;
        }

        $return = $model->save($data);

        $userId = (int) $app->getUserState('com_expautospro.edit.expimages.id');
        if ($userId) {
            $model->checkin($userId);
        }

        $app->setUserState('com_expautospro.edit.expimages.id', null);

        $this->setMessage(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_INFO_SAVE_SUCCESS_TEXT'));
        $this->setRedirect(JRoute::_(($redirect = $app->getUserState('com_expautospro.edit.expimages.redirect')) ? $redirect : 'index.php?option=com_expautospro&view=explist&userid='.(int)$user->id.'&id=' . (int) $return, false));

        $app->setUserState('com_expautospro.edit.expimages.data', null);
    }

}