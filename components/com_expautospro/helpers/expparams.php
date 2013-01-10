<?php

/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

defined('_JEXEC') or die;
require_once JPATH_SITE . '/components/com_expautospro/helpers/helper.php';

class ExpAutosProExpparams {

    public static function getExpParams($database, $id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('params');
        $query->from('#__expautos_' . (string) $database);
        $query->where('id = ' . (int) $id);
        $db->setQuery($query);

        $result = $db->loadResult();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        $value_params = new JRegistry();
        $value_params->loadJSON($result);
        return $value_params;

        /* For example
          $expparams = ExpAutosProExpparams::getExpParams('config',1);
          $expparams->get('c_user_req_logopatch');
         */
    }

    public static function getCatParams($id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('params');
        $query->from('#__categories');
        $query->where('id = ' . (int) $id);
        $db->setQuery($query);

        $result = $db->loadResult();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        $value_params = new JRegistry();
        $value_params->loadJSON($result);
        return $value_params;

        /* For example
          $expparams = ExpAutosProImages::getExpParams('config',1);
          $expparams->get('c_user_req_logopatch');
         */
    }

    public static function getExpShortList($expval, $expid) {
        $expparams = ExpAutosProExpparams::getExpParams('config', 1);
        $lifetime = time() + 365 * 24 * 60 * 60;
        $config = JFactory::getConfig();
        $cookie_domain = $config->get('cookie_domain');
        $cookie_path = "/";
        $expgetcookie = JRequest::getVar('expshortlist', null, $hash = 'COOKIE');
        $cookiesarray = array_filter(explode(",", $expgetcookie));
        if ($expval == 1) {
            $stack = $cookiesarray;
            array_push($stack, $expid);
            $checkarray = array_unique($stack);
            $expsetcookies = implode(",", $checkarray);
            $expgetcookie = JRequest::setVar('expshortlist', '', $hash = 'COOKIE');
            setcookie('expshortlist', $expsetcookies, $lifetime, $cookie_path, $cookie_domain);
            //print_r($expsetcookies);
            ExpAutosProExpparams::createAdd($expsetcookies);
        } elseif ($expval == 2) {
            unset($cookiesarray[array_search($expid, $cookiesarray)]);
            $stack = $cookiesarray;
            $checkarray = array_unique($stack);
            $expsetcookies = implode(",", $checkarray);
            setcookie('expshortlist', $expsetcookies, $lifetime, $cookie_path, $cookie_domain);
            //print_r($expsetcookies);
            ExpAutosProExpparams::createAdd($expsetcookies);
        } elseif ($expval == 3) {
            $expid = explode(",", $expid);
            $expid = array_diff($cookiesarray, $expid);
            $expsetcookies = implode(",", $expid);
            setcookie('expshortlist', $expsetcookies, $lifetime, $cookie_path, $cookie_domain);
            ExpAutosProExpparams::createAdd($expsetcookies);
        } else {
            setcookie('expshortlist', null, $lifetime, $cookie_path, $cookie_domain);
            echo "";
        }
    }

