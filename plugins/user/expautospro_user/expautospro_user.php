<?php

/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

defined('JPATH_BASE') or die;
require_once JPATH_SITE . '/components/com_expautospro/helpers/expparams.php';

class plgUserExpautospro_user extends JPlugin {

    function onContentPrepareData($context, $data) {
        if (!in_array($context, array('com_users.profile', 'com_users.registration', 'com_users.user', 'com_admin.profile'))) {
            return true;
        }
        //$userParams = JComponentHelper::getParams('com_users');
        $userId = isset($data->id) ? $data->id : 0;
        return true;
    }

    function onContentPrepareForm($form, $data) {
        $expparams = ExpAutosProExpparams::getExpParams('config', 1);

        // Load user_profile plugin language
        $lang = JFactory::getLanguage();
        $lang->load('plg_user_expautospro_user', JPATH_ADMINISTRATOR);

        if (!($form instanceof JForm)) {
            $this->_subject->setError('JERROR_NOT_A_FORM');
            return false;
        }
        // Check we are manipulating a valid form.
        if (!in_array($form->getName(), array('com_users.registration'))) {
            return true;
        }
        if ($form->getName() == 'com_users.profile') {
            // Add the profile fields to the form.
            //JForm::addFormPath(dirname(__FILE__) . '/profiles');
            //$form->loadFile('profile', false);
        } elseif ($form->getName() == 'com_users.registration' && $this->params->get('expprofile_usefields', 1)) {

            $userParams = JComponentHelper::getParams('com_users');
            $document = JFactory::getDocument();
            $userId = isset($data->id) ? $data->id : 0;
            $listgroupparams = null;
            if ($userId > 0) {
                $listusergroups = implode(',', JAccess::getGroupsByUser($UserId));
                $listusergroupid = ExpAutosProExpparams::getExpgroupid($listusergroups);
                $listgroupparams = ExpAutosProExpparams::getExpParams('userlevel', $listusergroupid);
            } else {
                $listusergroupid = ExpAutosProExpparams::getExpgroupid($userParams->get('new_usertype'));
                $listgroupparams = ExpAutosProExpparams::getExpParams('userlevel', $listusergroupid);
            }
            if ($listusergroupid) {

                // Add the registration fields to the form.
                JForm::addFormPath(dirname(__FILE__) . '/profiles');
                $form->loadFile('profile', false);
				
				$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxrequest.js');
				$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxchained.js');
				$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expsearchchained.js');
				$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expsearchtoggle.js');
				
                if ($expparams->get('c_admanager_useradd_showgooglemaps')) {
                    $lat = $expparams->get('c_admanager_useradd_googlemaps_latdef');
                    $long = $expparams->get('c_admanager_useradd_googlemaps_longdef');
                    $zoom = $expparams->get('c_admanager_useradd_googlemaps_zoomdef');
                    $expzoombycst = $expparams->get('c_admanager_useradd_googlemaps_zoomcst');
                    $expzoombystreet = $expparams->get('c_admanager_useradd_googlemaps_zoomstreet');
                    $expgooglewidth = $expparams->get('c_admanager_useradd_googlemaps_width');
                    $expgoogleheight = $expparams->get('c_admanager_useradd_googlemaps_height');

                    $document->addScript('http://maps.google.com/maps/api/js?sensor=false');
                    $document->addScript(JURI::root() . 'components/com_expautospro/assets/js/expgooglemap.js');
                    $script = '';
                    $script .= "
            var explat=$lat;
            var explong =$long;
            var expzoom =$zoom;
            var expzoombycst =$expzoombycst;
            var expzoombystreet =$expzoombystreet;
            var expclick = 1;
            var expmapTypeId = google.maps.MapTypeId." . $expparams->get('c_general_gmapmaptypeid') . ";
            var latid = 'jform_expprofile_latitude';
            var longid = 'jform_expprofile_longitude';
            var expalert = '" . JText::_('COM_EXPAUTOSPRO_GOOGLEMAP_ALERT_TEXT') . "';
            var exp_map_canvas = 'exp_map_canvas';
                    ";

                    $document->addScriptDeclaration($script);
                }


                if ($listgroupparams->get('c_lastname')) {
                    if ($expparams->get('c_user_req_lastname')) {
                        $form->setFieldAttribute('lastname', 'required', 'true', 'expprofile');
                    }
                } else {
                    $form->removeField('lastname', 'expprofile');
                }

                if ($listgroupparams->get('c_ucompany')) {
                    if ($expparams->get('c_user_req_companyname')) {
                        $form->setFieldAttribute('companyname', 'required', 'true', 'expprofile');
                    }
                } else {
                    $form->removeField('companyname', 'expprofile');
                }

                if ($listgroupparams->get('c_uwebsite')) {
                    if ($expparams->get('c_user_req_web')) {
                        $form->setFieldAttribute('web', 'required', 'true', 'expprofile');
                    }
                } else {
                    $form->removeField('web', 'expprofile');
                }

                if ($listgroupparams->get('c_uphone')) {
                    if ($expparams->get('c_user_req_phone')) {
                        $form->setFieldAttribute('phone', 'required', 'true', 'expprofile');
                    }
                } else {
                    $form->removeField('phone', 'expprofile');
                }

                if ($listgroupparams->get('c_ucphone')) {
                    if ($expparams->get('c_user_req_cellphone')) {
                        $form->setFieldAttribute('mobphone', 'required', 'true', 'expprofile');
                    }
                } else {
                    $form->removeField('mobphone', 'expprofile');
                }

                if ($listgroupparams->get('c_ufax')) {
                    if ($expparams->get('c_user_req_fax')) {
                        $form->setFieldAttribute('fax', 'required', 'true', 'expprofile');
                    }
                } else {
                    $form->removeField('fax', 'expprofile');
                }

                if ($listgroupparams->get('c_uinfo')) {
                    
                } else {
                    $form->removeField('userinfo', 'expprofile');
                }

                if ($listgroupparams->get('c_ulogo') || $listgroupparams->get('c_ulogo')) {
                    $script = '';
                    $script .= "
                        function add_enctype(valexp){
                        console.log(valexp);
                        valexp.encoding = 'multipart/form-data';
                        }
                            ";

                    $document->addScriptDeclaration($script);
                }
                if ($listgroupparams->get('c_ulogo')) {
                    $form->setFieldAttribute('logo', 'onchange', 'add_enctype(this.form);', 'expprofile');
                } else {
                    $form->removeField('logo', 'expprofile');
                }
                if ($listgroupparams->get('c_photo')) {
                    $form->setFieldAttribute('expphoto', 'onchange', 'add_enctype(this.form);', 'expprofile');
                } else {
                    $form->removeField('expphoto', 'expprofile');
                }

                if ($listgroupparams->get('c_ucountry')) {
                    $change_param_c = '';
                    if ($listgroupparams->get('c_ustate')) {
                        $script = '';
                        $script .= "
                        function change_chained(val){
                        var url = 'index.php?option=com_expautospro&view=expuser&format=ajax&expcountry_id='+val;
                            ajaxgetchained(url,'jformexpprofileexpstate')
                        }
                            ";

                        $document->addScriptDeclaration($script);
                        $change_param_c .= 'change_chained(this.value);';
                    }
                    if ($expparams->get('c_admanager_useradd_showgooglemaps')) {
                        $change_param_c .= ' findAddress(this);return false;';
                    }
                    if ($expparams->get('c_user_req_country')) {
                        $form->setFieldAttribute('country', 'required', 'true', 'expprofile');
                    }
                    $form->setFieldAttribute('country', 'onchange', $change_param_c, 'expprofile');
                } else {
                    $form->removeField('country', 'expprofile');
                }

                if ($listgroupparams->get('c_ustate')) {
                    $change_param = '';
                    if ($listgroupparams->get('c_ucity')) {
                        $script = '';
                        $script .= "
                        function change_chained_state(val){
                            var url = 'index.php?option=com_expautospro&view=expuser&format=ajax&state_id='+val;
                            ajaxgetchained(url,'jformexpprofilecity')
                        }
                            ";

                        $document->addScriptDeclaration($script);
                        $change_param .= 'change_chained_state(this.value);';
                    }
                    if ($expparams->get('c_admanager_useradd_showgooglemaps')) {
                        $change_param .= ' findAddress(this);return false;';
                    }
                    if ($expparams->get('c_user_req_state')) {
                        $form->setFieldAttribute('expstate', 'required', 'true', 'expprofile');
                    }
                    $form->setFieldAttribute('expstate', 'onchange', $change_param, 'expprofile');
                } else {
                    $form->removeField('expstate', 'expprofile');
                }

                if ($listgroupparams->get('c_ucity')) {
                    if ($expparams->get('c_user_req_city')) {
                        $form->setFieldAttribute('city', 'required', 'true', 'expprofile');
                    }
                    if ($expparams->get('c_admanager_useradd_showgooglemaps')) {
                        $form->setFieldAttribute('city', 'onchange', ' findAddress(this);return false;', 'expprofile');
                    }
                } else {
                    $form->removeField('city', 'expprofile');
                }

                if ($listgroupparams->get('c_ustreet')) {
                    if ($expparams->get('c_user_req_street'))
                        $form->setFieldAttribute('street', 'required', 'true', 'expprofile');
                    if ($expparams->get('c_admanager_useradd_showgooglemaps'))
                        $form->setFieldAttribute('street', 'onchange', 'codeStreet(this.value);return false;', 'expprofile');
                }else {
                    $form->removeField('street', 'expprofile');
                }

                if ($listgroupparams->get('c_uzip')) {
                    if ($expparams->get('c_user_req_zipcode')) {
                        $form->setFieldAttribute('zipcode', 'required', 'true', 'expprofile');
                    }
                } else {
                    $form->removeField('zipcode', 'expprofile');
                }

                if ($expparams->get('c_admanager_useradd_showgooglemaps')) {
                    if ($expparams->get('c_admanager_useradd_latlong')) {
                        $form->setFieldAttribute('latitude', 'required', 'true', 'expprofile');
                        $form->setFieldAttribute('longitude', 'required', 'true', 'expprofile');
                    }
                    $form->setFieldAttribute('latitude', 'onchange', 'findLangLong();return false;', 'expprofile');
                    $form->setFieldAttribute('longitude', 'onchange', 'findLangLong();return false;', 'expprofile');
                } else {
                    $form->removeField('latitude', 'expprofile');
                    $form->removeField('longitude', 'expprofile');
                }
				
                if (!$expparams->get('c_admanager_useradd_showgooglemaps')) {
					$form->removeField('expgoogle', 'expprofile');
				}
            }
        }
    }

