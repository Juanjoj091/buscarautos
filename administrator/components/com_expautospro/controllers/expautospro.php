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

class ExpAutosProControllerExpautospro extends JControllerForm {

        public function sendlicense(){
            $license_number = JRequest::getString('number',0);
            $model = $this->getModel('Expautospro');
            $this->setRedirect('index.php?option=com_expautospro');
            if ($model->send_license($license_number)) {
			$this->setMessage(JText::_('COM_EXPAUTOSPRO_FP_LICENSENUMBER_SUCCESSFULLSEND_TEXT'));
			return true;
		}else {
			$this->setMessage(JText::_(JText::sprintf('COM_EXPAUTOSPRO_FP_LICENSENUMBER_NOSEND_TEXT', $model->getError())));
			return false;
		}
        }
}