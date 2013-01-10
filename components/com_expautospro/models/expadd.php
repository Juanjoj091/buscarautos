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
jimport('joomla.application.component.modelitem');
jimport('joomla.event.dispatcher');
jimport('joomla.plugin.helper');
require_once JPATH_COMPONENT . '/helpers/expparams.php';
require_once JPATH_COMPONENT . '/helpers/helper.php';

class ExpAutosProModelExpadd extends JModelForm {

    protected $data;
    
    public function checkexpuser($expuser){
       $return =  ExpAutosProExpparams::expuser($expuser);
       return $return;
    }

    public function checkin($addId = null) {
        $table = JTable::getInstance('Expadmanager', $prefix = 'ExpautosproTable');
        return true;
    }

    function getExpparams() {
        $expparams = ExpAutosProExpparams::getExpParams('config', 1);
        return $expparams;
    }

    function getExpgroup() {
        $user = JFactory::getUser();
        $UserId = (int) $user->get('id');
        $listusergroup='';
        if ($UserId > 0) {
            $listusergroups = implode(',', JAccess::getGroupsByUser($UserId));
            $listusergroup = ExpAutosProExpparams::getExpgroupname($listusergroups);
            //print_r($listusergroup);
        }
        return $listusergroup;
    }

    function getExpgroupid() {
        $user = JFactory::getUser();
        $UserId = (int) $user->id;
        $usergroupid='';
        if ($UserId > 0) {
            $usergroups = implode(',', $user->groups);
            $usergroupid = ExpAutosProExpparams::getExpgroupid($usergroups);
            //print_r($usergroupid);
        }
        return $usergroupid;
    }

    function getExpgroupfields() {
        $user = JFactory::getUser();
        $UserId = (int) $user->get('id');
        $listgroupparams='';
        if ($UserId > 0) {
            $listusergroups = implode(',', JAccess::getGroupsByUser($UserId));
            //print_r($listusergroups);
            $listusergroupid = ExpAutosProExpparams::getExpgroupid($listusergroups);
            $listgroupparams = ExpAutosProExpparams::getExpParams('userlevel', $listusergroupid);
        }
        return $listgroupparams;
    }

    function getExpcategoryfields($data='') {
        $expcat = JRequest::getInt('expcat', 0);
        if($expcat){
            $catid_val = $expcat;
        }elseif($this->getData()->catid){
            $catid_val = $this->getData()->catid;            
        }else{
            $catid_val = $data;
        }
        $listcatfields = ExpAutosProExpparams::getCatParams($catid_val);
        return $listcatfields;
    }

    protected function populateState() {
        $params = JFactory::getApplication()->getParams('com_expautospro');
        $addid = JFactory::getApplication()->getUserState('com_expautospro.edit.expadd.id');

        $this->setState('expadd.id', $addid);
        $this->setState('params', $params);
    }

    public function getData() {
        $addId = $this->getState('expadd.id');
        $user = JFactory::getUser();
        $loginUserId = (int) $user->get('id');
        $table = JTable::getInstance('Expadmanager', $prefix = 'ExpautosproTable');
        $return = $table->load($addId);

        $properties = $table->getProperties(1);
        $value = JArrayHelper::toObject($properties, 'JObject');
        $registry = new JRegistry($value->params);
	$value->params = $registry->toArray();
        return $value;
    }