    public static function createAdd($expid) {
        $html = '';
        if ($expid) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('IF( a.bprice != "",a.bprice,a.price ) price, a.id AS adid,a.catid AS catid, a.imgmain AS img_name');
            $query->from('#__expautos_admanager AS a');
            /* Makes */
            $query->select('mk.name AS make_name');
            $query->join('LEFT', '#__expautos_make AS mk ON mk.id = a.make');
            //$query->where('mk.state=1');

            /* Models */
            $query->select('md.name AS model_name');
            $query->join('LEFT', '#__expautos_model AS md ON md.id = a.model');

            // Join over the images.
            /* old version 3.5.1
              $query->select('img.name AS img_name');
              $query->join('LEFT', '#__expautos_images AS img ON a.id = img.catid AND img.ordering = 1');
             */

            $query->where('a.id IN (' . $expid . ')');
            $query->where('a.state = 1');

            // Get the options.
            $db->setQuery($query);

            $options = $db->loadObjectList();
            // Check for a database error.
            if ($db->getErrorNum()) {
                JError::raiseWarning(500, $db->getErrorMsg());
            }
            $expconfig = ExpAutosProExpparams::getExpParams('config', 1);
            $thumbsize = $expconfig->get('c_images_thumbsize_width') + 10;
            $itemid = ExpAutosProExpparams::getExpLinkItemid();
            $app = JFactory::getApplication();
            $exp_sef = $app->getCfg('sef');
            if ($exp_sef) {
                $action_link = JRoute::_('index.php?option=com_expautospro&amp;view=expcompare&amp;Itemid=' . $itemid);
            } else {
                $action_link = JRoute::_('index.php');
            }
            if ($expconfig->get('c_general_shortlisttitle')) {
                $html .='<h3 class="expshortl_list_h3">
                    <span class="backh">
                        <span class="backh2">
                            <span class="backh3">' . JText::_('COM_EXPAUTOSPRO_CP_SHORTLIST_MODULE_H3_TEXT') . '</span>
                        </span>
                    </span>
                </h3>';
            }
            $html .='<form name="exporderform" action="' . $action_link . '" method="GET">';
            if (!$exp_sef) {
                $html .='
            <input type="hidden" name="option" value="com_expautospro" />
            <input type="hidden" name="view" value="expcompare" />';
            }
            $html .='';
            $html .='<ul class="thumbnails expshort_ul">';
            for ($i = 0, $n = count($options); $i < $n; $i++) {
                if ($options[$i]->img_name) {
                    $imglink = ExpAutosProExpparams::ImgUrlPatchThumbs() . $options[$i]->img_name;
                } else {
                    $imglink = ExpAutosProExpparams::ImgUrlPatch() . 'assets/images/no_photo.jpg';
                }
                $text_all = $options[$i]->make_name . ' ' . $options[$i]->model_name;
                $text_str = ExpAutosProExpparams::getExpcutStr($text_all, $expconfig->get('c_general_shortmaxlenght'));
                $price = ExpAutosProExpparams::price_formatdata($options[$i]->price, 1);
                $link = JRoute::_("index.php?option=com_expautospro&amp;view=expdetail&amp;catid=" . $options[$i]->catid . "&amp;id=" . $options[$i]->adid . "&amp;Itemid=" . $itemid);
                $html .='<li style="width:' . $thumbsize . 'px" class="expautos_shortlist_images">';
                $html .='<span class="thumbnail">';
                $html .='<a href="' . $link . '">';
                $html .='<img src="' . $imglink . '" />';
                $html .='<div class="expshortlist_text label label-info">';
                $html .='<div class="expautos_shortlist_text"><center>' . $text_str . '</center></div>';
                $html .='<div class="expautos_shortlist_price"><center>' . $price . '</center></div>';
                $html .='</div>';
                $html .='</a>';
                $html .='
                 <div class="expautos_compare_checkbox">
                     <input type="checkbox" name="compare[]" value="' . $options[$i]->adid . '"/>
                     <span>' . JText::_('COM_EXPAUTOSPRO_COMPARE_ADDTOCOMPARE_TEXT') . '</span>
                 </div>';
                $html .='</span>';
                $html .='</li>';
            }
            $html .='</ul>';
            $html .='';
            $html .='<input type="hidden" name="Itemid" value="' . $itemid . '" />';
            $html .='
                <div id="expmodshortlist_clearlink" style="clear:both;">
                    <input type="submit" class="button expcompare btn btn-primary" value="' . JText::_('COM_EXPAUTOSPRO_COMPARE_BUTTON_TEXT') . '"/>
                    <input type="submit" class="button expclear btn btn-danger" onClick="javascript:expall_checkbox(3,0,0);return false;" value="' . JText::_('COM_EXPAUTOSPRO_CP_SHORTLIST_MODULE_LINKCLEAR_TEXT') . '"/>
                </div>';
            $html .='</form>';
        }
        echo $html;
    }

    public static function expuser($expuser) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id');
        $query->from('#__expautos_expuser');
        $query->where('userid = ' . (int) $expuser);
        $db->setQuery($query);
        $expuserid = $db->loadResult();

        return $expuserid;
    }

    public static function expsend_mail($userid, $id, $isNew) {
        jimport('joomla.mail.helper');
        $config = JFactory::getConfig();
        $expconfig = ExpAutosProExpparams::getExpParams('config', 1);
        $text_itemid = ExpAutosProExpparams::getExpLinkItemid();
        $expadmin_email = $expconfig->get('c_general_adminemail');
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('a.year AS year,a.state AS expstate,a.imgmain,a.id');
        $query->from('#__expautos_admanager as a');
        $query->where('a.id=' . (int) $id);

        $query->select('mk.id AS make_id,mk.name AS make_name');
        $query->join('LEFT', '#__expautos_make AS mk ON mk.id = a.make');

        $query->select('md.id AS model_id,md.name AS model_name');
        $query->join('LEFT', '#__expautos_model AS md ON md.id = a.model');

        $query->select('user.name AS user_name,user.email AS user_email');
        $query->join('LEFT', '#__users AS user ON user.id = a.user');

        $query->select('expuser.emailstyle AS emailstyle');
        $query->join('LEFT', '#__expautos_expuser AS expuser ON expuser.userid = a.user');

        $db->setQuery($query);
        $result = $db->loadAssoc();
        if ($isNew) {
            $text_ad = JText::_('COM_EXPAUTOSPRO_CP_EXPADD_MAIL_ADDED_TEXT');
        } else {
            $text_ad = JText::_('COM_EXPAUTOSPRO_CP_EXPADD_MAIL_EDIT_TEXT');
        }

        $subject = JText::_('COM_EXPAUTOSPRO_CP_EXPADD_MAIL_SUBJECT_TEXT') . $text_ad;

        $body = '';
        $mode = $result['emailstyle'];
        if ($mode) {
            $mail_skin = $expconfig->get('c_admanager_mail_add_skin');
            /* Add skin params and lang */
            $params_file = JPATH_COMPONENT . '/skins/expmail/' . $mail_skin . '/parameters/params.php';
            if (file_exists($params_file))
                require_once $params_file;
            ExpAutosProHelper::expskin_lang('expmail', $mail_skin);

            $text_username = $result['user_name'];
            $text_id = $id;
            $text_makeid = $result['make_id'];
            $text_makename = $result['make_name'];
            $text_modelid = $result['model_id'];
            $text_modelname = $result['model_name'];
            $text_ads = $result['make_name'] . " " . $result['model_name'] . " " . $result['year'];
            if ($result['imgmain']) {
                $linkimg = ExpAutosProExpparams::ImgUrlPatchMiddle() . $result['imgmain'];
            } else {
                $linkimg = ExpAutosProExpparams::ImgUrlPatch() . 'assets/images/no_photo.jpg';
            }
            if ($result['expstate']) {
                $status = JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_BODY_STATUS_PUBLISHED_TEXT");
            } else {
                $status = JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_BODY_STATUS_UNPUBLISHED_TEXT");
            }

            /* data to skin */
            $skin_top_text = JText::_(JText::sprintf('EXPAUTOSPRO_EXPMAIL_DEFAULT_ID', $text_id));
            $skin_body_text = '
                    <h3 style="margin: 10px 0;font-family: inherit;font-weight: bold;line-height: 1;color: inherit; text-rendering: optimizelegibility;font-size: 24px;line-height: 40px;">' . JText::_(JText::sprintf('EXPAUTOSPRO_EXPMAIL_DEFAULT_HELLO', $text_username)) . '</h3>
                    <p><h4>' . JText::_('COM_EXPAUTOSPRO_CP_EXPADD_MAIL_SUBJECT_TEXT') . $text_ad . '</h4></p>
                    <p></p>
                    <p><a href="' . JURI::root() . 'index.php?option=com_expautospro&amp;view=expdetail&amp;id=' . $text_id . '&amp;catid=85&amp;makeid=' . $text_makeid . '&amp;modelid=' . $text_modelid . '&amp;Itemid=' . $text_itemid . '"><img src="' . $linkimg . '" border="" hspace="5" align="middle" style="margin: 5px auto; display: block;" /></a></p>
                    <p style="text-align: center;"><a href="' . JURI::root() . 'index.php?option=com_expautospro&amp;view=expdetail&amp;id=' . $text_id . '&amp;catid=85&amp;makeid=' . $text_makeid . '&amp;modelid=' . $text_modelid . '&amp;Itemid=' . $text_itemid . '">' . $text_ads . '</a></p>
                    <p style="text-align: right;"><a href="' . JURI::root() . 'index.php?option=com_expautospro&amp;view=expdetail&amp;id=' . $text_id . '&amp;catid=85&amp;makeid=' . $text_makeid . '&amp;modelid=' . $text_modelid . '&amp;Itemid=' . $text_itemid . '">' . JText::_('EXPAUTOSPRO_EXPMAIL_DEFAULT_READMORE_TEXT') . '</a></p>
                    <p>' . JText::_('EXPAUTOSPRO_EXPMAIL_DEFAULT_STATUS_TEXT') . '<b>' . $status . '</b></p>
                    <p></p>';
            $skin_footer_text = JText::_("EXPAUTOSPRO_EXPMAIL_DEFAULT_BOTTOM_LINK_TEXT");

            /* include skin */
            include (JPATH_COMPONENT . '/skins/expmail/' . $mail_skin . '/default.php');

            $body = $mailhtml;
        } else {
            $body .= JText::sprintf(JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_BODY_DEAR_TEXT"), $result['user_name']) . "\n\n";
            $body .= "\n\n";
            $body .= JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_BODY_YOUR_ADD_TEXT") . $result['make_name'] . " " . $result['model_name'] . " " . $result['year'] . " " . JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_BODY_ADDED_TEXT") . $text_ad;
            $body .= "\n\n";
            $body .= JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_BODY_LINKAD_TEXT") . JURI::root() . "index.php?option=com_expautospro&view=explist&id=" . (int) $id;
            $body .= "\n\n";
            $body .= JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_BODY_STATUS_TEXT");
            if ($result['expstate']) {
                $body .= JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_BODY_STATUS_PUBLISHED_TEXT");
            } else {
                $body .= JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_BODY_STATUS_UNPUBLISHED_TEXT");
            }
            $body = JMailHelper::cleanBody($body);
        }
        $email_fromname = $config->get('fromname');
        $email_mailfrom = $config->get('mailfrom');
        $data_sitename = $config->get('sitename');
        if (!JMailHelper::isEmailAddress($result['user_email'])) {
            return false;
        }
        if (!JMailHelper::isEmailAddress($expadmin_email)) {
            return false;
        }
        $useremail = $result['user_email'];
        $recipients = array($useremail, $expadmin_email);
        $subject = JMailHelper::cleanSubject($subject);

        $cc = null;
        $bcc = null;
        if (JUtility::sendMail($email_mailfrom, $email_fromname, $recipients, $subject, $body, $mode, $cc, $bcc) == true) {
            echo JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_SUCCESSFULL_TEXT");
        } else {
            echo JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_PROBLEM_SEND_TEXT");
            return false;
        }
    }

    public static function expexpires_mail() {
        jimport('joomla.mail.helper');
        $config = JFactory::getConfig();
        $expconfig = ExpAutosProExpparams::getExpParams('config', 1);
        $text_itemid = ExpAutosProExpparams::getExpLinkItemid();
        $expadmin_email = $expconfig->get('c_general_adminemail');
        $expsend_email = $expconfig->get('c_general_sendexpiriesemail');
        if ($expsend_email) {
            $date_plus = JFactory::getDate('+' . $expconfig->get('c_general_expiriesdays') . ' day ' . date('Y-m-d', strtotime('now')))->toMySQL();
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('a.id AS id,a.year AS year,a.state AS expstate,a.imgmain');
            $query->from('#__expautos_admanager as a');
            $query->where('a.expirdate <= ' . $db->Quote($date_plus));
            $query->where('a.expemail !=1 ');

            $query->select('mk.id AS make_id,mk.name AS make_name');
            $query->join('LEFT', '#__expautos_make AS mk ON mk.id = a.make');

            $query->select('md.id AS model_id,md.name AS model_name');
            $query->join('LEFT', '#__expautos_model AS md ON md.id = a.model');

            $query->select('user.name AS user_name,user.email AS user_email');
            $query->join('LEFT', '#__users AS user ON user.id = a.user');

            $query->select('expuser.emailstyle AS emailstyle');
            $query->join('LEFT', '#__expautos_expuser AS expuser ON expuser.userid = a.user');

            $db->setQuery($query);
            $result = $db->loadObjectList();
            foreach ($result as $value) {
                $body = '';
                $mode = $value->emailstyle;
                if ($mode) {
                    $mail_skin = $expconfig->get('c_admanager_mail_expiries_skin');
                    /* Add skin params and lang */
                    $params_file = JPATH_COMPONENT . '/skins/expmail/' . $mail_skin . '/parameters/params.php';
                    if (file_exists($params_file))
                        require_once $params_file;
                    ExpAutosProHelper::expskin_lang('expmail', $mail_skin);
                    $text_username = $value->user_name;
                    $text_id = $value->id;
                    $text_makeid = $value->make_id;
                    $text_makename = $value->make_name;
                    $text_modelid = $value->model_id;
                    $text_modelname = $value->model_name;
                    $text_ads = $text_makename . " " . $text_modelname . " " . $value->year;
                    if ($value->imgmain) {
                        $linkimg = ExpAutosProExpparams::ImgUrlPatchMiddle() . $value->imgmain;
                    } else {
                        $linkimg = ExpAutosProExpparams::ImgUrlPatch() . 'assets/images/no_photo.jpg';
                    }

                    $status = JText::_("EXPAUTOSPRO_EXPMAIL_DEFAULT_STATUS_EXPIRIES_TEXT");

                    /* data to skin */
                    $skin_top_text = JText::_(JText::sprintf('EXPAUTOSPRO_EXPMAIL_DEFAULT_ID', $text_id));
                    $skin_body_text = '
                    <h3 style="margin: 10px 0;font-family: inherit;font-weight: bold;line-height: 1;color: inherit; text-rendering: optimizelegibility;font-size: 24px;line-height: 40px;">' . JText::_(JText::sprintf('EXPAUTOSPRO_EXPMAIL_DEFAULT_HELLO', $text_username)) . '</h3>
                    <p><h4>' . JText::_('COM_EXPAUTOSPRO_CP_EXPADD_MAIL_SUBJECT_TEXT') . JText::_("EXPAUTOSPRO_EXPMAIL_DEFAULT_STATUS_EXPIRIES_TEXT") . '</h4></p>
                    <p></p>
                    <p><a href="' . JURI::root() . 'index.php?option=com_expautospro&amp;view=expdetail&amp;id=' . $text_id . '&amp;catid=85&amp;makeid=' . $text_makeid . '&amp;modelid=' . $text_modelid . '&amp;Itemid=' . $text_itemid . '"><img src="' . $linkimg . '" border="" hspace="5" align="middle" style="margin: 5px auto; display: block;" /></a></p>
                    <p style="text-align: center;"><a href="' . JURI::root() . 'index.php?option=com_expautospro&amp;view=expdetail&amp;id=' . $text_id . '&amp;catid=85&amp;makeid=' . $text_makeid . '&amp;modelid=' . $text_modelid . '&amp;Itemid=' . $text_itemid . '">' . $text_ads . '</a></p>
                    <p style="text-align: right;"><a href="' . JURI::root() . 'index.php?option=com_expautospro&amp;view=expdetail&amp;id=' . $text_id . '&amp;catid=85&amp;makeid=' . $text_makeid . '&amp;modelid=' . $text_modelid . '&amp;Itemid=' . $text_itemid . '">' . JText::_('EXPAUTOSPRO_EXPMAIL_DEFAULT_READMORE_TEXT') . '</a></p>
                    <p>' . JText::_('EXPAUTOSPRO_EXPMAIL_DEFAULT_STATUS_TEXT') . '<b>' . $status . '</b></p>
                    <p></p>';
                    $skin_footer_text = JText::_("EXPAUTOSPRO_EXPMAIL_DEFAULT_BOTTOM_LINK_TEXT");

                    /* include skin */
                    include (JPATH_COMPONENT . '/skins/expmail/' . $mail_skin . '/default.php');

                    $body = $mailhtml;
                } else {
                    $body .= JText::sprintf(JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_BODY_DEAR_TEXT"), $value->user_name) . "\n\n";
                    $body .= "\n\n";
                    $body .= JText::_("COM_EXPAUTOSPRO_CP_EXPIRIED_BODY_YOUR_ADD_TEXT") . $value->make_name . " " . $value->model_name . " " . $value->year . " " . JText::_("COM_EXPAUTOSPRO_CP_EXPIRIED_BODY_ADDED_TEXT");
                    $body .= "\n\n";
                    $body .= JText::_("COM_EXPAUTOSPRO_CP_EXPIRIED_BODY_LINKAD_TEXT") . JURI::root() . "index.php?option=com_expautospro&view=explist&id=" . (int) $value->id;
                }

                $subject = JText::_('COM_EXPAUTOSPRO_CP_EXPIRIED_SUBJECT_TEXT');
                $email_fromname = $config->get('fromname');
                $email_mailfrom = $config->get('mailfrom');
                $data_sitename = $config->get('sitename');
                if (!JMailHelper::isEmailAddress($value->user_email)) {
                    return false;
                }
                if (!JMailHelper::isEmailAddress($expadmin_email)) {
                    return false;
                }
                $useremail = $value->user_email;
                $recipients = array($useremail, $expadmin_email);
                $subject = JMailHelper::cleanSubject($subject);
                $body = JMailHelper::cleanBody($body);
                $cc = null;
                $bcc = null;
                if (JUtility::sendMail($email_mailfrom, $email_fromname, $recipients, $subject, $body, $mode, $cc, $bcc) == true) {
                    //echo JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_SUCCESSFULL_TEXT");
                    $query = $db->getQuery(true);
                    $query->update('`#__expautos_admanager`');
                    $query->set('`expemail` = 1');
                    $query->where('id = ' . (int) $value->id);
                    $db->setQuery((string) $query);
                    $db->query();
                    // Check for a database error.
                    if ($db->getErrorNum()) {
                        JError::raiseWarning(500, $db->getErrorMsg());
                        return false;
                    }
                } else {
                    echo JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_PROBLEM_SEND_TEXT");
                    return false;
                }
            }
        }
    }

    public static function delete_bydate() {
        $datenow = JFactory::getDate(date('Y-m-d', strtotime('now')))->toMySQL();
        $expconfig = ExpAutosProExpparams::getExpParams('config', 1);
        $expuselife = $expconfig->get('c_general_uselifeduration');
        if ($expuselife) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('id');
            $query->from('#__expautos_admanager');
            $query->where('expirdate <= ' . $db->Quote($datenow));
            $db->setQuery($query);
            $explifead = $db->loadResultArray();
            if ($db->getErrorNum()) {
                JError::raiseWarning(500, $db->getErrorMsg());
            }
            foreach ($explifead as $value) {
                ExpAutosProExpparams::delete_ads((int) $value);
            }
        }
    }

    public static function changestatus_ads($expstatus) {
        $datenow = JFactory::getDate(date('Y-m-d', strtotime('now')))->toMySQL();
        $expconfig = ExpAutosProExpparams::getExpParams('config', 1);
        $expuselife = $expconfig->get('c_general_uselifeduration');
        if ($expuselife) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('id');
            $query->from('#__expautos_admanager');
            $query->where('expirdate <= ' . $db->Quote($datenow));
            $db->setQuery($query);
            $explifead = $db->loadResultArray();
            if ($db->getErrorNum()) {
                JError::raiseWarning(500, $db->getErrorMsg());
            }
            foreach ($explifead as $value) {
                ExpAutosProExpparams::changestatus((int) $value, $expstatus);
            }
        }
    }

    public static function delete_ads($id) {
        $ad_params = ExpAutosProExpparams::getExpParams('admanager', $id);
        $params_file = $ad_params->get('expfile');
        if ($params_file) {
            $link_file = ExpAutosProExpparams::FilesAbsPath() . $params_file;
            unlink($link_file);
        }
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('name');
        $query->from('#__expautos_images');
        $query->where('catid = ' . (int) $id);
        $db->setQuery($query);
        $expimages = $db->loadResultArray();
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }
        foreach ($expimages as $img) {
            $filepathtmb = ExpAutosProExpparams::ImgAbsPathThumbs() . $img;
            $filepathmiddle = ExpAutosProExpparams::ImgAbsPathMiddle() . $img;
            $filepathbig = ExpAutosProExpparams::ImgAbsPathBig() . $img;
            $unlinktmb = ExpAutosProExpparams::UnlinkImg($filepathtmb);
            $unlinkmiddle = ExpAutosProExpparams::UnlinkImg($filepathmiddle);
            $unlinkbig = ExpAutosProExpparams::UnlinkImg($filepathbig);
        }
        $query = $db->getQuery(true);
        $query->delete();
        $query->from('#__expautos_images');
        $query->where('catid = ' . (int) $id);
        $db->setQuery((string) $query);
        if (!$db->Query()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }
        
        jimport('joomla.mail.helper');
        $config = JFactory::getConfig();
        $expconfig = ExpAutosProExpparams::getExpParams('config', 1);
        $text_itemid = ExpAutosProExpparams::getExpLinkItemid();
        $expadmin_email = $expconfig->get('c_general_adminemail');
        $query = $db->getQuery(true);
        $query->select('a.year AS year,a.state AS expstate,a.id');
        $query->from('#__expautos_admanager as a');
        $query->where('a.id=' . (int) $id);

        $query->select('mk.name AS make_name');
        $query->join('LEFT', '#__expautos_make AS mk ON mk.id = a.make');

        $query->select('md.name AS model_name');
        $query->join('LEFT', '#__expautos_model AS md ON md.id = a.model');

        $query->select('user.name AS user_name,user.email AS user_email');
        $query->join('LEFT', '#__users AS user ON user.id = a.user');

        $query->select('expuser.emailstyle AS emailstyle');
        $query->join('LEFT', '#__expautos_expuser AS expuser ON expuser.userid = a.user');

        $db->setQuery($query);
        $result = $db->loadAssoc();
        $text_ad = JText::_('COM_EXPAUTOSPRO_CP_EXPADD_MAIL_STATUS_REMOVED_TEXT');

        $subject = JText::_('COM_EXPAUTOSPRO_CP_EXPADD_MAIL_SUBJECT_TEXT') . $text_ad;

        $body = '';
        $mode = $result['emailstyle'];
        if ($mode) {
            $mail_skin = $expconfig->get('c_admanager_mail_delete_skin');
            /* Add skin params and lang */
            $params_file = JPATH_COMPONENT . '/skins/expmail/' . $mail_skin . '/parameters/params.php';
            if (file_exists($params_file))
                require_once $params_file;
            ExpAutosProHelper::expskin_lang('expmail', $mail_skin);

            $text_username = $result['user_name'];
            $text_id = $id;
            $text_makename = $result['make_name'];
            $text_modelname = $result['model_name'];
            $text_ads = $result['make_name'] . " " . $result['model_name'] . " " . $result['year'];
            $status = JText::_("EXPAUTOSPRO_EXPMAIL_DEFAULT_STATUS_REMOVED_TEXT");

            /* data to skin */
            $skin_top_text = JText::_(JText::sprintf('EXPAUTOSPRO_EXPMAIL_DEFAULT_ID', $text_id));
            $skin_body_text = '
                    <h3 style="margin: 10px 0;font-family: inherit;font-weight: bold;line-height: 1;color: inherit; text-rendering: optimizelegibility;font-size: 24px;line-height: 40px;">' . JText::_(JText::sprintf('EXPAUTOSPRO_EXPMAIL_DEFAULT_HELLO', $text_username)) . '</h3>
                    <p><h4>' . JText::_('COM_EXPAUTOSPRO_CP_EXPADD_MAIL_SUBJECT_TEXT') . $text_ad . '</h4></p>
                    <p></p>
                    <p style="text-align: center;">' . $text_ads . '</p>
                    <p>' . JText::_('EXPAUTOSPRO_EXPMAIL_DEFAULT_STATUS_TEXT') . '<b>' . $status . '</b></p>
                    <p></p>
                    <p></p>';
            $skin_footer_text = JText::_("EXPAUTOSPRO_EXPMAIL_DEFAULT_BOTTOM_LINK_TEXT");;

            /* include skin */
            include (JPATH_COMPONENT . '/skins/expmail/' . $mail_skin . '/default.php');

            $body = $mailhtml;
        } else {
            $body .= JText::sprintf(JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_BODY_DEAR_TEXT"), $result['user_name']) . "\n\n";
            $body .= "\n\n";
            $body .= JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_BODY_YOUR_ADD_TEXT") . $result['make_name'] . " " . $result['model_name'] . " " . $result['year'] . " " . JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_BODY_ADDED_TEXT") . $text_ad;
            $body .= "\n\n";
            $body .= JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_BODY_STATUS_TEXT");
            $body .= JText::_("EXPAUTOSPRO_EXPMAIL_DEFAULT_STATUS_REMOVED_TEXT");
            $body = JMailHelper::cleanBody($body);
        }
        $email_fromname = $config->get('fromname');
        $email_mailfrom = $config->get('mailfrom');
        $data_sitename = $config->get('sitename');
        if (!JMailHelper::isEmailAddress($result['user_email'])) {
            return false;
        }
        if (!JMailHelper::isEmailAddress($expadmin_email)) {
            return false;
        }
        $useremail = $result['user_email'];
        $recipients = array($useremail, $expadmin_email);
        $subject = JMailHelper::cleanSubject($subject);

        $cc = null;
        $bcc = null;
        if (JUtility::sendMail($email_mailfrom, $email_fromname, $recipients, $subject, $body, $mode, $cc, $bcc) == true) {
            echo JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_SUCCESSFULL_TEXT");
        } else {
            echo JText::_("COM_EXPAUTOSPRO_CP_EXPADD_MAIL_PROBLEM_SEND_TEXT");
            return false;
        }
        
        $query = $db->getQuery(true);
        $query->delete();
        $query->from('#__expautos_admanager');
        $query->where('id = ' . (int) $id);
        $db->setQuery((string) $query);
        if (!$db->Query()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }
        return true;
        
    }

    public static function changestatus($id, $expst = '-2') {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update('`#__expautos_admanager`');
        $query->set("`state` = " . $expst);
        $query->where('id = ' . (int) $id);
        $db->setQuery((string) $query);
        $db->query();
        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
            return false;
        }
        return true;
    }

    public static function getExpgroupid($usergroups) {
        $expgroupid = '';
        if ($usergroups) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('MAX(id)');
            $query->from('#__expautos_userlevel');
            $query->where('userlevel IN (' . $usergroups . ')');
            $query->where('state = 1');
            // Get the options. 
            $db->setQuery($query);

            $expgroupid = $db->loadResult();

            // Check for a database error. 
            if ($db->getErrorNum()) {
                JError::raiseWarning(500, $db->getErrorMsg());
            }
        }

        return $expgroupid;
    }

    public static function getExpgroupname($usergroups) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('MAX(userlvl.userlevel)');
        $query->from('#__expautos_userlevel as userlvl');
        $query->where('userlvl.userlevel IN (' . $usergroups . ')');
        // Get the options.
        $db->setQuery($query);

        $expgroupid = $db->loadResult();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }
        if ($expgroupid) {
            $query = $db->getQuery(true);
            $query->select('usergr.title');
            $query->from('#__usergroups as usergr');
            $query->where('usergr.id = ' . (int) $expgroupid);
            $db->setQuery($query);

            $expgroupname = $db->loadResult();

            // Check for a database error.
            if ($db->getErrorNum()) {
                JError::raiseWarning(500, $db->getErrorMsg());
            }
        }
        return $expgroupname;
    }

    public static function price_formatdata($p_price, $p_format = 1, $where = '', $javal = 0) {
        $expparams = ExpAutosProExpparams::getExpParams('config', 1);
        $conf_currformat = $expparams->get('c_general_priceformat');
        if (!$p_price && !$expparams->get('c_general_pricetext')) {
            $html = JText::_('COM_EXPAUTOSPRO_GENERAL_NO_PRICE_TEXT');
        } else {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            $query->select('cname,exchange,cvariable');
            $query->from('#__expautos_currency');
            $query->where('state = 1');
            if ($where) {
                $query->where('exchange = 1');
            }
            $query->order('ordering ASC');
            $db->setQuery($query);

            $result = $db->loadObjectList();
            switch ($conf_currformat) {
                case 1:
                    $html = "";
                    foreach ($result as $curr) {
                        if ($p_format == 2 && $curr->exchange != 1)
                            $html .= "&nbsp;(";
                        if ($javal)
                            $html .= $curr->cvariable . " " . $p_price . " " . $curr->cname;
                        else
                            $html .= $curr->cvariable . " " . number_format((float) $p_price * $curr->exchange) . " " . $curr->cname;
                        if ($p_format == 2 && $curr->exchange != 1)
                            $html .= ")";
                        if ($p_format == 1)
                            $html .= "<br />";
                    }
                    return $html;
                    break;
                case 2:
                    $html = "";
                    foreach ($result as $curr) {
                        if ($p_format == 2 && $curr->exchange != 1)
                            $html .= "(";
                        if ($javal)
                            $html .= $curr->cvariable . " " . $p_price;
                        else
                            $html .= $curr->cvariable . " " . number_format((float) $p_price * $curr->exchange);
                        if ($p_format == 2 && $curr->exchange != 1)
                            $html .= ")";
                        if ($p_format == 1)
                            $html .= "<br />";
                    }
                    return $html;
                    break;
                case 3:
                    $html = "";
                    foreach ($result as $curr) {
                        if ($p_format == 2 && $curr->exchange != 1)
                            $html .= "(";
                        if ($javal)
                            $html .= $p_price . " " . $curr->cname;
                        else
                            $html .= number_format((float) $p_price * $curr->exchange) . " " . $curr->cname;
                        if ($p_format == 2 && $curr->exchange != 1)
                            $html .= ")";
                        if ($p_format == 1)
                            $html .= "<br />";
                    }
                    return $html;
                    break;
                case 4:
                    $html = "";
                    foreach ($result as $curr) {
                        if ($curr->exchange == 1)
                            if ($javal)
                                $html .= $p_price . " " . $curr->cname;
                            else
                                $html .= number_format((float) $p_price) . " " . $curr->cname;
                    }
                    return $html;
                    break;
                default:
                    $html = "";
                    foreach ($result as $curr) {
                        if ($p_format == 2 && $curr->exchange != 1)
                            $html .= "(";
                        if ($javal)
                            $html .= $p_price . " " . $curr->cname;
                        else
                            $html .= number_format((float) $p_price * $curr->exchange) . " " . $curr->cname;
                        if ($p_format == 2 && $curr->exchange != 1)
                            $html .= ")";
                        if ($p_format == 1)
                            $html .= "<br />";
                    }
            }
        }
        return $html;
    }

    public static function expgeneral_pricename() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('cname');
        $query->from('#__expautos_currency');
        $query->where('state = 1');
        $query->where('exchange = 1');
        $db->setQuery($query);
        $result = $db->loadResult();

        return $result;
    }

    public static function ImgUrlPatch() {
        $config = ExpAutosProExpparams::getExpParams('config', '1');
        $imgpath = $config->get('c_user_req_imgpatch');
        $url = JURI::root() . 'administrator/components/com_expautospro/';
        $image_urlpath = $url . $imgpath;
        return $image_urlpath;
    }

    public static function ImgAbsPath() {
        $config = ExpAutosProExpparams::getExpParams('config', '1');
        $imgpath = $config->get('c_user_req_imgpatch');
        $image_abspath = JPATH_ROOT . DS . $imgpath;
        return $image_abspath;
    }

    public static function ImgUrlPatchLogo() {
        $config = ExpAutosProExpparams::getExpParams('config', '1');
        $imgpath = $config->get('c_user_req_logopatch');
        $url = JURI::root();
        $image_urlpath = $url . $imgpath;
        return $image_urlpath;
    }

    public static function ImgAbsPathLogo() {
        $config = ExpAutosProExpparams::getExpParams('config', '1');
        $imgpath = $config->get('c_user_req_logopatch');
        $image_abspath = JPATH_ROOT . DS . $imgpath;
        return $image_abspath;
    }

    public static function ImgUrlPatchThumbs() {
        $config = ExpAutosProExpparams::getExpParams('config', '1');
        $imgpath = $config->get('c_images_thumbpatch');
        $url = JURI::root();
        $image_urlpaththumb = $url . $imgpath;
        return $image_urlpaththumb;
    }

    public static function ImgUrlPatchMiddle() {
        $config = ExpAutosProExpparams::getExpParams('config', '1');
        $imgpath = $config->get('c_images_middlepatch');
        $url = JURI::root();
        $image_urlpathmiddle = $url . $imgpath;
        return $image_urlpathmiddle;
    }

    public static function ImgUrlPatchBig() {
        $config = ExpAutosProExpparams::getExpParams('config', '1');
        $imgpath = $config->get('c_images_bigpatch');
        $url = JURI::root();
        $image_urlpathbig = $url . $imgpath;
        return $image_urlpathbig;
    }

    public static function ImgAbsPathThumbs() {
        $config = ExpAutosProExpparams::getExpParams('config', '1');
        $imgpath = $config->get('c_images_thumbpatch');
        $image_abspaththumb = JPATH_ROOT . DS . $imgpath;
        return $image_abspaththumb;
    }

    public static function ImgAbsPathMiddle() {
        $config = ExpAutosProExpparams::getExpParams('config', '1');
        $imgpath = $config->get('c_images_middlepatch');
        $image_abspathmiddle = JPATH_ROOT . DS . $imgpath;
        return $image_abspathmiddle;
    }

    public static function ImgAbsPathBig() {
        $config = ExpAutosProExpparams::getExpParams('config', '1');
        $imgpath = $config->get('c_images_bigpatch');
        $image_abspathbig = JPATH_ROOT . DS . $imgpath;
        return $image_abspathbig;
    }

    public static function FilesAbsPath() {
        $config = ExpAutosProExpparams::getExpParams('config', '1');
        $filepath = $config->get('c_admanager_files_folder');
        $file_abspath = JPATH_ROOT . DS . $filepath;
        return $file_abspath;
    }

    public static function FilesUrlPatch() {
        $config = ExpAutosProExpparams::getExpParams('config', '1');
        $filepath = $config->get('c_admanager_files_folder');
        $url = JURI::root();
        $file_urlpathbig = $url . $filepath;
        return $file_urlpathbig;
    }

    public static function UnlinkImg($filepath) {
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }

    /**
     * This is a driver for the thumbnail creating
     *
     * PHP versions 4 and 5
     *
     * LICENSE:
     *
     * The PHP License, version 3.0
     *
     * Copyright (c) 1997-2005 The PHP Group
     *
     * This source file is subject to version 3.0 of the PHP license,
     * that is bundled with this package in the file LICENSE, and is
     * available through the world-wide-web at the following url:
     * http://www.php.net/license/3_0.txt.
     * If you did not receive a copy of the PHP license and are unable to
     * obtain it through the world-wide-web, please send a note to
     * license@php.net so we can mail you a copy immediately.
     *
     * @author      Ildar N. Shaimordanov <ildar-sh@mail.ru>
     * @license     http://www.php.net/license/3_0.txt
     *              The PHP License, version 3.0
     */

    /**
     * Create a GD image resource from given input.
     *
     * This method tried to detect what the input, if it is a file the
     * createImageFromFile will be called, otherwise createImageFromString().
     *
     * @param  mixed $input The input for creating an image resource. The value
     *                      may a string of filename, string of image data or
     *                      GD image resource.
     *
     * @return resource     An GD image resource on success or false
     * @access public
     * @static
     * @see    Thumbnail::imageCreateFromFile(), Thumbnail::imageCreateFromString()
     */
    public static function imageCreate($input) {
        if (is_file($input)) {
            return ExpAutosProExpparams::imageCreateFromFile($input);
        } else if (is_string($input)) {
            return ExpAutosProExpparams::imageCreateFromString($input);
        } else {
            return $input;
        }
    }

