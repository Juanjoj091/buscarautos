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

jimport('joomla.application.component.modelform');

class ExpAutosProModelExpsource extends JModelForm {

    protected $text_prefix = 'COM_EXPAUTOSPRO_SOURCE';

    protected function populateState() {
        jimport('joomla.filesystem.file');

        $app = JFactory::getApplication('administrator');
        //$fileName	= $this->getState('filename');
        $skinName = JRequest::getVar('skin');
        $this->setState('fileskin', $skinName);
        $skinName = "/" . $skinName . "/";
        $filePath = JPATH_ROOT . "/components/com_expautospro" . $skinName . "parameters/params.php";
        $this->setState('filename', $filePath);

        $app->setUserState('editor.expsource.syntax', JFile::getExt($filePath));
    }

    public function getForm($data = array(), $loadData = true) {
        $form = $this->loadForm('com_expautospro.expsource', 'expsource', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData() {
        $data = JFactory::getApplication()->getUserState('com_expautospro.edit.expsource.data', array());

        if (empty($data)) {
            $data = $this->getSource();
        }

        return $data;
    }

    public function &getSource() {
        $item = new stdClass;

        $fileName = $this->getState('filename');
        $fileSkin = $this->getState('fileskin');
        $filePath = JPath::clean($fileName);

        if (file_exists($filePath)) {
            jimport('joomla.filesystem.file');

            $item->filename = $this->getState('filename');
            $item->fileskin = $this->getState('fileskin');
            $item->expsource = JFile::read($filePath);
        } else {
            $this->setError(JText::_('COM_EXPAUTOSPRO_SOURCE_FILE_NOT_FOUND'));
        }

        return $item;
    }

    public function save($data) {
        jimport('joomla.filesystem.file');
        $fileName = $data['filename'];

        $filePath = JPath::clean($fileName);
        if (JPath::isOwner($filePath) && !JPath::setPermissions($filePath, '0644')) {
            $this->setError(JText::_('COM_EXPAUTOSPRO_SOURCE_FILE_NOT_WRITABLE'));
            return false;
        }

        $return = JFile::write($filePath, $data['expsource']);
        if (JPath::isOwner($filePath) && !JPath::setPermissions($filePath, '0444')) {
            $this->setError(JText::_('COM_EXPAUTOSPRO_SOURCE_FILE_NOT_UNWRITABLE'));
            return false;
        } elseif (!$return) {
            $this->setError(JText::sprintf('COM_EXPAUTOSPRO_FAILED_TO_SAVE_FILENAME', $fileName));
            return false;
        }

        return true;
    }

}
