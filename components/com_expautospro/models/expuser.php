<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
require_once JPATH_COMPONENT . '/helpers/expparams.php';
require_once JPATH_COMPONENT . '/helpers/expfields.php';

class ExpAutosProModelExpuser extends JModelForm {

    protected $data;

    public function checkin($userId = null) {
        $userId = (!empty($userId)) ? $userId : (int) $this->getState('expuser.id');
        $table = JTable::getInstance('Expuser', $prefix = 'ExpautosproTable');
        return true;
    }

    function getExpparams() {
        $expparams = ExpAutosProExpparams::getExpParams('config', 1);
        return $expparams;
    }

    function getExpgroup() {
        $user = JFactory::getUser();
        $UserId = (int) $user->get('id');
        $listusergroup = '';
        if ($UserId > 0) {
            //$listusergroups = implode(',', JAccess::getGroupsByUser($UserId));
            $listusergroups = implode(',', JAccess::getGroupsByUser($UserId));
            $listusergroup = ExpAutosProExpparams::getExpgroupname($listusergroups);
        }
        return $listusergroup;
    }

    function getExpgroupid() {
        $user = JFactory::getUser();
        $UserId = (int) $user->id;
        $usergroupid = '';
        if ($UserId > 0) {
            $usergroups = implode(',', $user->groups);
            $usergroupid = ExpAutosProExpparams::getExpgroupid($usergroups);
        }
        return $usergroupid;
    }

    function getExpgroupfields() {
        $user = JFactory::getUser();
        $UserId = (int) $user->get('id');
        $listgroupparams = '';
        if ($UserId > 0) {
            //$listusergroups = implode(',', $user->groups);
            $listusergroups = implode(',', JAccess::getGroupsByUser($UserId));
            $listusergroupid = ExpAutosProExpparams::getExpgroupid($listusergroups);
            $listgroupparams = ExpAutosProExpparams::getExpParams('userlevel', $listusergroupid);
        }
        return $listgroupparams;
    }

    protected function populateState() {

        $params = JFactory::getApplication()->getParams('com_expautospro');
        $userId = JFactory::getApplication()->getUserState('com_expautospro.edit.expuser.id');
        $userId = !empty($userId) ? $userId : (int) JFactory::getUser()->get('id');

        $this->setState('expuser.id', $userId);
        $this->setState('params', $params);
    }

    public function getData() {

        $userId = $this->getState('expuser.id');
        $user = JFactory::getUser();
        $loginUserId = (int) $user->get('id');
        $exptableId = (int) ExpAutosProFields::getExpuserid($user->id);
        
        $table = JTable::getInstance('Expuser', $prefix = 'ExpautosproTable');
        $return = $table->load($exptableId);

        $properties = $table->getProperties(1);
        $value = JArrayHelper::toObject($properties, 'JObject');
        $value->gname = $user->get('name');
        $value->gusername = $user->get('username');
        $value->gemail = $user->get('email');

        return $value;
    }