// }}}
// {{{
    /**
     * Create a GD image resource from file (JPEG, PNG support).
     *
     * @param  string $filename The image filename.
     *
     * @return mixed            GD image resource on success, FALSE on failure.
     * @access public
     * @static
     */
    public static function imageCreateFromFile($filename) {
        if (!is_file($filename) || !is_readable($filename)) {
            user_error(JText::_('COM_EXPAUTOSPRO_UNABLEOPENFILE_TEXT') . ' "' . $filename . '"', E_USER_NOTICE);
            return false;
        }
// determine image format
        list(,, $type) = getimagesize($filename);
        switch ($type) {
            case IMAGETYPE_JPEG:
                return imagecreatefromjpeg($filename);
                break;
            case IMAGETYPE_PNG:
                return imagecreatefrompng($filename);
                break;
            case IMAGETYPE_GIF:
                return imagecreatefromgif($filename);
                break;
        }
        user_error(JText::_('EXPA_ADM_UNSUPPORTIMGTYPE_TEXT'), E_USER_NOTICE);
        return false;
    }

// }}}
// {{{
    /**
     * Create a GD image resource from a string data.
     *
     * @param  string $string The string image data.
     *
     * @return mixed          GD image resource on success, FALSE on failure.
     * @access public
     * @static
     */
    public static function imageCreateFromString($string) {
        if (!is_string($string) || empty($string)) {
            user_error(JText::_('COM_EXPAUTOSPRO_INVALIDIMGSTRING_TEXT'), E_USER_NOTICE);
            return false;
        }
        return imagecreatefromstring($string);
    }

