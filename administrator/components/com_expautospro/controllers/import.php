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

jimport('joomla.application.component.controlleradmin');
require_once JPATH_COMPONENT . '/helpers/helper.php';

class ExpAutosProControllerImport extends JControllerAdmin {

    protected $text_prefix = 'COM_EXPAUTOSPRO_IMPORT';
    protected $table;

    public function catimportcsv() {
        $table = JRequest::getString('importtable', 0);
        $select = JRequest::getString('expautosadmincsv', 0);
        $uploadfile = JRequest::getVar('uploadfile', array(), 'files', 'array');
        
        if (!$table) {
            JError::raiseWarning(500, JText::_('COM_EXPAUTOSPRO_CSV_SELECTDATABASE_TEXT'));
            $this->setRedirect('index.php?option=com_expautospro&view=import', $msg);
            return false;
        }
        if (!$uploadfile['name']) {
            JError::raiseWarning(500, JText::_('COM_EXPAUTOSPRO_CSVIMPORT_ERROR_SELECTFILE_TEXT'));
            $this->setRedirect('index.php?option=com_expautospro&view=import', $msg);
            return false;
        }
        
        $model = $this->getModel('import');
        if ($model->importcsv($table, $select, $uploadfile)) {
            $msg = $this->setMessage(JText::_(JText::sprintf('COM_EXPAUTOSPRO_CSVSUCCESS_TEXT', $model->getError())));
        } else {
            $msg = $this->setMessage(JText::_(JText::sprintf('COM_EXPAUTOSPRO_CSVIMPORT_ERROR_PROBLEM_TEXT', $model->getError())), 'warning');
        }
        /*
          switch ($table) {
          case 'categories':
          $expredirect='expcats';
          break;
          case 'make':
          $expredirect='expmakes';
          break;
          case 'model':
          $expredirect='expmodels';
          break;

          default:
          break;
          }
         * 
         */
        $this->setRedirect('index.php?option=com_expautospro&view=import', $msg);
    }

    public function catimportxml() {
        $select = JRequest::getString('expautosadmincsv', 0);
        $uploadfile = JRequest::getVar('uploadfile', array(), 'files', 'array');
        
        if (!$uploadfile['name']) {
            JError::raiseWarning(500, JText::_('COM_EXPAUTOSPRO_CSVIMPORT_ERROR_SELECTFILE_TEXT'));
            $this->setRedirect('index.php?option=com_expautospro&view=import', $msg);
            return false;
        }
        
        $model = $this->getModel('import');
        if ($model->importxml($uploadfile,$select)) {
            $msg = $this->setMessage(JText::_(JText::sprintf('COM_EXPAUTOSPRO_CSVSUCCESS_TEXT', $model->getError())));
        } else {
            $msg = $this->setMessage(JText::_(JText::sprintf('COM_EXPAUTOSPRO_CSVIMPORT_ERROR_PROBLEM_TEXT', $model->getError())), 'warning');
        }
        $this->setRedirect('index.php?option=com_expautospro&view=import', $msg);
    }

}