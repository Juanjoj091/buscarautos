<?php

/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2012  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);
define('JPATH_BASE', dirname(__FILE__) . '/../..');

/* Required Files */
require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';
require_once( JPATH_BASE . '/components/com_expautospro/helpers/expparams.php' );
require_once( JPATH_BASE . '/components/com_expautospro/helpers/expfields.php' );

$lang = JFactory::getLanguage();
$lang->load('mod_expautospro_paypal', JPATH_BASE, $lang->getDefault(), false, false);

// Instantiate the application.
$app = JFactory::getApplication('site');
$app->initialise();
$app->render();
$cache = JFactory::getCache('com_expautospro', '');
$cache->clean('com_expautospro');

$config = ExpAutosProExpparams::getExpParams('config', 1);
$modules = JModuleHelper::getModule('expautospro_paypal');


$params = new JRegistry();
$params->loadJSON($modules->params);

//echo JText::sprintf(JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PAGE_MAIL_ADS_BODY_TEXT'),'14','55');

$exppay_status='Completed';

$url_pay = $params->get('form_action');
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
foreach ($_POST as $key => $value) {
    $value = urlencode(stripslashes($value));
    $req .= "&$key=$value";
}

$curl = curl_init($url_pay);
curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $req);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
$response = curl_exec($curl);
curl_close($curl);

