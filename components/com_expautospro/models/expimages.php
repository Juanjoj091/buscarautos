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
require_once JPATH_COMPONENT . '/helpers/expfields.php';
require_once JPATH_COMPONENT . '/helpers/helper.php';

class ExpAutosProModelExpimages extends JModelForm {

    protected $data;
    
    public function checkexpuser($expuser){
       $return =  ExpAutosProExpparams::expuser($expuser);
       return $return;
    }

    public function checkin($addId = null) {
        $table = JTable::getInstance('Expimages', $prefix = 'ExpautosproTable');
        return true;
    }
    
    function getExpimages(){
        $user	= JFactory::getUser();
        $expid = (int) JRequest::getInt('id', 0);
        
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id, name, ordering, description');
        $query->from('#__expautos_images');
        $query->where('catid = ' . (int) $expid);
        $query->order('ordering');
        $db->setQuery($query);
        $listimages = $db->loadObjectList();
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }
        return $listimages;
    }

    function getExpparams() {
        $expparams = ExpAutosProExpparams::getExpParams('config', 1);
        return $expparams;
    }

    function getExpgroup() {
        $user = JFactory::getUser();
        $UserId = (int) $user->get('id');
        if ($UserId > 0) {
            //$listusergroups = implode(',', JAccess::getGroupsByUser($UserId));
            $listusergroups = implode(',', JAccess::getGroupsByUser($UserId));
            $listusergroup = ExpAutosProExpparams::getExpgroupname($listusergroups);
        }else{
            echo JError::raiseWarning(500, JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_ONLYREGISTERED_TEXT'));
            return false;
        }
        return $listusergroup;
    }

    function getExpgroupid() {
        $user = JFactory::getUser();
        $UserId = (int) $user->id;
        if ($UserId > 0) {
            $usergroups = implode(',', $user->groups);
            $usergroupid = ExpAutosProExpparams::getExpgroupid($usergroups);
        }else{
            return false;
        }
        return $usergroupid;
    }

    function getExpgroupfields() {
        $user = JFactory::getUser();
        $UserId = (int) $user->get('id');
        if ($UserId > 0) {
            //$listusergroups = implode(',', $user->groups);
            $listusergroups = implode(',', JAccess::getGroupsByUser($UserId));
            $listusergroupid = ExpAutosProExpparams::getExpgroupid($listusergroups);
            $listgroupparams = ExpAutosProExpparams::getExpParams('userlevel', $listusergroupid);
        }else{
            return false;
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
        $userId = JFactory::getApplication()->getUserState('com_expautospro.edit.expimages.id');
        $userId = !empty($userId) ? $userId : (int) JFactory::getUser()->get('id');

        $this->setState('expimages.id', $userId);
        $this->setState('params', $params);
    }

    public function getData() {
        $addId = $this->getState('expimages.id');
        $user = JFactory::getUser();
        $loginUserId = (int) $user->get('id');
        $table = JTable::getInstance('Expimages', $prefix = 'ExpautosproTable');
        $return = $table->load($addId);

        $properties = $table->getProperties(1);
        $value = JArrayHelper::toObject($properties, 'JObject');

        return $value;
    }

    public function getForm($data = array(), $loadData = true) {
        // Get the form.
        $form = $this->loadForm('com_expautospro.expimages', 'expimages', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData() {
        $app	= JFactory::getApplication();
	$data	= $app->getUserState('com_expautospro.expimages.data', array());
        return $this->getData();
    }
    
    public function expaddvalid($addid,$expuser){
        $return = ExpAutosProFields::expaddcheck($addid,$expuser);
        if($return)
            return true;
        else
            return false;
    }
    
    public function save($data) {
        $application	= JFactory::getApplication();
        $expcatid = (int) JRequest::getInt('catid', null, '', 'array');
        $expisnew = (int) JRequest::getInt('expisnew', '');
        $user	= JFactory::getUser();
        $id = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('expimages.id');
        $isNew = $expisnew;
        $table = $this->getTable('Expimages', $prefix = 'ExpautosproTable');
        if ($id > 0) {
            $table->load($id);
        }

        if (!$table->bind($data)) {
            $this->setError($table->getError());
            return false;
        }

        // Check the data.
        if (!$table->check()) {
            $this->setError($table->getError());
            return false;
        }
        // Store the data.
        if (!$table->store()) {
            $this->setError($table->getError());
            return false;
        }
        $del_imgs = JRequest::getVar('del_image', array(), 'post', 'array');
        $imgupload = JRequest::getVar('adimages', array(), 'files', 'array');
        $orderimg = JRequest::getVar('imgordering', array(), 'post', 'array');
        $imgdescription = JRequest::getVar('imgdescription', array(), 'post', 'array');

        $config = ExpAutosProExpparams::getExpParams('config', '1');
        //$wtpath = $config->get('c_images_wt_imagename');

        $db = JFactory::getDBO();


        foreach ($orderimg as $i => $value) {
            $obj = new stdClass();
            $obj->id = (int) $i;
            $obj->ordering = (int)$value;
            $db->updateObject('#__expautos_images', $obj, 'id');
        }

        foreach ($imgdescription as $i => $value) {
            $obj = new stdClass();
            $obj->id = (int) $i;
            $obj->description = JFilterOutput::cleanText($value);
            $db->updateObject('#__expautos_images', $obj, 'id');
        }
        /* Add image */
        if ($imgupload) {
            foreach ($imgupload['name'] as $key => $val) {
                if (!empty($val)) {
                    $imgsize = $imgupload['size'][$key];
                    $name = $imgupload['name'][$key];
                    $tmpname = $imgupload['tmp_name'][$key];

                    if ($name) {
                        $size = getimagesize($tmpname);
                        $img_return = true;

                        if ($imgsize > $config->get('c_images_maxfilesize')) {
                            $imgsizeconvert = ExpAutosProExpparams::exp_convertsize($imgsize);
                            $imglimitsizeconvert = ExpAutosProExpparams::exp_convertsize($config->get('c_images_maxfilesize'));
                            $application->enqueueMessage(JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_ERROR_IMGSIZELIMIT_TEXT') . " $imgsizeconvert ." . JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_ERROR_IMGMAXSIZE_TEXT') . " $imglimitsizeconvert", 'notice');
                            $img_return = false;
                        }

                        if ($size[0] < $config->get('c_images_minsize_width')) {
                            $application->enqueueMessage(JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_ERROR_IMGWIDTHSMALL_TEXT') . ": $name", 'notice');
                            $img_return = false;
                        }

                        if ($size[1] < $config->get('c_images_minsize_height')) {
                            $application->enqueueMessage(JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_ERROR_IMGHEIGHTSMALL_TEXT') . ": $name", 'notice');
                            $img_return = false;
                        }

                        if ($img_return) {
                            $sufix = mktime();
                            $img_name = end(explode(".", strtolower($name)));
                            //$respons_img = $key . "_" . $sufix . "." . $img_name;
                            $respons_img =  $id. "_" . $key. "_" . $sufix . "." . $img_name;
                            $input_file = $tmpname;
                            $output_tmbfile = ExpAutosProExpparams::ImgAbsPathThumbs() . $respons_img;
                            $output_middlefile = ExpAutosProExpparams::ImgAbsPathMiddle() . $respons_img;
                            $output_bigfile = ExpAutosProExpparams::ImgAbsPathBig() . $respons_img;
                            $options_tmbfile = array(
                                'width' => $config->get('c_images_thumbsize_width'),
                                'height' => $config->get('c_images_thumbsize_height'),
                                'method' => $config->get('c_images_resmethod'),
                                'percent' => $config->get('c_images_percent'),
                                'halign' => $config->get('c_images_horalign'),
                                'valign' => $config->get('c_images_vertalign'),
                                'watermark' => 0,
                            );
                            $options_middlefile = array(
                                'width' => $config->get('c_images_middlesize_width'),
                                'height' => $config->get('c_images_middlesize_height'),
                                'method' => $config->get('c_images_resmethod'),
                                'percent' => $config->get('c_images_percent'),
                                'halign' => $config->get('c_images_horalign'),
                                'valign' => $config->get('c_images_vertalign'),
                                'watermark' => $config->get('c_images_wt_use'),
                            );
                            $img_return = ExpAutosProExpparams::output($input_file, $output_tmbfile, $options_tmbfile);
                            $img_return = ExpAutosProExpparams::output($input_file, $output_middlefile, $options_middlefile);

                            if ($config->get('c_images_maxsize_width') == 0 && $config->get('c_images_maxsize_height') == 0 || $size[0] < $config->get('c_images_maxsize_width') || $size[1] < $config->get('c_images_maxsize_height')) {
                                if ($config->get('c_images_wt_use')) {
                                    $options_bigfile = array(
                                        'percent' => 0,
                                        'method' => $config->get('c_images_resmethod'),
                                        'width' => $size[0],
                                        'height' => $size[1],
                                        'halign' => 0,
                                        'valign' => 0,
                                        'watermark' => $config->get('c_images_wt_use'),
                                    );
                                    $img_return = ExpAutosProExpparams::output($input_file, $output_bigfile, $options_bigfile);
                                } else {
                                    move_uploaded_file($input_file, $output_bigfile);
                                }
                            } else {

                                $options_bigfile = array(
                                    'percent' => $config->get('c_images_percent'),
                                    'method' => $config->get('c_images_resmethod'),
                                    'width' => $config->get('c_images_maxsize_width'),
                                    'height' => $config->get('c_images_maxsize_height'),
                                    'halign' => $config->get('c_images_horalign'),
                                    'valign' => $config->get('c_images_vertalign'),
                                    'watermark' => $config->get('c_images_wt_use'),
                                );
                                $img_return = ExpAutosProExpparams::output($input_file, $output_bigfile, $options_bigfile);
                            }

                            if ($img_return) {
                                $obj = new stdClass();
                                $obj->catid = (int) $expcatid;
                                $obj->name = $respons_img;
                                $obj->ordering = $key;
                                $obj->description = JFilterOutput::cleanText($imgdescription[$key]);
                                $db->insertObject('#__expautos_images', $obj);
                            }
                        }
                    }
                }
            }
            
        }
        if ($del_imgs) {
            foreach ($del_imgs as $i => $del_img) {
                $filepathtmb = ExpAutosProExpparams::ImgAbsPathThumbs() . $del_img;
                $filepathmiddle = ExpAutosProExpparams::ImgAbsPathMiddle() . $del_img;
                $filepathbig = ExpAutosProExpparams::ImgAbsPathBig() . $del_img;

                $unlinktmb = ExpAutosProExpparams::UnlinkImg($filepathtmb);
                $unlinkmiddle = ExpAutosProExpparams::UnlinkImg($filepathmiddle);
                $unlinkbig = ExpAutosProExpparams::UnlinkImg($filepathbig);

                $query = $db->getQuery(true);
                $query->delete();
                $query->from('#__expautos_images');
                $query->where('id = ' . (int) $i);
                $db->setQuery((string) $query);
                if ($db->Query()) {
                    
                } else {
                    $this->setError(JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_ERROR_DELETEIMGDATABASE_TEXT'));
                }
            }
        }
        
        ExpAutosProHelper::getExpdataImg($expcatid);
        //echo $user->id."---user id --".$id."-- id ---".$isNew."-- new";
        //die();
        ExpAutosProExpparams::expsend_mail($user->id,$id,$isNew);
        
        return (int)$expcatid;
    }

    public function getExpItemid() {
        $expitemid = ExpAutosProExpparams::getExpLinkItemid();
        return $expitemid;
    }

}