    function onUserAfterSave($data, $isNew, $result, $error) {
        $userId = JArrayHelper::getValue($data, 'id', 0, 'int');


        if ($userId && $result && isset($data['expprofile']) && (count($data['expprofile']))) {
            try {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('userid');
                $query->from('#__expautos_expuser');
                $query->where('userid = ' . (int) $userId);
                $db->setQuery($query);
                $expuser_id = $db->loadResult();
                if (!$expuser_id) {
                    $expparams = ExpAutosProExpparams::getExpParams('config', 1);
                    $db = JFactory::getDBO();
                    $db->setQuery('INSERT INTO #__expautos_expuser (catid,userid,state,ordering,lastname,companyname,web,phone,mobphone,fax,zipcode,userinfo,country,expstate,city,street,latitude,longitude) VALUES ("777",' . $userId . ',' . $expparams->get("c_user_req_autopublished") . ',"p","' . $data["expprofile"]["lastname"] . '","' . $data["expprofile"]["companyname"] . '","' . $data["expprofile"]["web"] . '","' . $data["expprofile"]["phone"] . '","' . $data["expprofile"]["mobphone"] . '","' . $data["expprofile"]["fax"] . '","' . $data["expprofile"]["zipcode"] . '","' . $data["expprofile"]["userinfo"] . '","' . $data["expprofile"]["country"] . '","' . $data["expprofile"]["expstate"] . '","' . $data["expprofile"]["city"] . '","' . $data["expprofile"]["street"] . '","' . $data["expprofile"]["latitude"] . '","' . $data["expprofile"]["longitude"] . '")');
                    /*
                      $logo_data = JRequest::getVar('logo', '', 'files', 'array');
                      $userphoto_data = JRequest::getVar('userphoto', '', 'files', 'array');
                      $obj = new stdClass();
                      $obj->catid = 777;
                      $obj->userid = $userId;
                      $obj->state = $expparams->get('c_user_req_autopublished');
                      $obj->ordering = 0;
                      $obj->lastname = $data['expprofile']['lastname'];
                      $obj->companyname = $data['expprofile']['companyname'];
                      $obj->web = $data['expprofile']['web'];
                      $obj->phone = $data['expprofile']['phone'];
                      $obj->mobphone = $data['expprofile']['mobphone'];
                      $obj->fax = $data['expprofile']['fax'];
                      $obj->zipcode = $data['expprofile']['zipcode'];
                      $obj->userinfo = $data['expprofile']['userinfo'];
                      $obj->country = $data['expprofile']['country'];
                      $obj->expstate = $data['expprofile']['expstate'];
                      $obj->city = $data['expprofile']['city'];
                      $obj->street = $data['expprofile']['street'];
                      $obj->latitude = $data['expprofile']['latitude'];
                      $obj->longitude = $data['expprofile']['longitude'];
                      $db->insertObject('#__expautos_expuser', $obj);
                     * 
                     */

                    if (!$db->query()) {
                        throw new Exception($db->getErrorMsg());
                    }
                }
            } catch (JException $e) {
                $this->_subject->setError($e->getMessage());
                return false;
            }
        }
        return true;
    }