    public function getForm($data = array(), $loadData = true) {
        // Get the form.
        $form = $this->loadForm('com_expautospro.expuser', 'expuser', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData() {
        return $this->getData();
    }
    
    public function expvalid($data){
        $expauserreq = $this->getExpparams();
        $expauserfield = $this->getExpgroupfields();
        if(!$data['userid'])
            $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_DEALERINFO_ERROR_USERID_TEXT'));
        if($expauserfield->get('c_lastname')){
            if($expauserreq->get('c_user_req_lastname') && !$data['lastname']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_DEALERINFO_ERROR_LASTNAME_TEXT'));
                return false;
            }
        }
        if($expauserfield->get('c_ucompany')){
            if($expauserreq->get('c_user_req_companyname') && !$data['companyname']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_DEALERINFO_ERROR_COMPANYNAME_TEXT'));
                return false;
            }
        }
        if($expauserfield->get('c_ucountry')){
            if($expauserreq->get('c_user_req_country') && !$data['country']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_DEALERINFO_ERROR_COUNTRY_TEXT'));
                return false;
            }
        }
        if($expauserfield->get('c_ustate')){
            if($expauserreq->get('c_user_req_state') && !$data['expstate']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_DEALERINFO_ERROR_STATE_TEXT'));
                return false;
            }
        }
        if($expauserfield->get('c_ucity')){
            if($expauserreq->get('c_user_req_city') && !$data['city']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_DEALERINFO_ERROR_CITY_TEXT'));
                return false;
            }
        }
        if($expauserfield->get('c_ustreet')){
            if($expauserreq->get('c_user_req_street') && !$data['street']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_DEALERINFO_ERROR_STREET_TEXT'));
                return false;
            }
        }
        if($expauserfield->get('c_uwebsite')){
            if($expauserreq->get('c_user_req_web') && !$data['web']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_DEALERINFO_ERROR_WEB_TEXT'));
                return false;
            }
        }
        if($expauserfield->get('c_uphone')){
            if($expauserreq->get('c_user_req_phone') && !$data['phone']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_DEALERINFO_ERROR_PHONE_TEXT'));
                return false;
            }
        }
        if($expauserfield->get('c_ucphone')){
            if($expauserreq->get('c_user_req_cellphone') && !$data['mobphone']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_DEALERINFO_ERROR_MOBPHONE_TEXT'));
                return false;
            }
        }
        if($expauserfield->get('c_ufax')){
            if($expauserreq->get('c_user_req_fax') && !$data['fax']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_DEALERINFO_ERROR_FAX_TEXT'));
                return false;
            }
        }
        if($expauserfield->get('c_uzip')){
            if($expauserreq->get('c_user_req_zipcode') && !$data['zipcode']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_DEALERINFO_ERROR_ZIPCODE_TEXT'));
                return false;
            }
        }
        if($expauserreq->get('c_admanager_useradd_latlong') && !$data['latitude']){
            $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_LATITUDE_TEXT'));
            return false;
        }
        if($expauserreq->get('c_admanager_useradd_latlong') && !$data['longitude']){
            $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_LONGITUDE_TEXT'));
            return false;
        }
          return true;
    }
    public function saveimg($logo_data,$data_user,$width,$height,$sep=5) {
        $config = ExpAutosProExpparams::getExpParams('config', '1');
            $sufix = mktime();
            $img_name = end(explode(".", strtolower($logo_data['name'])));
            $respons_img = $data_user . "_".$sep."_" . $sufix . "." . $img_name;
            $input_file = $logo_data['tmp_name'];
            $output_file = ExpAutosProExpparams::ImgAbsPathLogo() . $respons_img;
            $options_logo = array(
                'width' => $width,
                'height' => $height,
                'method' => $config->get('c_user_req_logoresmethod'),
                'type' => IMAGETYPE_JPEG,
                'percent' => $config->get('c_user_req_logopercent'),
                'halign' => $config->get('c_user_req_logohoralign'),
                'valign' => $config->get('c_user_req_logovertalign'),
            );
            $img_return = ExpAutosProExpparams::output($input_file, $output_file, $options_logo);
            return $img_return.",".$respons_img;
    }

    public function save($data) {
        $expuserparams = $this->getExpparams();
        $config = ExpAutosProExpparams::getExpParams('config', '1');
        $id = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('expuser.id');
        $isNew = true;
        $table = $this->getTable('Expuser', $prefix = 'ExpautosproTable');
        if ($id > 0) {
            $table->load($id);
            $isNew = false;
        }
        $logo_data = JRequest::getVar('logo', '', 'files', 'array');
        $logo_del = JRequest::getString('delete_logo');
        $logo_name = JRequest::getString('logo_name');
        $logowidth = $config->get('c_user_req_logowidth');
        $logoheight = $config->get('c_user_req_logoheight');
        $userphoto_data = JRequest::getVar('userphoto', '', 'files', 'array');
        $userphoto_del = JRequest::getString('delete_userphoto');
        $userphoto_name = JRequest::getString('userphoto_name');
        $photowidth = $config->get('c_user_req_photowidth');
        $photoheight = $config->get('c_user_req_photoheight');
        if ($userphoto_data['name']) {
            $img_return = $this->saveimg($userphoto_data,$data['userid'],$photowidth,$photoheight,3);
            $img_return = explode(",",$img_return);
            if ($img_return[0]) {
                $data['params']['expphoto'] = $img_return[1];
                $registry = new JRegistry;
                $registry->loadArray($data['params']);
                $data['params'] = (string) $registry;
            }
        }
        if ($userphoto_del) {
            $filepath = ExpAutosProExpparams::ImgAbsPathLogo() . $userphoto_name;
            ExpAutosProExpparams::UnlinkImg($filepath);
            $data['params']['expphoto'] = '';
            $registry = new JRegistry;
            $registry->loadArray($data['params']);
            $data['params'] = (string) $registry;
        }
        if ($logo_data['name']) {
            $img_return = $this->saveimg($logo_data,$data['userid'],$logowidth,$logoheight,2);
            $img_return = explode(",",$img_return);
            if ($img_return[0]) {
                $data['logo'] = $img_return[1];
            }
        }
        if ($logo_del) {
            $filepath = ExpAutosProExpparams::ImgAbsPathLogo() . $logo_name;
            ExpAutosProExpparams::UnlinkImg($filepath);
            $data['logo'] = '';
        }
        $data['state'] = (int)$expuserparams->get('c_user_req_autopublished');
        // Bind the data.
        if (!$table->bind($data)) {
            $this->setError($table->getError());
            return false;
        }
        if (!$table->check()) {
            $this->setError($table->getError());
            return false;
        }
        // Store the data.
        if (!$table->store()) {
            $this->setError($table->getError());
            return false;
        }
        return $table->id;
    }

}