// }}}
// {{{
    /**
     * Display rendered image (send it to browser or to file).
     * This method is a common implementation to render and output an image.
     * The method calls the render() method automatically and outputs the
     * image to the browser or to the file.
     *
     * @param  mixed   $input   Destination image, a filename or an image string data or a GD image resource
     * @param  array   $options Thumbnail options
     *         <pre>
     *         width   int    Width of thumbnail
     *         height  int    Height of thumbnail
     *         percent number Size of thumbnail per size of original image
     *         method  int    Method of thumbnail creating
     *         halign  int    Horizontal align
     *         valign  int    Vertical align
     *         </pre>
     *
     * @return boolean          TRUE on success or FALSE on failure.
     * @access public
     */
    public static function output($input, $output = null, $options = array()) {
//print_r($input);
//die();
        $config = ExpAutosProExpparams::getExpParams('config', '1');
        $imgquality = $config->get('c_images_quality');
// Load source file and render image
        $renderImage = ExpAutosProExpparams::render($input, $options);
        if (!$renderImage) {
            user_error(JText::_('COM_EXPAUTOSPRO_ERRORRENDIMG_TEXT'), E_USER_NOTICE);
            return false;
        }
// Set output image type
// By default PNG image
        $type = isset($options['type']) ? $options['type'] : IMAGETYPE_JPEG;
// Before output to browsers send appropriate headers
        if (empty($output)) {
            $content_type = image_type_to_mime_type($type);
            if (!headers_sent()) {
                header(JText::_('COM_EXPAUTOSPRO_CONTENTTYPE_TEXT') . $content_type);
            } else {
                user_error(JText::_('COM_EXPAUTOSPRO_NOTDISPIMG_TEXT'), E_USER_NOTICE);
                return false;
            }
        }
// Define outputing function
        switch ($type) {
            case IMAGETYPE_PNG:
                $result = empty($output) ? imagepng($renderImage) : imagepng($renderImage, $output, $imgquality);
                break;
            case IMAGETYPE_JPEG:
                $result = empty($output) ? imagejpeg($renderImage) : imagejpeg($renderImage, $output, $imgquality);
                break;
            case IMAGETYPE_GIF:
                $result = empty($output) ? imagegif($renderImage) : imagegif($renderImage, $output, $imgquality);
                break;
            default:
                user_error(JText::_('COM_EXPAUTOSPRO_IMGTYPE_TEXT') . $content_type . JText::_('COM_EXPAUTOSPRO_NOTSUPPORTPHP_TEXT'), E_USER_NOTICE);
                return false;
        }
// Output image (to browser or to file)
        if (!$result) {
            user_error(JText::_('COM_EXPAUTOSPRO_ERROROUTPUTIMG_TEXT'), E_USER_NOTICE);
            return false;
        }
// Free a memory from the target image
        imagedestroy($renderImage);
        return true;
    }

