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

class ExpautosproControllerExplist extends JControllerForm
{
	public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
        
        public function solid(){
            $user = JFactory::getUser();
            $userid = (int) $user->id;
            $id = (int) JRequest::getInt('id','0');
            $value = (int) JRequest::getInt('value','0');
            $field = 'solid';
            $model = $this->getModel('Explist', 'ExpAutosProModel');
            if (!$model->insertdata($id,$field,$value)) {
                $errors = $model->getError();
                JError::raiseWarning(500, JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PERMISSION_DENIED_TEXT'));
                $this->setRedirect(JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;userid='.(int)$userid, false));
                return false;
            }
            $this->setRedirect(JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;id='.(int)$id, false));
        }
        
        public function expreserved(){
            $user = JFactory::getUser();
            $userid = (int) $user->id;
            $id = (int) JRequest::getInt('id','0');
            $value = (int) JRequest::getInt('value','0');
            $field = 'expreserved';
            $model = $this->getModel('Explist', 'ExpAutosProModel');
            if (!$model->insertdata($id,$field,$value)) {
                $errors = $model->getError();
                JError::raiseWarning(500, JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PERMISSION_DENIED_TEXT'));
                $this->setRedirect(JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;userid='.(int)$userid, false));
                return false;
            }
            $this->setRedirect(JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;id='.(int)$id, false));
        }
        
        public function deletelink(){
            $user = JFactory::getUser();
            $userid = (int) $user->id;
            $id = (int) JRequest::getInt('id','0');
            $model = $this->getModel('Explist', 'ExpAutosProModel');
            if (!$model->delete_ads($id)) {
                $errors = $model->getError();
                JError::raiseWarning(500, JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PERMISSION_DENIED_TEXT'));
                $this->setRedirect(JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;userid='.(int)$userid, false));
                return false;
            }
            $this->setRedirect(JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;userid='.(int)$userid, false));
        }
        
        public function extend(){
            $user = JFactory::getUser();
            $userid = (int) $user->id;
            $id = (int) JRequest::getInt('id','0');
            $model = $this->getModel('Explist', 'ExpAutosProModel');
            if (!$model->extend_ads($id)) {
                $errors = $model->getError();
                JError::raiseWarning(500, JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PERMISSION_DENIED_TEXT'));
                $this->setRedirect(JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;userid='.(int)$userid, false));
                return false;
            }
            $this->setRedirect(JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;id='.(int)$id, false));
        }
}