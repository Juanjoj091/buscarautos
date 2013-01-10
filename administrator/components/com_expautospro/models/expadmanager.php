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

jimport('joomla.application.component.modeladmin');
require_once JPATH_COMPONENT . '/helpers/expimages.php';

class ExpAutosProModelExpadmanager extends JModelAdmin {

    protected $text_prefix = 'COM_EXPAUTOSPRO_ADMANAGER';

    protected function canDelete($record) {
        $user = JFactory::getUser();
        $db = JFactory::getDBO();
        
        /* Search Attached Files */
        $ad_params = ExpAutosProImages::getExpParams('admanager', $record->id);
        $params_file = $ad_params->get('expfile');
        if($params_file){
        $link_file = ExpAutosProImages::FilesAbsPath().$params_file;
        unlink($link_file);
        }

        /* Search Image Name */
        $query = $db->getQuery(true);
        $query->select('name');
        $query->from('#__expautos_images');
        $query->where('catid = ' . (int) $record->id);
        // Get the options.
        $db->setQuery($query);
        $imgnames = $db->loadResultArray();
        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }
        /* Delete Images */
        if($imgnames){
            foreach ($imgnames as $imgname) {
                $filepathtmb = ExpAutosProImages::ImgAbsPathThumbs() . $imgname;
                $filepathmiddle = ExpAutosProImages::ImgAbsPathMiddle() . $imgname;
                $filepathbig = ExpAutosProImages::ImgAbsPathBig() . $imgname;

                $unlinktmb = ExpAutosProImages::UnlinkImg($filepathtmb);
                $unlinkmiddle = ExpAutosProImages::UnlinkImg($filepathmiddle);
                $unlinkbig = ExpAutosProImages::UnlinkImg($filepathbig);
            }
            $query_del = $db->getQuery(true);
            $query_del->delete();
            $query_del->from('#__expautos_images');
            $query_del->where('catid = ' . (int) $record->id);
            $db->setQuery((string) $query_del);
            if ($db->Query()) {
            } else {
                $this->setError(JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_ERROR_DELETEIMGDATABASE_TEXT'));
            }
        }

        if (!empty($record->catid)) {
            return $user->authorise('core.delete', 'com_expautospro.expadmanager.' . (int) $record->catid);
        } else {
            return parent::canDelete($record);
        }
    }

    protected function canEditState($record) {
        $user = JFactory::getUser();

        if (!empty($record->catid)) {
            return $user->authorise('core.edit.state', 'com_expautospro.expadmanager.' . (int) $record->catid);
        } else {
            return parent::canEditState($record);
        }
    }

