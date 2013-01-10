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
defined('_JEXEC') or die('Restricted access');

// Import Joomla! libraries
jimport('joomla.application.component.model');

class ExpAutosProModelExpAutosPro extends JModel {
    public function __construct() {
		parent::__construct();
    }
    
    public function send_license($license_number){
        jimport('joomla.mail.helper');
        $config	= JFactory::getConfig();
        $subject = JText::_('COM_EXPAUTOSPRO_FP_LICENSENUMBER_SEND_SUBJECT_TEXT');
        $body = JText::_("Site :").JURI::root()."\n\n";
        $body .= "\n\n";
        $body .= JText::_("License number :").$license_number."\n\n";
        $body .= "\n\n";
        $email_fromname	= $config->get('fromname');
        $email_mailfrom	= $config->get('mailfrom');
        $data_sitename	= $config->get('sitename');
        $recipients = "license@feellove.eu";
        $subject = JMailHelper::cleanSubject($subject);
        $body = JMailHelper::cleanBody($body);
        if (JUtility::sendMail($email_mailfrom, $email_fromname, $recipients, $subject, $body) == true) {
            //echo JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_SUCCESSFULL_TEXT");
            return true;
        } else {
            echo JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_PROBLEM_SEND_TEXT");
            return false;
        }
    }
}
?>