// }}}
// {{{ render()
    /**
     * Draw thumbnail result to resource.
     *
     * @param  mixed   $input   Destination image, a filename or an image string data or a GD image resource
     * @param  array   $options Thumbnail options
     *
     * @return boolean TRUE on success or FALSE on failure.
     * @access public
     * @see    Thumbnail::output()
     */
    public static function render($input, $options = array()) {

        //$config = & ExpAutosProExpparams::getExpParams('config', '1');
// Create the source image
        $sourceImage = ExpAutosProExpparams::imageCreate($input);
//print_r($sourceImage);
//die();
        if (!is_resource($sourceImage)) {
            user_error(JText::_('COM_EXPAUTOSPRO_INVALIDIMGRES_TEXT'), E_USER_NOTICE);
            return false;
        }
        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);
// Set default options
        static $defOptions = array(
    'width' => 150,
    'height' => 150,
    'method' => 0 /* THUMBNAIL_METHOD_SCALE_MAX */,
    'percent' => 0,
    'halign' => 0 /* THUMBNAIL_ALIGN_CENTER */,
    'valign' => 0 /* THUMBNAIL_ALIGN_CENTER */,
    'watermark' => 0,
        );
        foreach ($defOptions as $k => $v) {
            if (!isset($options[$k])) {
                $options[$k] = $v;
            }
        }