// assign posted variables to local variables
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];
$payment_date = $_POST['payment_date'];
$payment_custom = $_POST['custom'];
$expcustom = explode(",", $payment_custom);
// $expcustom[0] = what is
// $expcustom[1] = id
// $expcustom[2] = user id
$payment_date = date('Y-m-d H:i:s', strtotime($payment_date));


        if (strcmp ($response, "VERIFIED") == 0) {
// check the payment_status is Completed
// check that txn_id has not been previously processed
// check that receiver_email is your Primary PayPal email
// check that payment_amount/payment_currency are correct
// process payment
                $expnotice = '';
                $expvalid = true;
                if($expcustom[2] && $expcustom[0] && $expcustom[1]){
                    $listusergroups = implode(',', JAccess::getGroupsByUser($expcustom[2]));
                    $listusergroupid = ExpAutosProExpparams::getExpgroupid($listusergroups);
                    $listgroupparams = ExpAutosProExpparams::getExpParams('userlevel', $listusergroupid);
                    
                    if($expcustom[0] != 5 && !ExpAutosProFields::expaddcheck($expcustom[1],$expcustom[2])){
                        $expnotice .= JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PAGE_PROBLEM_CHECKUSER_TEXT');
                        $expvalid = false;
                    }
                    if(!$listgroupparams){
                        if($payment_currency != $listgroupparams->get('p_pcurrency')){
                            $expnotice .= JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PAGE_PROBLEM_CURRENCY_TEXT');
                            $expvalid = false;
                        }
                        if($expcustom[0] == 1){
                            $payment_price = $listgroupparams->get('p_ppricead');
                            if($payment_amount != $payment_price){
                                $expnotice .= JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PAGE_PROBLEM_PRICEAD_TEXT');
                                $expvalid = false;
                            }
                        }
                        if($expcustom[0] == 2){
                            $payment_price = $listgroupparams->get('p_ppricetop');
                            if($payment_amount != $payment_price){
                                $expnotice .= JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PAGE_PROBLEM_PRICETOP_TEXT');
                                $expvalid = false;
                            }
                        }
                        if($expcustom[0] == 3){
                            $payment_price = $listgroupparams->get('p_ppricecommercial');
                            if($payment_amount != $payment_price){
                                $expnotice .= JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PAGE_PROBLEM_PRICECOMM_TEXT');
                                $expvalid = false;
                            }
                        }
                        if($expcustom[0] == 4){
                            $payment_price = $listgroupparams->get('p_ppricespecial');
                            if($payment_amount != $payment_price){
                                $expnotice .= JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PAGE_PROBLEM_PRICESPECIAL_TEXT');
                                $expvalid = false;
                            }
                        }
                        if($expcustom[0] == 5){
                            $payment_price = $listgroupparams->get('p_plevel');
                            if($payment_amount != $payment_price){
                                $expnotice .= JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PAGE_PROBLEM_PRICELEVEL_TEXT');
                                $expvalid = false;
                            }
                        }
                        $expnotice .= JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PAGE_PROBLEM_USERID_TEXT');
                        $expvalid = false; 
                    }

                }else{
                    $expnotice .= JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PAGE_PROBLEM_CUSTOMDATA_TEXT');
                    $expvalid = false;
                }

                    $db = JFactory::getDbo();
                    $obj = new stdClass();
                    $obj->adid = $item_number;
                    $obj->payname = $item_name;
                    $obj->payval = $payer_email;
                    $obj->paysum = $payment_amount;
                    $obj->status = $payment_status;
                    $obj->paydate = $payment_date;
                    $obj->payuser = $expcustom[2];
                    $obj->paynotice = $expnotice;
                    $obj->payid = $expcustom[1];
                    $obj->paysysval = $expcustom[0];
                    if($expvalid)
                    $obj->state = 1;
                    $db->insertObject('#__expautos_payment', $obj);
                    //Send Mail
                    $subject = JText::sprintf(JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PAGE_MAIL_SUBJECT_PAYMENT_TEXT'),$item_number,$payer_email);
                    $body = JText::sprintf(JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PAGE_MAIL_BODY_PAYMENT_TEXT'),$expcustom[2],$expcustom[0],$expcustom[1],$item_number,$item_name,$payment_amount,$payment_status,$expnotice);
                    exppayment_mail($subject,$body);
                    
            if ($payment_status == $exppay_status) {       
                    if($expvalid){
                        if($expcustom[0] > 0 && $expcustom[0] <= 4)
                            expadmanager($expcustom[0],$expcustom[1]);
                        if($expcustom[0] == 5)
                            expusermanager($expcustom[2],$expcustom[1]);
                    }
            }
        } else if (strcmp($response, "INVALID") == 0) {
// log for manual investigation
            $expnotice = JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PAGE_PROBLEM_INVALID_TEXT');
            $db = JFactory::getDbo();
            $obj = new stdClass();
            $obj->paynotice = $expnotice;
            $db->insertObject('#__expautos_payment', $obj);
        }
    


function expadmanager($whatis,$id){
    $db = JFactory::getDbo();
    $obj = new stdClass();
    $obj->id    	= $id;
    if($whatis == 1){
        $obj->state = 1;
    }elseif($whatis == 2){
        $obj->ftop = 1;
    }elseif($whatis == 3){
        $obj->fcommercial = 1;
    }elseif($whatis == 4){
        $obj->special = 1;
    }
    $db->updateObject('#__expautos_admanager', $obj, 'id');
    $subject = JText::sprintf(JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PAGE_MAIL_ADS_SUBJECT_TEXT'),$id,$whatis);
    $body = JText::sprintf(JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PAGE_MAIL_ADS_BODY_TEXT'),$id,$whatis);
    exppayment_mail($subject,$body);
}

function expusermanager($userid,$levelid){
    $db = JFactory::getDbo();
    $obj = new stdClass();
    $obj->user_id    	= $userid;
    $obj->group_id    	= $levelid;
    $db->updateObject('#__user_usergroup_map', $obj, 'user_id');
    $subject = JText::sprintf(JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PAGE_MAIL_USER_LEVEL_SUBJECT_TEXT'),$userid,$levelid);
    $body = JText::sprintf(JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PAGE_MAIL_USER_LEVEL_BODY_TEXT'),$userid,$levelid);
    exppayment_mail($subject,$body);	
}

function exppayment_mail($subject,$body){
    jimport('joomla.mail.helper');
    $config = JFactory::getConfig();
    $expconfig = ExpAutosProExpparams::getExpParams('config', 1);
    $expadmin_email = $expconfig->get('c_general_adminemail');
    $email_fromname	= $config->get('fromname');
    $email_mailfrom	= $config->get('mailfrom');
    $data_sitename	= $config->get('sitename');
    if (!JMailHelper::isEmailAddress($expadmin_email)) {
        return false;
    }
    $recipients = $expadmin_email;
    $subject = JMailHelper::cleanSubject($subject);
    $body = JMailHelper::cleanBody($body);
    if (JUtility::sendMail($email_mailfrom, $email_fromname, $recipients, $subject, $body) == true) {
        echo JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_SUCCESSFULL_TEXT");
    }
}