    public function getTable($type = 'Expadmanager', $prefix = 'ExpautosproTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true) {
        $form = $this->loadForm('com_expautospro.expadmanager', 'expadmanager', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        if ($this->getState('expadmanager.id')) {
            $form->setFieldAttribute('catid', 'action', 'core.edit');
        } else {
            $form->setFieldAttribute('catid', 'action', 'core.create');
        }

        if (!$this->canEditState((object) $data)) {
            $form->setFieldAttribute('ordering', 'disabled', 'true');
            $form->setFieldAttribute('state', 'disabled', 'true');

            $form->setFieldAttribute('ordering', 'filter', 'unset');
            $form->setFieldAttribute('state', 'filter', 'unset');
        }

        return $form;
    }

    protected function loadFormData() {
        $data = JFactory::getApplication()->getUserState('com_expautospro.edit.expadmanager.data', array());
        $expdata = JRequest::getInt('expcat', 0);
        if (empty($data)) {
            $data = $this->getItem();

            if ($this->getState('expadmanager.id') == 0) {
                $app = JFactory::getApplication();
                $data->set('catid', JRequest::getInt('catid', $app->getUserState('com_expautospro.expadmanagers.filter.category_id')));
            }
            if ($expdata > 0) {
                $data->set('catid', $expdata);
            }
        }

        return $data;
    }

    protected function prepareTable(&$table) {
        
    }

    protected function getReorderConditions($table) {
        $condition = array();
        $condition[] = 'catid = ' . (int) $table->catid;
        $condition[] = 'state >= 0';
        return $condition;
    }

    public function makes_build($buildcat, $pks, $makeaction) {

        // Sanitize user ids.
        $pks = array_unique($pks);
        JArrayHelper::toInteger($pks);

        // Remove any values of zero.
        if (array_search(0, $pks, true)) {
            unset($pks[array_search(0, $pks, true)]);
        }
        if (empty($pks)) {
            $this->setError(JText::_('COM_EXPAUTOSPRO_MAKE_NO_ITEM_SELECTED'));
            return false;
        }
        if (empty($buildcat)) {
            $this->setError(JText::_('COM_EXPAUTOSPRO_MAKE_NO_CATEGOR_SELECTED'));
            return false;
        }

        $done = false;

        if (!empty($buildcat)) {
            if ($makeaction == 'c' && !$this->buildCopy($buildcat, $pks)) {
                return false;
            } else if ($makeaction == 'm' && !$this->buildCopy($buildcat, $pks, '1')) {
                return false;
            }
            $done = true;
        }

        if (!$done) {
            $this->setError(JText::_('COM_EXPAUTOSPRO_INSUFFICIENT_COPYMOVE_INFORMATION'));
            return false;
        }

        return true;
    }

    public function buildCopy($value, $pks, $variable = 0) {
        $parts = $value;
        $table = $this->getTable();
        $db = $this->getDbo();
        $user = JFactory::getUser();


        // Parent exists so we let's proceed
        while (!empty($pks)) {
            // Pop the first id off the stack
            $pk = array_shift($pks);

            $table->reset();
            // Check that the row actually exists
            if (!$table->load($pk)) {
                if ($error = $table->getError()) {
                    // Fatal error
                    $this->setError($error);
                    return false;
                } else {
                    // Not fatal error
                    $this->setError(JText::sprintf('JGLOBAL_BATCH_MOVE_ROW_NOT_FOUND', $pk));
                    continue;
                }
            }
            if (!$variable) {
                $table->id = 0;
            }
            $table->catid = $value;

            // Store the row.
            if (!$table->store()) {
                $this->setError($table->getError());
                return false;
            }
            $count--;
        }


        // Clear the component's cache
        $cache = JFactory::getCache('com_expautospro');
        $cache->clean();

        return true;
    }

    function exptask(&$pks, $value = 1, $expmodel='') {
        // Initialise variables.
        $user = JFactory::getUser();
        $table = $this->getTable();
        $pks = (array) $pks;

        // Access checks.
        foreach ($pks as $i => $pk) {
            if ($table->load($pk)) {
                if (!$this->canEditState($table)) {
                    // Prune items that you can't change.
                    unset($pks[$i]);
                    JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
                }
            }
        }

        // Attempt to change the state of the records.
        if (!$table->exptask($pks, $value, $user->get('id'), $expmodel)) {
            $this->setError($table->getError());
            return false;
        }

        return true;
    }

    public function save($data) {
        $id = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('expadmanager.id');
        $application	= JFactory::getApplication();
        $isNew = true;
        $table = $this->getTable();
        if ($id > 0) {
            $table->load($id);
            $isNew = false;
        }
        $file_data = JRequest::getVar('expfile', '', 'files', 'array');
        $file_del = JRequest::getString('delete_expfile');
        $file_name = JRequest::getString('expfile_name');
        if (isset($file_data['name'])) {
            $sufix = mktime();
            $file_name = end(explode(".", strtolower($file_data['name'])));
            $respons_file = $data['user'] . "_" . $sufix . "." . $file_name;
            $input_file = $file_data['tmp_name'];
            $output_file = ExpAutosProImages::FilesAbsPath() . $respons_file;
            if(move_uploaded_file($input_file, $output_file)){
                $data['params']['expfile'] = $respons_file;
                $registry = new JRegistry;
                $registry->loadArray($data['params']);
                $data['params'] = (string) $registry;
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

        if (!$table->bind($data)) {
            $this->setError($table->getError());
            return false;
        }

        // Prepare the row for saving
        $this->prepareTable($table);

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

        $config = & ExpAutosProImages::getExpParams('config', '1');
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
                            $imgsizeconvert = ExpAutosProImages::exp_convertsize($imgsize);
                            $imglimitsizeconvert = ExpAutosProImages::exp_convertsize($config->get('c_images_maxfilesize'));
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
                            $respons_img =  $table->id. "_" . $key. "_" . $sufix . "." . $img_name;
                            $input_file = $tmpname;
                            $output_tmbfile = ExpAutosProImages::ImgAbsPathThumbs() . $respons_img;
                            $output_middlefile = ExpAutosProImages::ImgAbsPathMiddle() . $respons_img;
                            $output_bigfile = ExpAutosProImages::ImgAbsPathBig() . $respons_img;
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
                            $img_return = ExpAutosProImages::output($input_file, $output_tmbfile, $options_tmbfile);
                            $img_return = ExpAutosProImages::output($input_file, $output_middlefile, $options_middlefile);

                            if ($config->get('c_images_maxsize_width') == 0 && $config->get('c_images_maxsize_height') == 0 || $size[0] < $config->get('c_images_maxsize_width') || $size[1] < $config->get('c_images_maxsize_height')) {
                                if ($config->usewatermark) {
                                    $options_bigfile = array(
                                        'percent' => 0,
                                        'method' => $config->get('c_images_resmethod'),
                                        'width' => $size[0],
                                        'height' => $size[1],
                                        'halign' => 0,
                                        'valign' => 0,
                                        'watermark' => $config->get('c_images_wt_use'),
                                    );
                                    $img_return = ExpAutosProImages::output($input_file, $output_bigfile, $options_bigfile);
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
                                $img_return = ExpAutosProImages::output($input_file, $output_bigfile, $options_bigfile);
                            }

                            if ($img_return) {
                                $obj = new stdClass();
                                $obj->catid = (int) $table->id;
                                $obj->name = $respons_img;
                                $obj->ordering = $key;
                                $obj->description = $imgdescription[$key];
                                $db->insertObject('#__expautos_images', $obj);
                            }
                        }
                    }
                }
            }
        }
        $table_img = $this->getTable('Expimages');
        if ($del_imgs) {
            foreach ($del_imgs as $i => $del_img) {
                $filepathtmb = ExpAutosProImages::ImgAbsPathThumbs() . $del_img;
                $filepathmiddle = ExpAutosProImages::ImgAbsPathMiddle() . $del_img;
                $filepathbig = ExpAutosProImages::ImgAbsPathBig() . $del_img;

                $unlinktmb = ExpAutosProImages::UnlinkImg($filepathtmb);
                $unlinkmiddle = ExpAutosProImages::UnlinkImg($filepathmiddle);
                $unlinkbig = ExpAutosProImages::UnlinkImg($filepathbig);

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
        $table_img->reorder('catid = '.(int)$table->id);
        
            $imgcount = ExpAutosProImages::getExpcount('images', 'catid', $table->id);
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('name');
            $query->from('#__expautos_images');
            $query->where('catid = '.(int)$table->id);
            $query->where('ordering = 1');
            $db->setQuery($query);
            $mainimage = $db->loadResult();
            if ($db->getErrorNum()) {
                JError::raiseWarning(500, $db->getErrorMsg());
            }
            //print_r($mainimage."---".$imgcount);
            //die();
            $obj = new stdClass();
            $obj->id = (int) $table->id;
            $obj->imgcount = (int)$imgcount;
            $obj->imgmain = $mainimage;
            $db->updateObject('#__expautos_admanager', $obj, 'id');
        
        $this->setState('expadmanager.id', $table->id);
        return true;
    }

}