// Estimate a rectangular portion of the source image and a size of the target image
        if ($options['method'] == 2 /* THUMBNAIL_METHOD_CROP */) {
            if ($options['percent']) {
                $W = floor($options['percent'] * $sourceWidth);
                $H = floor($options['percent'] * $sourceHeight);
            } else {
                $W = $options['width'];
                $H = $options['height'];
            }
            $width = $W;
            $height = $H;
            $Y = ExpAutosProExpparams::_coord($options['valign'], $sourceHeight, $H);
            $X = ExpAutosProExpparams::_coord($options['halign'], $sourceWidth, $W);
        } else {
            $X = 0;
            $Y = 0;
            $W = $sourceWidth;
            $H = $sourceHeight;
            if ($options['percent']) {
                $width = floor($options['percent'] * $W);
                $height = floor($options['percent'] * $H);
            } else {
                $width = $options['width'];
                $height = $options['height'];
                if ($options['method'] == 1/* THUMBNAIL_METHOD_SCALE_MIN */) {
                    $Ww = $W / $width;
                    $Hh = $H / $height;
                    if ($Ww > $Hh) {
                        $W = floor($width * $Hh);
                        $X = ExpAutosProExpparams::_coord($options['halign'], $sourceWidth, $W);
                    } else {
                        $H = floor($height * $Ww);
                        $Y = ExpAutosProExpparams::_coord($options['valign'], $sourceHeight, $H);
                    }
                } else {
                    if ($H > $W) {
                        $width = floor($height / $H * $W);
                    } else {
                        $height = floor($width / $W * $H);
                    }
                }
            }
        }