    public function getForm($data = array(), $loadData = true) {
        // Get the form.
        $form = $this->loadForm('com_expautospro.expadd', 'expadd', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData() {
        $app	= JFactory::getApplication();
	$data	= $app->getUserState('com_expautospro.expadd.data', array());
        return $this->getData();
    }
    
    public function expvalid($data){
        $expaddreq = $this->getExpparams();
        $expcatfields = $this->getExpcategoryfields((int)$data['catid']);
        if(!$data['user']){
            $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_DEALERINFO_ERROR_USERID_TEXT'));
            return false;
        }
        if(!$data['catid']){
            $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_CATID_TEXT'));
            return false;
        }
        if($expcatfields->get('usemakes')){
            if($expaddreq->get('c_admanager_req_makes') && !$data['make']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_MAKE_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('usemodels')){
            if($expaddreq->get('c_admanager_req_models') && empty($data['model']) && empty($data['expyourmodel'])){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_MODEL_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('usespecificmodel')){
            if($expaddreq->get('c_admanager_req_specificmodel') && !$data['specificmodel']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_SPECMODEL_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('usebodytype')){
            if($expaddreq->get('c_admanager_req_bodytype') && !$data['bodytype']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_BODYTYPE_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('usedrive')){
            if($expaddreq->get('c_admanager_req_drive') && !$data['drive']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_DRIVE_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('usefuel')){
            if($expaddreq->get('c_admanager_req_fuel') && !$data['fuel']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_FUEL_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('usetrans')){
            if($expaddreq->get('c_admanager_req_trans') && !$data['trans']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_TRANS_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('useextrafield1')){
            if($expaddreq->get('c_admanager_req_extrafield1') && !$data['extrafield1']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_EXTRAFIELD1_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('useextrafield2')){
            if($expaddreq->get('c_admanager_req_extrafield2') && !$data['extrafield2']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_EXTRAFIELD2_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('useextrafield3')){
            if($expaddreq->get('c_admanager_req_extrafield3') && !$data['extrafield3']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_EXTRAFIELD3_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('usecondition')){
            if($expaddreq->get('c_admanager_req_condition') && !$data['condition']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_CONDITION_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('useextcolor')){
            if($expaddreq->get('c_admanager_req_extcolor') && !$data['extcolor']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_EXTCOLOR_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('usespecificcolor')){
            if($expaddreq->get('c_admanager_req_specificcolor') && !$data['specificcolor']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_SPECCOLOR_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('useintcolor')){
            if($expaddreq->get('c_admanager_req_intcolor') && !$data['intcolor']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_INTCOLOR_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('usemonth')){
            if($expaddreq->get('c_admanager_req_month') && !$data['month']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_MONTH_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('useyear')){
            if($expaddreq->get('c_admanager_req_year') && !$data['year']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_YEAR_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('usevincode')){
            if($expaddreq->get('c_admanager_req_vincode') && !$data['vincode']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_VINCODE_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('usemileage')){
            if($expaddreq->get('c_admanager_req_mileage') && !$data['mileage']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_MILEAGE_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('usedisplacement')){
            if($expaddreq->get('c_admanager_req_displacement') && (($data['displacement'][0]=='' || $data['displacement'][2] == '') || ($data['displacement'][0]=='0' && $data['displacement'][2] == '0'))){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_DISPLACEMENT_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('useengine')){
            if($expaddreq->get('c_admanager_req_engine') && !$data['engine']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_ENGINE_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('usestocknum')){
            if($expaddreq->get('c_admanager_req_stocknum') && !$data['stocknum']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_STOCKNUMBER_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('useprice')){
            if($expaddreq->get('c_admanager_req_price') && !$data['price']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_PRICE_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('useprice')){
            if($data['bprice'] > $data['price']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_BPRICEMORE_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('usecountry')){
            if($expaddreq->get('c_admanager_req_country') && !$data['country']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_COUNTRY_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('usestate')){
            if($expaddreq->get('c_admanager_req_state') && !$data['expstate']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_STATE_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('usecity')){
            if($expaddreq->get('c_admanager_req_city') && !$data['city']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_CITY_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('usestreet')){
            if($expaddreq->get('c_admanager_req_street') && !$data['street']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_STREET_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('usezipcode')){
            if($expaddreq->get('c_admanager_req_zipcode') && !$data['zipcode']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_DEALERINFO_ERROR_ZIPCODE_TEXT'));
                return false;
            }
        }
        if($expcatfields->get('usegooglemaps')){
            if($expaddreq->get('c_admanager_add_latlong') && !$data['latitude']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_LATITUDE_TEXT'));
                return false;
            }
            if($expaddreq->get('c_admanager_add_latlong') && !$data['longitude']){
                $this->setError(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_LONGITUDE_TEXT'));
                return false;
            }
        }
       
          return true;
    }

    public function save($data) {
        $user = JFactory::getUser();
        $expuserparams = $this->getExpparams();
        $id = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('expadd.id');
        $ads_count = ExpAutosProHelper::getExpcount('admanager','user',$user->id,1);
        $group_fields = $this->getExpgroupfields();
        $isNew = true;
        
        //add new models
        if($data['expyourmodel'] && $group_fields->get('g_newmodel')){
            $expyourmodel = JFilterOutput::cleanText($data['expyourmodel']);
            $expyourmodel_alias = JFilterOutput::stringURLSafe($expyourmodel);
            $db = JFactory::getDBO();
            $obj = new stdClass();
            $obj->makeid = (int) $data['make'];
            $obj->name = $expyourmodel;
            $obj->ordering = 0;
            $obj->alias = $expyourmodel_alias;
            $obj->state = $group_fields->get('g_newmodelpublished');
            $obj->metakey = $expyourmodel;
            $obj->metadesc = $expyourmodel;
            $db->insertObject('#__expautos_model', $obj);

            $data['model'] = $db->insertid();
        }
        
        $table = $this->getTable('Expadmanager', $prefix = 'ExpautosproTable');
        if ($id > 0) {
            $table->load($id);
            $isNew = false;
        }
        
        $expallowext = $group_fields->get('g_file_ext');
        $expallowext = explode(",", $expallowext);
        $expmaxfilesize = $group_fields->get('g_filemax_size');
        $file_data = JRequest::getVar('expfile', '', 'files', 'array');
        $file_del = JRequest::getString('delete_expfile');
        $file_name = JRequest::getString('expfile_name');
        if (isset($file_data['name'])) {
            $sufix = mktime();
            $file_name = end(explode(".", strtolower($file_data['name'])));
            $respons_file = $data['user'] . "_" . $sufix . "." . $file_name;
            $input_file = $file_data['tmp_name'];
            $filesize = getimagesize($input_file);
            $output_file = ExpAutosProExpparams::FilesAbsPath() . $respons_file;
            if ($imgsize < $expmaxfilesize && in_array($file_name, $expallowext)){ 
                if(move_uploaded_file($input_file, $output_file)){
                    $data['params']['expfile'] = $respons_file;
                    $registry = new JRegistry;
                    $registry->loadArray($data['params']);
                    $data['params'] = (string) $registry;
                }
            }
            
        }else{
            $data['params']['expfile'] = $file_name;
            $registry = new JRegistry;
            $registry->loadArray($data['params']);
            $data['params'] = (string) $registry;
        }
        if ($file_del) {
            $filepath = ExpAutosProImages::FilesAbsPath() . $file_name;
            ExpAutosProImages::UnlinkImg($filepath);
            $data['params']['expfile'] = '';
            $registry = new JRegistry;
            $registry->loadArray($data['params']);
            $data['params'] = (string) $registry;
        }
        
        
        if((int)$group_fields->get('g_adscount') != 0 && $group_fields->get('g_adscount') < $ads_count && $isNew){
            return false;
        }
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
        //ExpAutosProExpparams::expsend_mail($user->id,$table->id,$isNew);
        return $table->id;
    }

    public function getExpItemid() {
        $expitemid = ExpAutosProExpparams::getExpLinkItemid();
        return $expitemid;
    }
    
    protected function cleanCache($group = NULL, $client_id = 0){
		parent::cleanCache('com_expautospro');
		parent::cleanCache('mod_expautospro_carfax');
		parent::cleanCache('mod_expautospro_categories');
		parent::cleanCache('mod_expautospro_dealersearch');
		parent::cleanCache('mod_expautospro_exprss');
		parent::cleanCache('mod_expautospro_googlemap');
		parent::cleanCache('mod_expautospro_images');
		parent::cleanCache('mod_expautospro_images_sumo');
		parent::cleanCache('mod_expautospro_jqgallery');
		parent::cleanCache('mod_expautospro_keyword');
		parent::cleanCache('mod_expautospro_moogallery');
		parent::cleanCache('mod_expautospro_mortgage');
		parent::cleanCache('mod_expautospro_paypal');
		parent::cleanCache('mod_expautospro_search');
		parent::cleanCache('mod_expautospro_search_vert');
		parent::cleanCache('mod_expautospro_shortlist');
		parent::cleanCache('mod_expautospro_slideshow_diapo');
		parent::cleanCache('mod_expautospro_stats');
	}

}