    function onUserAfterDelete($user, $success, $msg) {
        if (!$success) {
            return false;
        }

        $userId = JArrayHelper::getValue($user, 'id', 0, 'int');
        $gluser = JFactory::getUser();
        $GlUserId = (int) $gluser->get('id');

        if ($userId && $GlUserId) {
            try {

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('id');
                $query->from('#__expautos_admanager');
                $query->where('user = ' . $userId);
                $db->setQuery($query);
                $user_ads = $db->loadResultArray();
                if ($user_ads) {
                    foreach ($user_ads as $ads) {
                        if ($this->params->get('expprofile_delete', 1)) {
                            if (ExpAutosProExpparams::delete_ads((int) $ads)) {
                                //return true;
                            } else {
                                //return false;
                            }
                        } else {
                            if (ExpAutosProExpparams::changestatus((int) $ads)) {
                                //return true;
                            } else {
                                //return false;
                            }
                        }
                    }
                }
                if ($this->params->get('expprofile_deluser', 1)) {
                    $db->setQuery(
                            'DELETE FROM #__expautos_expuser WHERE userid = ' . $userId
                    );

                    if (!$db->query()) {
                        throw new Exception($db->getErrorMsg());
                    }
                }
            } catch (JException $e) {
                $this->_subject->setError($e->getMessage());
                return false;
            }
        }

        return true;
    }

}

?>