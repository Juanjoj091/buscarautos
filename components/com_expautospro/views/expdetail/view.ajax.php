<?php

/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
require_once JPATH_COMPONENT . '/helpers/helper.php';
require_once JPATH_COMPONENT . '/helpers/expparams.php';
jimport('joomla.mail.helper');

class ExpautosproViewExpdetail extends JView {

    public function display($tpl = null) {
        $task = JRequest::getString('task', 0);
        if ($task == "expsellerpost") {
            $this->expsellerpost();
        }
        if ($task == "expdealerpost") {
            $this->expdealerpost();
        }
    }

    function expsellerpost() {
        if ($this->_name == (string) "expdetail") {
            $expid = JRequest::getInt('expid', 0);
            if ($expid) {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('a.year,a.imgmain,a.id');
                $query->from('#__expautos_admanager as a');
                $query->where('a.state=1');
                $query->where('a.id=' . (int) $expid);

                $query->select('mk.id AS make_id,mk.name AS make_name');
                $query->join('LEFT', '#__expautos_make AS mk ON mk.id = a.make');

                $query->select('md.id AS model_id,md.name AS model_name');
                $query->join('LEFT', '#__expautos_model AS md ON md.id = a.model');

                $query->select('user.email AS user_email, user.name AS user_name');
                $query->join('LEFT', '#__users AS user ON user.id = a.user');

                $query->select('expuser.emailstyle AS emailstyle');
                $query->join('LEFT', '#__expautos_expuser AS expuser ON expuser.userid = a.user');

                $db->setQuery($query);
                $result = $db->loadAssoc();

                //print_r($result);

                $expsender_name = JRequest::getString('expsender_name', 0);
                $expsender_email = JRequest::getString('expsender_email', 0);
                $expsender_phone = JRequest::getString('expsender_phone', 0);
                $expmessage = JRequest::getString('expmessage', 0);
                //$exprecipient = JRequest::getString('exprecipient', 0);
                if (!JMailHelper::isEmailAddress($expsender_email)) {
                    echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILFAILED_TEXT') . JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_SENDEREMAILNOVALID_TEXT');
                    return false;
                }
                if (!JMailHelper::isEmailAddress($result['user_email'])) {
                    echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILFAILED_TEXT') . JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_RECIPIENTEMAILNOVALID_TEXT');
                    return false;
                }
                if (empty($expmessage)) {
                    echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILFAILED_TEXT') . JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_INSERTMESSAGE_TEXT');
                    return false;
                }
                if (empty($expid)) {
                    echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILFAILED_TEXT') . JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILPROBLEMID_TEXT');
                    return false;
                }
                if (empty($expsender_name)) {
                    echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILFAILED_TEXT') . JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_INSERTNAME_TEXT');
                    return false;
                }
                $dealer_email = $result['user_email'];
                $subject = $expsender_name . " " . JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILSUBJECT_TEXT');
                
                $mode = $result['emailstyle'];
                $body = '';
                if($mode){
                    $expparams = ExpAutosProExpparams::getExpParams('config',1);
                    $mail_skin = $expparams->get('c_admanager_mail_detail_skin');
                    /* Add skin params and lang */
                    $params_file = JPATH_COMPONENT . '/skins/expmail/'.$mail_skin.'/parameters/params.php';
                    if (file_exists($params_file))
                        require_once $params_file;
                    ExpAutosProHelper::expskin_lang('expmail', $mail_skin);

                    
                    $text_username = $result['user_name'];
                    $text_id = $result['id'];
                    $text_makeid = $result['make_id'];
                    $text_makename = $result['make_name'];
                    $text_modelid = $result['model_id'];
                    $text_modelname = $result['model_name'];
                    $text_itemid = ExpAutosProExpparams::getExpLinkItemid();
                    $text_sendername = $expsender_name;
                    $text_expmessage = $expmessage;
                    $text_phone = $expsender_phone;
                    $text_ads = $result['make_name'] . " " . $result['model_name'] . " " . $result['year'];
                    if($result['imgmain']){
                        $linkimg = ExpAutosProExpparams::ImgUrlPatchThumbs().$result['imgmain'];
                    }else{
                        $linkimg = ExpAutosProExpparams::ImgUrlPatch() . 'assets/images/no_photo.jpg';
                    }
                    
                    /* data to skin */
                    $skin_top_text = JText::_(JText::sprintf('EXPAUTOSPRO_EXPMAIL_DEFAULT_ID', $text_id));
                    $skin_body_text = '';
                    $skin_body_text .= '
                    <h3 style="margin: 10px 0;font-family: inherit;font-weight: bold;line-height: 1;color: inherit; text-rendering: optimizelegibility;font-size: 24px;line-height: 40px;">' . JText::_(JText::sprintf('EXPAUTOSPRO_EXPMAIL_DEFAULT_HELLO', $text_username)) . '</h3>
                    <p>' . JText::_(JText::sprintf('EXPAUTOSPRO_EXPMAIL_DEFAULT_REVIEW_TEXT', $text_sendername)) . '</p>';
                    
                    if (!empty($expsender_phone)) {
                        $skin_body_text .= '<p>' . JText::_(JText::sprintf('EXPAUTOSPRO_EXPMAIL_DEFAULT_USERPHONE_TEXT', $text_sendername, $text_phone)) . '</p>';
                    }
                    $skin_body_text .= '<p>' . JText::_(JText::sprintf('EXPAUTOSPRO_EXPMAIL_DEFAULT_USEREMAIL_TEXT', $text_sendername, '<a href="mailto:'.$expsender_email.'">'.$expsender_email.'</a>')) . '</p>';
                    if (!empty($expmessage)) {
                        $skin_body_text .= '<p>' . JText::_(JText::sprintf('EXPAUTOSPRO_EXPMAIL_DEFAULT_USERMESSAGE_TEXT', $text_sendername, $text_expmessage)) . '</p>';
                    }
                    $skin_body_text .= '<p></p>
                    <p><a href="' . JURI::root() . 'index.php?option=com_expautospro&amp;view=expdetail&amp;id=' . $text_id . '&amp;catid=85&amp;makeid=' . $text_makeid . '&amp;modelid=' . $text_modelid . '&amp;Itemid=' . $text_itemid . '"><img src="' . $linkimg . '" border="" hspace="5" align="left" style="margin: 5px;" />' . $text_ads . '<br />' . JText::_('EXPAUTOSPRO_EXPMAIL_DEFAULT_READMORE_TEXT') . '</a></p>
                    <p></p>';
                    $skin_footer_text = JText::_("EXPAUTOSPRO_EXPMAIL_DEFAULT_BOTTOM_LINK_TEXT");
                    
                    /* include skin */
                    include (JPATH_COMPONENT . '/skins/expmail/'.$mail_skin.'/default.php');
                    $body = $mailhtml;
                }else{
                    $body .= JText::_("COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILBODY_TEXT") . "{$expsender_name}\n\n";
                    $body .= "\n\n";
                    if (!empty($expmessage)) {
                        $body .= $expsender_name . " " . JText::_("COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILBODYMESSAGE_TEXT") . "\n";
                        $body .= '"' . $expmessage . '"' . "\n\n";
                    }
                    if (!empty($expsender_phone)) {
                        $body .= JText::_("COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILBODYPHONE_TEXT") . $expsender_phone . "\n\n";
                    }
                    $body .= JText::_("COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILBODYMESSAGELINK_TEXT") . " " . JURI::root() . "index.php?option=com_expautospro&view=expdetail&id=" . (int) $expid . "\n\n";
                    $body .= $result['make_name'] . " " . $result['model_name'] . " " . $result['year'];
                }
                $body = JMailHelper::cleanBody($body);
                $sender_name = JMailHelper::cleanText($expsender_name);
                $subject = JMailHelper::cleanSubject($subject);
                $cc = null;
                $bcc = $expsender_email;
                
                    if (JUtility::sendMail($expsender_email, $expsender_name, $dealer_email, $subject, $body,$mode,$cc,$bcc) == true) {
                        echo "1";
                        //print_r($body);
                    } else {
                        echo JText::_("COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILFAILED_TEXT");
                        return false;
                    }
                //echo "test post!---" . $expsender_name . "---" . $expsender_email . "---" . $expsender_phone;
            }
        }
    }

    function expdealerpost() {
        if ($this->_name == (string) "expdetail") {
            $expuserid = JRequest::getInt('expuserid', 0);
            if ($expuserid) {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('u.email AS user_email, u.name AS user_name');
                $query->from('#__users as u');
                $query->select('expuser.emailstyle AS emailstyle');
                $query->join('LEFT', '#__expautos_expuser AS expuser ON expuser.userid = u.id');
                //$query->where('state=1');
                $query->where('u.id=' . (int) $expuserid);
                $db->setQuery($query);
                $result = $db->loadAssoc();

                $expsender_name = JRequest::getString('expsender_name', 0);
                $expsender_email = JRequest::getString('expsender_email', 0);
                $expsender_phone = JRequest::getString('expsender_phone', 0);
                $expmessage = JRequest::getString('expmessage', 0);
                $dealer_email = $result['user_email'];
                //$exprecipient = JRequest::getString('exprecipient', 0);
                if (!JMailHelper::isEmailAddress($expsender_email)) {
                    echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILFAILED_TEXT') . JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_SENDEREMAILNOVALID_TEXT');
                    return false;
                }
                if (!JMailHelper::isEmailAddress($dealer_email)) {
                    echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILFAILED_TEXT') . JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_RECIPIENTEMAILNOVALID_TEXT');
                    return false;
                }
                if (empty($expmessage)) {
                    echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILFAILED_TEXT') . JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_INSERTMESSAGE_TEXT');
                    return false;
                }
                if (empty($expsender_name)) {
                    echo JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILFAILED_TEXT') . JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_INSERTNAME_TEXT');
                    return false;
                }
                $mode = $result['emailstyle'];
                $body = '';
                if($mode){
                    $expparams = ExpAutosProExpparams::getExpParams('config',1);
                    $mail_skin = $expparams->get('c_admanager_mail_dealerdetail_skin');
                    /* Add skin params and lang */
                    $params_file = JPATH_COMPONENT . '/skins/expmail/'.$mail_skin.'/parameters/params.php';
                    if (file_exists($params_file))
                        require_once $params_file;
                    ExpAutosProHelper::expskin_lang('expmail', $mail_skin);

                    $text_username = $result['user_name'];
                    $text_sendername = $expsender_name;
                    $text_expmessage = $expmessage;
                    $text_phone = $expsender_phone;
                    
                    /* data to skin */
                    $skin_top_text = '';
                    
                    $skin_body_text = '';
                    $skin_body_text .= '
                    <h3 style="margin: 10px 0;font-family: inherit;font-weight: bold;line-height: 1;color: inherit; text-rendering: optimizelegibility;font-size: 24px;line-height: 40px;">' . JText::_(JText::sprintf('EXPAUTOSPRO_EXPMAIL_DEFAULT_HELLO', $text_username)) . '</h3>
                    <p>' . JText::_(JText::sprintf('EXPAUTOSPRO_EXPMAIL_DEFAULT_REVIEW_TEXT', $text_sendername)) . '</p>';
                    if (!empty($expmessage)) {
                        $skin_body_text .= '<p>' . JText::_(JText::sprintf('EXPAUTOSPRO_EXPMAIL_DEFAULT_USERMESSAGE_TEXT', $text_sendername, $text_expmessage)) . '</p>';
                    }
                    if (!empty($expsender_phone)) {
                        $skin_body_text .= '<p>' . JText::_(JText::sprintf('EXPAUTOSPRO_EXPMAIL_DEFAULT_USERPHONE_TEXT', $text_sendername, $text_phone)) . '</p>';
                    }
                    $skin_body_text .= '<p>' . JText::_(JText::sprintf('EXPAUTOSPRO_EXPMAIL_DEFAULT_USEREMAIL_TEXT', $text_sendername, '<a href="mailto:'.$expsender_email.'">'.$expsender_email.'</a>')) . '</p>';
                    $skin_body_text .= '<p></p>';
                    $skin_footer_text = JText::_("EXPAUTOSPRO_EXPMAIL_DEFAULT_BOTTOM_LINK_TEXT");
                    
                    /* include skin */
                    include (JPATH_COMPONENT . '/skins/expmail/'.$mail_skin.'/default.php');
                    $body = $mailhtml;
                }else{
                    $body .= JText::_("COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILBODY_TEXT") . "{$expsender_name}\n\n";
                    $body .= "\n\n";
                    if (!empty($expmessage)) {
                        $body .= $expsender_name . " " . JText::_("COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILBODYMESSAGE_TEXT") . "\n";
                        $body .= '"' . $expmessage . '"' . "\n\n";
                    }
                    if (!empty($expsender_phone)) {
                        $body .= JText::_("COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILBODYPHONE_TEXT") . $expsender_phone . "\n\n";
                    }
                }

                $subject = $expsender_name . " " . JText::_('COM_EXPAUTOSPRO_CP_DEALER_DETAIL_CONTACT_CONTACTSELLER_EMAILSUBJECT_TEXT') . JURI::root();
                $sender_name = JMailHelper::cleanText($expsender_name);
                $subject = JMailHelper::cleanSubject($subject);
                $body = JMailHelper::cleanBody($body);
                
                $cc = null;
                $bcc = $expsender_email;
                if (JUtility::sendMail($expsender_email, $expsender_name, $dealer_email, $subject, $body,$mode,$cc,$bcc) == true) {
                    echo "1";
                    // print_r($body);
                } else {
                    echo JText::_("COM_EXPAUTOSPRO_CP_DETAILPAGE_CONTACTSELLER_EMAILFAILED_TEXT");
                    return false;
                }
            }
        }
    }

}

?>