// Create the target image
        if (function_exists('imagecreatetruecolor')) {
            $targetImage = imagecreatetruecolor($width, $height);
        } else {
            $targetImage = imagecreate($width, $height);
        }
        if (!is_resource($targetImage)) {
            user_error(JText::_('COM_EXPAUTOSPRO_NOTINITGDIMG_TEXT'), E_USER_NOTICE);
            return false;
        }
// Copy the source image to the target image
        if ($options['method'] == 2 /* THUMBNAIL_METHOD_CROP */) {
            $result = imagecopy($targetImage, $sourceImage, 0, 0, $X, $Y, $W, $H);
            if ($options['watermark']) {
                ExpAutosProExpparams::watermark($targetImage, $width, $height, $W, $H);
            }
        } elseif (function_exists('imagecopyresampled')) {
            $result = imagecopyresampled($targetImage, $sourceImage, 0, 0, $X, $Y, $width, $height, $W, $H);
            if ($options['watermark']) {
                ExpAutosProExpparams::watermark($targetImage, $width, $height, $W, $H);
            }
        } else {
            $result = imagecopyresized($targetImage, $sourceImage, 0, 0, $X, $Y, $width, $height, $W, $H);
            if ($options['watermark']) {
                ExpAutosProExpparams::watermark($targetImage, $width, $height, $W, $H);
            }
        }
        if (!$result) {
            user_error(JText::_('COM_EXPAUTOSPRO_NOTRESIZEIMG_TEXT'), E_USER_NOTICE);
            return false;
        }
// Free a memory from the source image
        imagedestroy($sourceImage);
// Save the resulting thumbnail
        return $targetImage;
    }

    public static function watermark($img, $width, $height, $W, $H) {
        $config = ExpAutosProExpparams::getExpParams('config', '1');
        $wtpath = $config->get('c_images_wt_imagename');
        $patch_folder = ExpAutosProExpparams::ImgAbsPath();
        $watermark_src = $patch_folder . '/administrator/components/com_expautospro/assets/images/' . $wtpath;
        $watermark = imagecreatefrompng($watermark_src);
        list($watermark_width, $watermark_height) = getimagesize($watermark_src);
        // calculates the x position
        switch ($config->get('c_images_wt_horalign')) {
            case 0:
                $final_x = $config->get('c_images_wt_hormargin');
                break;
            case 1:
                $final_x = round(($width - $watermark_width) / 2);
                break;
            case 2:
            default:
                $final_x = $width - $watermark_width - $config->get('c_images_wt_hormargin');
                break;
        }

        // calculates the y position
        switch ($config->get('c_images_wt_vertalign')) {
            case 0:
                $final_y = $config->get('c_images_wt_vertmargin');
                break;
            case 1:
                $final_y = round(($height - $watermark_height) / 2);
                break;
            case 2:
            default:
                $final_y = $height - $watermark_height - $config->get('c_images_wt_vertmargin');
                break;
        }

        settype($final_x, 'integer');
        settype($final_y, 'integer');
        imagealphablending($watermark, false);

        imagecopy($img, $watermark, $final_x, $final_y, 0, 0, $W, $H);
    }

