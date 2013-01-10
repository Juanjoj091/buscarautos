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

class ExpAutosProModelExpuser extends JModelAdmin {

    protected $text_prefix = 'COM_EXPAUTOSPRO_USERS';

    protected function canDelete($record) {
        $user = JFactory::getUser();
        $filepath = ExpAutosProImages::ImgAbsPathLogo() . $record->logo;
        ExpAutosProImages::UnlinkImg($filepath);

        if (!empty($record->catid)) {
            return $user->authorise('core.delete', 'com_expautospro.expuser.' . (int) $record->catid);
        } else {
            return parent::canDelete($record);
        }
    }

    protected function canEditState($record) {
        $user = JFactory::getUser();

        if (!empty($record->catid)) {
            return $user->authorise('core.edit.state', 'com_expautospro.expuser.' . (int) $record->catid);
        } else {
            return parent::canEditState($record);
        }
    }

    public function getTable($type = 'Expuser', $prefix = 'ExpautosproTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true) {
        $form = $this->loadForm('com_expautospro.expuser', 'expuser', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        if ($this->getState('expuser.id')) {
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
        $data = JFactory::getApplication()->getUserState('com_expautospro.edit.expuser.data', array());

        if (empty($data)) {
            $data = $this->getItem();
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

    public function save($data) {
        $id = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('expuser.id');
        $isNew = true;
        $table = $this->getTable();
        if ($id > 0) {
            $table->load($id);
            $isNew = false;
        }
        //print_r($data);
        //die();
        /* Logo */
        $logo_data = JRequest::getVar('logo', '', 'files', 'array');
        $logo_del = JRequest::getString('delete_logo');
        $logo_name = JRequest::getString('logo_name');
        if (isset($logo_data['name'])) {
            $config = ExpAutosProImages::getExpParams('config', '1');
            $sufix = mktime();
            $img_name = end(explode(".", strtolower($logo_data['name'])));
            $respons_img = $data['userid'] . "_2_" . $sufix . "." . $img_name;
            $input_file = $logo_data['tmp_name'];
            $output_file = ExpAutosProImages::ImgAbsPathLogo() . $respons_img;
            $options_logo = array(
                'width' => $config->get('c_user_req_logowidth'),
                'height' => $config->get('c_user_req_logoheight'),
                'method' => $config->get('c_user_req_logoresmethod'),
                'type' => IMAGETYPE_JPEG,
                'percent' => $config->get('c_user_req_logopercent'),
                'halign' => $config->get('c_user_req_logohoralign'),
                'valign' => $config->get('c_user_req_logovertalign'),
            );
            $img_return = ExpAutosProImages::output($input_file, $output_file, $options_logo);
            if ($img_return) {
                $data['logo'] = $respons_img;
            }
        }
        if ($logo_del) {
            $filepath = ExpAutosProImages::ImgAbsPathLogo() . $logo_name;
            ExpAutosProImages::UnlinkImg($filepath);
            $data['logo'] = '';
        }
        /* Photo */
        $userphoto_data = JRequest::getVar('userphoto', '', 'files', 'array');
        $userphoto_del = JRequest::getString('delete_userphoto');
        $userphoto_name = JRequest::getString('userphoto_name');
        if (isset($userphoto_data['name'])) {
            $config = ExpAutosProImages::getExpParams('config', '1');
            $sufix = mktime();
            $img_name = end(explode(".", strtolower($userphoto_data['name'])));
            $respons_img = $data['userid'] . "_3_" . $sufix . "." . $img_name;
            $input_file = $userphoto_data['tmp_name'];
            $output_file = ExpAutosProImages::ImgAbsPathLogo() . $respons_img;
            $options_logo = array(
                'width' => $config->get('c_user_req_photowidth'),
                'height' => $config->get('c_user_req_photoheight'),
                'method' => $config->get('c_user_req_logoresmethod'),
                'type' => IMAGETYPE_JPEG,
                'percent' => $config->get('c_user_req_logopercent'),
                'halign' => $config->get('c_user_req_logohoralign'),
                'valign' => $config->get('c_user_req_logovertalign'),
            );
            $img_return = ExpAutosProImages::output($input_file, $output_file, $options_logo);
            if ($img_return) {
                $data['params']['expphoto'] = $respons_img;
                $registry = new JRegistry;
                $registry->loadArray($data['params']);
                $data['params'] = (string) $registry;
            }
        }
        if ($userphoto_del) {
            $filepath = ExpAutosProImages::ImgAbsPathLogo() . $userphoto_name;
            ExpAutosProImages::UnlinkImg($filepath);
            $data['params']['expphoto'] = '';
            $registry = new JRegistry;
            $registry->loadArray($data['params']);
            $data['params'] = (string) $registry;
        }

            if (!$table->bind($data)) {
                $this->setError($table->getError());
                return false;
            }

            $this->prepareTable($table);

            if (!$table->check()) {
                $this->setError($table->getError());
                return false;
            }

            if (!$table->store()) {
                $this->setError($table->getError());
                return false;
            }
        $this->setState('expuser.id', $table->id);
        return true;
    }

    function exptask(&$pks, $value = 1, $expmodel='') {

        $user = JFactory::getUser();
        $table = $this->getTable();
        $pks = (array) $pks;

        foreach ($pks as $i => $pk) {
            if ($table->load($pk)) {
                if (!$this->canEditState($table)) {
                    unset($pks[$i]);
                    JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
                }
            }
        }

        if (!$table->exptask($pks, $value, $user->get('id'), $expmodel)) {
            $this->setError($table->getError());
            return false;
        }

        return true;
    }

}
