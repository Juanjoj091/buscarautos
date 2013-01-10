<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
require_once JPATH_COMPONENT . '/helpers/expparams.php';

class ExpautosproControllerExppayment extends JControllerForm
{
	public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
        
        public function ftop(){
            $user = JFactory::getUser();
            $userid = (int) $user->id;
            $id = (int) JRequest::getInt('id','0');
            //$field = (string) JRequest::getVar('task','0');
            $field = 'ftop';
            $model = $this->getModel('Exppayment', 'ExpAutosProModel');
            if (!$model->checkspec($id,$field)) {
            $errors = $model->getError();
            JError::raiseWarning(500, JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PERMISSION_DENIED_TEXT'));
            $this->setRedirect(JRoute::_('index.php?option=com_expautospro&amp;view=exppayment', false));
            return false;
            }
            $this->setRedirect(JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;userid='.(int)$userid, false));
        }
        
        public function fcommercial(){
            $user = JFactory::getUser();
            $userid = (int) $user->id;
            $id = (int) JRequest::getInt('id','0');
            //$field = (string) JRequest::getVar('task','0');
            $field = 'fcommercial';
            $model = $this->getModel('Exppayment', 'ExpAutosProModel');
            if (!$model->checkspec($id,$field)) {
            $errors = $model->getError();
            JError::raiseWarning(500, JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PERMISSION_DENIED_TEXT'));
            $this->setRedirect(JRoute::_('index.php?option=com_expautospro&amp;view=exppayment', false));
            return false;
            }
            $this->setRedirect(JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;id='.(int)$id, false));
        }
        
        public function special(){
            $user = JFactory::getUser();
            $userid = (int) $user->id;
            $id = (int) JRequest::getInt('id','0');
            //$field = (string) JRequest::getVar('task','0');
            $field = 'special';
            $model = $this->getModel('Exppayment', 'ExpAutosProModel');
            if (!$model->checkspec($id,$field)) {
            $errors = $model->getError();
            JError::raiseWarning(500, JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PERMISSION_DENIED_TEXT'));
            $this->setRedirect(JRoute::_('index.php?option=com_expautospro&amp;view=exppayment', false));
            return false;
            }
            $this->setRedirect(JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;userid='.(int)$userid, false));
        }
        
}