// }}}
// {{{ _coord()
    public static function _coord($align, $param, $src) {
        if ($align < 0 /* THUMBNAIL_ALIGN_CENTER */) {
            $result = 0;
        } elseif ($align > 0 /* THUMBNAIL_ALIGN_CENTER */) {
            $result = $param - $src;
        } else {
            $result = ($param - $src) >> 1;
        }
        return $result;
    }

    /* Version 2.2 */

    public static function getExpLinkItemid() {
        $expconfig = ExpAutosProExpparams::getExpParams('config', 1);
        $expadvmenu = $expconfig->get('c_general_menuadvanced');
        $expItemid = (int) JRequest::getInt('Itemid', 0);
        $expCatid = (int) JRequest::getInt('catid', 0);
        $expId = (int) JRequest::getInt('id', 0);
        $expOption = JRequest::getVar('option', 0);
        $expView = JRequest::getVar('view', 0);
        $app = JFactory::getApplication();
        $menus = $app->getMenu('site');
        $component = JComponentHelper::getComponent('com_expautospro');
        //$menu = $app->getMenu('site');
        $items = $menus->getItems('component_id', $component->id);
        //print_r($items);
        $expitem = '';
        $expitem = $items[0]->id;
        if ($expOption == 'com_expautospro') {
            $categories = JCategories::getInstance('Expautospro');
            $category = $categories->get($expCatid);
            if (is_object($category)) {
                $path = array_reverse($category->getPath());
                foreach ($items as $id => $item) {
                    foreach ($path as $id) {
                        $ids = explode(':', $id, 2);
                        if (!empty($item->query['id']) && (int) $ids[0] == (int) $item->query['id']) {
                            $expitem = $item->id;
                            break;
                        }
                    }
                    //print_r($item->query['id']);
                    if ($expView == 'categories' && (int) $expId && isset($item->query['id']) && $expId == $item->query['id']) {
                        $expitem = $item->id;
                    }
                    if ($expadvmenu) {
                        if ($expCatid) {
                            if (isset($item->query['id']) && $item->query['id'] == $expCatid) {
                                $expitem = $item->id;
                            } elseif (isset($item->query['catid']) && $item->query['catid'] == $expCatid && $expItemid == $item->id) {
                                $expitem = $item->id;
                            }
                        } elseif ($expItemid == $item->id) {
                            $expitem = $item->id;
                        } elseif ($item->note == 'default') {
                            $expitem = $item->id;
                        }
                    }
                }
            }
            if ($expitem)
                $menus->setActive($expitem);
        }
        else {
            foreach ($items as $id => $item) {
                if ($item->note == 'default')
                    $expitem = $item->id;
            }
        }
        return $expitem;
    }

    public static function getExpCategories($allowcat = 0, $scount = 0) {
        if ($allowcat) {
            $expparams = ExpAutosProExpparams::getExpParams('config', 1);
            $expallowcat = $expparams->get('c_admanager_fpcat_allowcat');
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $extension = "com_expautospro";

        $query->select('a.id AS value, a.title AS text, a.level');
        $query->from('#__categories AS a');
        $query->join('LEFT', $db->quoteName('#__categories') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt');
        $query->where('(a.extension = ' . $db->quote($extension) . ' OR a.parent_id = 0)');
        $query->where('a.published IN (0,1)');
        $query->group('a.id, a.title, a.level, a.lft, a.rgt, a.extension, a.parent_id');
        $query->order('a.lft ASC');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }
        $count_opt = count($options);
        for ($i = 0, $n = $count_opt; $i < $n; $i++) {
            //print_r($options[$i]);
            $expcount_num = '';
            $expcount = null;
            // Translate ROOT
            if ($options[$i]->level == 0) {
                $options[$i]->text = JText::_('MOD_EXPAUTOSPRO_SEARCH_SELECT_CATEGORY_TEXT');
                $options[$i]->value = 0;
            }
            if ($allowcat && $options[$i]->level != 0 && !in_array($options[$i]->value, $expallowcat)) {
                if ($scount) {
                    if ($options[$i]->value) {
                        $expcount = ExpAutosProHelper::getExpcount('admanager', 'catid', $options[$i]->value);
                    }
                    if ($expcount) {
                        $expcount_num = " (" . $expcount . ")";
                    }
                }
                $options[$i]->text = str_repeat('- ', $options[$i]->level) . $options[$i]->text . $expcount_num;
                $options[$i] = JHtml::_('select.option', $options[$i]->value, $options[$i]->text, 'value', 'text', true);
                //unset($options[$i]);
            } else {
                if ($scount) {
                    if ($options[$i]->value) {
                        $expcount = ExpAutosProHelper::getExpcount('admanager', 'catid', $options[$i]->value);
                    }
                    if ($expcount) {
                        $expcount_num = " (" . $expcount . ")";
                    }
                }
                $options[$i]->text = str_repeat('- ', $options[$i]->level) . $options[$i]->text . $expcount_num;
            }
        }
        return $options;
    }

    public static function getExpDealersParams($UserId = 0) {
        $listusergroups = implode(',', JAccess::getGroupsByUser($UserId));
        $listusergroupid = ExpAutosProExpparams::getExpgroupid($listusergroups);
        $listgroupparams = ExpAutosProExpparams::getExpParams('userlevel', $listusergroupid);
        return $listgroupparams;
    }

    public static function getExpEquipments($catid, $expequip, $val = 0) {
        $return = '';
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id');
        $query->from('#__expautos_catequipment');
        $query->where('state=1');
        $query->where('catid = ' . (int) $catid);
        $db->setQuery($query);
        $dbqeuip = $db->loadResultArray();
        if ($dbqeuip) {
            $dbqeuip = implode(",", $dbqeuip);

            //$db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('id As value, name As text');
            $query->from('#__expautos_equipment');
            $query->where('state = 1');
            $query->where('catid  IN (' . $dbqeuip . ')');
            $query->where('id  IN (' . $expequip . ')');
            $query->order('ordering');

            // Get the options.
            $db->setQuery($query);

            $listequip = $db->loadObjectList();

            // Check for a database error.
            if ($db->getErrorNum()) {
                JError::raiseWarning(500, $db->getErrorMsg());
            }
            if ($val) {
                if ($val == 1) {
                    $etag = 'p';
                } elseif ($val == 2) {
                    $etag = 'li';
                } else {
                    $etag = 'p';
                }
                $html = '';
                if ($listequip) {
                    foreach ($listequip as $item) {
                        $html .= '<' . $etag . ' class="expautospro_search searchcheckbox">
                    <label for="modexpsearch_equipmets">' . $item->text . '</label>
                    <input type="checkbox" name="equipmets[]" value="' . $item->value . '"  />
                        </' . $etag . '>';
                    }
                }
                $return = $html;
            } else {
                $return = $listequip;
            }
        }
        return $return;
    }

    public static function getExpCatChilds($catid) {
        $expallcat = 0;
        $expselcat = JCategories::getInstance('Expautospro');
        if ($expselcat)
            $expgetcat = $expselcat->get($catid);
        if ($expgetcat) {
            $expgetchild = $expgetcat->getChildren(true);
            $expcatsub = array();
            $expcatsub[$catid] = $catid;
            foreach ($expgetchild as $expchild) {
                $expcatsub[$expchild->id] = $expchild->id;
            }
            $expallcat = implode(',', $expcatsub);
        }
        //print_r($expallcat);
        return $expallcat;
    }

    public static function getExpcutStr($str, $scount, $startcount = 0, $cutparam = '...') {

        if (strlen($str) > $scount) {
            $str = substr($str, $startcount, $scount) . $cutparam;
        }

        return $str;
    }

    public static function getExpImgBtype($catid, $imgbtype = 1, $bodytype = 0, $expitemid = 0) {
        $html = null;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('btp.id AS btpid, btp.name AS btpname, btp.image AS btpimage');
        $query->from('#__expautos_bodytype as btp');
        $query->where('btp.state=1');
        $query->where('btp.catid=' . (int) $catid);
        $query->order('btp.ordering');
        $db->setQuery($query);
        $return = $db->loadObjectList();
        $html .= '<ul>';
        foreach ($return as $value) {
            if (($imgbtype && $value->btpimage) || (!$imgbtype)) {
                $linklist = JRoute::_('index.php?option=com_expautospro&amp;view=explist&amp;catid=' . (int) $catid . '&amp;bodytype=' . $value->btpid . '&amp;Itemid=' . (int) $expitemid);
                $bclass = null;
                if ($bodytype && $bodytype == $value->btpid) {
                    $bclass = ' b_active ';
                }
                $html .='<li>
                            <a class="' . $bclass . '" href="' . $linklist . '">
                                <div class="expbtype_link">
                                    <img src="' . JURI::root() . $value->btpimage . '" />
                                    <p>' . $value->btpname . '</p>
                                </div>
                            </a>
                        </li>';
            }
        }
        $html .='</ul><div style="clear:both;"></div>';
        return $html;
    }

    public static function getExpCat($parent, $count) {
        $options = array();
        $options['countItems'] = 1;
        $categories = JCategories::getInstance('Expautospro', $options);
        $category = $categories->get($parent);
        if ($category != null) {
            $items = $category->getChildren();
            if ($count > 0 && count($items) > $count) {
                $items = array_slice($items, 0, $count);
            }
            return $items;
        }
    }

}

?>
