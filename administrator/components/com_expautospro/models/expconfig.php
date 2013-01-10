<?php

/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');
require_once JPATH_COMPONENT . '/helpers/expimages.php';

class ExpAutosProModelExpconfig extends JModelAdmin {

    protected $text_prefix = 'COM_EXPAUTOSPRO_CONFIG';

    protected function canDelete($record) {
        $user = JFactory::getUser();

        if (!empty($record->catid)) {
            return $user->authorise('core.delete', 'com_expautospro.expconfig.' . (int) $record->catid);
        } else {
            return parent::canDelete($record);
        }
    }

    protected function canEditState($record) {
        $user = JFactory::getUser();

        if (!empty($record->catid)) {
            return $user->authorise('core.edit.state', 'com_expautospro.expconfig.' . (int) $record->catid);
        } else {
            return parent::canEditState($record);
        }
    }

    public function getTable($type = 'Expconfig', $prefix = 'ExpautosproTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true) {
        $form = $this->loadForm('com_expautospro.expconfig', 'expconfig', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData() {
        $data = JFactory::getApplication()->getUserState('com_expautospro.edit.expconfig.data', array());

        //print_r($data);
        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    protected function prepareTable(&$table) {
        
    }

    public function expresimg($folder_num, $folder_from) {
        if ($folder_num) {
            $config = ExpAutosProImages::getExpParams('config', '1');
            $inputfolder = ExpAutosProImages::ImgAbsPathBig();
            switch ($folder_num) {
                case 1:
                    $filepath = ExpAutosProImages::ImgAbsPathThumbs();
                    $expwidth = $config->get('c_images_thumbsize_width');
                    $expheight = $config->get('c_images_thumbsize_height');
                    $expwatermark = 0;
                    break;
                case 2:
                    $filepath = ExpAutosProImages::ImgAbsPathMiddle();
                    $expwidth = $config->get('c_images_middlesize_width');
                    $expheight = $config->get('c_images_middlesize_height');
                    $expwatermark = $config->get('c_images_wt_use');
                    break;
                case 3:
                    $filepath = ExpAutosProImages::ImgAbsPathBig();
                    $expwidth = $config->get('c_images_maxsize_width');
                    $expheight = $config->get('c_images_maxsize_height');
                    $expwatermark = $config->get('c_images_wt_use');
                    break;
            }
            $options_img = array(
                'width' => $expwidth,
                'height' => $expheight,
                'method' => $config->get('c_images_resmethod'),
                'percent' => $config->get('c_images_percent'),
                'halign' => $config->get('c_images_horalign'),
                'valign' => $config->get('c_images_vertalign'),
                'watermark' => $expwatermark,
            );
            $exp_from = $inputfolder;
            if ($folder_from) {
                $exp_from = $filepath;
            }
            $allimgs = JFolder::files($inputfolder, '.jpg$', false, false);
            foreach ($allimgs as $value) {
                ExpAutosProImages::output($exp_from . $value, $filepath . $value, $options_img);
            }
            return true;
        }
    }

    public function expinstall() {
        $this->setState('action', 'expinstall');
        JClientHelper::setCredentialsFromRequest('ftp');
        $app = JFactory::getApplication();

        switch (JRequest::getWord('installtype')) {
            case 'upload':
                $package = $this->_getPackageFromUpload();
                break;
            default:
                $app->setUserState('com_installer.message', JText::_('COM_EXPAUTOSPRO_NO_INSTALL_TYPE_FOUND'));
                return false;
                break;
        }

        if (!$package) {
            $app->setUserState('com_installer.message', JText::_('COM_EXPAUTOSPRO_UPLOADSKIN_UNABLE_TO_FIND_INSTALL_PACKAGE'));
            return false;
        } else {
            // Package installed sucessfully
            $msg = JText::sprintf('COM_EXPAUTOSPRO_CONFIG_SKIN_INSTALL_SUCCESS', basename($package['dir']), $package['type']);
            $result = true;
        }

        // Set some model state values
        $app = JFactory::getApplication();
        $app->enqueueMessage($msg);

        return $result;
    }

    protected function _getPackageFromUpload() {
        jimport('joomla.filesystem.archive');
        jimport('joomla.filesystem.file');
        $userfile = JRequest::getVar('install_package', null, 'files', 'array');

        if (!(bool) ini_get('file_uploads')) {
            JError::raiseWarning('', JText::_('COM_EXPAUTOSPRO_MSG_INSTALL_WARNINSTALLFILE'));
            return false;
        }

        if (!extension_loaded('zlib')) {
            JError::raiseWarning('', JText::_('COM_EXPAUTOSPRO_MSG_INSTALL_WARNINSTALLZLIB'));
            return false;
        }

        if (!is_array($userfile)) {
            JError::raiseWarning('', JText::_('COM_EXPAUTOSPRO_MSG_INSTALL_NO_FILE_SELECTED'));
            return false;
        }

        if ($userfile['error'] || $userfile['size'] < 1) {
            JError::raiseWarning('', JText::_('COM_EXPAUTOSPRO_MSG_INSTALL_WARNINSTALLUPLOADERROR'));
            return false;
        }

        $config = JFactory::getConfig();
        $tmp_dest = $config->get('tmp_path') . '/' . $userfile['name'];
        $tmp_src = $userfile['tmp_name'];

        $uploaded = JFile::upload($tmp_src, $tmp_dest);

        $package_unpack = JInstallerHelper::unpack($tmp_dest);
        $path_skin = JPATH_ROOT . "/components/com_expautospro/skins/" . $package_unpack['type'];
        $package = JArchive::extract($tmp_dest, $path_skin);
        if ($package) {
            JInstallerHelper::cleanupInstall($package_unpack['packagefile'], $package_unpack['extractdir']);
        }
        return $package_unpack;
    }

    public function expdeleteskin($delskin) {
        jimport('joomla.filesystem.folder');
        $path_skin = JPATH_ROOT . "/components/com_expautospro/" . $delskin;
        
        if(JFolder::delete($path_skin)){
            $msg = JText::sprintf('COM_EXPAUTOSPRO_CONFIG_SKIN_REMOVE_SUCCESS', basename($path_skin));
            $app = JFactory::getApplication();
            $app->enqueueMessage($msg);
            $result = true;
            
        }else{
            JError::raiseWarning('', JText::_('COM_EXPAUTOSPRO_PROBLEM_DELETE_FOLDER_TEXT'));
            return false;
        }
        return $result;
    }

}
