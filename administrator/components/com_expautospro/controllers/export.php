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

class ExpAutosProControllerExport extends JControllerAdmin {

    protected $text_prefix = 'COM_EXPAUTOSPRO_EXPORT';
    protected $table;

    public function catexportcsv() {
        $exporttable = JRequest::getString('exporttable', 0);
        $catid = JRequest::getInt('filter_catid', 0);
        $where = '';
        if($exporttable == 'categories')
            $where = " WHERE extension = 'com_expautospro'";
        if ($catid) {
            switch ($exporttable) {
                case "expautos_make":
                    $where = " WHERE catid = $catid ";
                    break;
                case "expautos_model":
                    $where = " WHERE makeid = $catid ";
                    break;
                case "expautos_bodytype":
                    $where = " WHERE catid = $catid ";
                    break;
                case "expautos_extrafield1":
                    $where = " WHERE catid = $catid ";
                    break;
                case "expautos_catequipment":
                    $where = " WHERE catid = $catid ";
                    break;
                case "expautos_equipment":
                    $where = " WHERE catid = $catid ";
                    break;
                case "expautos_expuser":
                    $where = " WHERE userid = $catid ";
                    break;
                default:
                    break;
            }
        }
        $model = $this->getModel('export');
        if ($model->exportcsv($exporttable, $where)) {
            $this->setMessage(JText::_('COM_EXPAUTOSPRO_EXPORT_SUCCESS'));
            return true;
        } else {
            $this->setMessage(JText::_(JText::sprintf('COM_EXPAUTOSPRO_ERROR_EXPORT_FAILED', $model->getError())));
            return false;
        }
        //ExpAutosProHelper::exportcsv($exporttable, $where);
       // $this->setRedirect('index.php?option=com_expautospro&view=' . $table);
    }

    public function catexportxml() {
        $exporttable = JRequest::getString('exporttable', 0);
        $catid = JRequest::getInt('filter_catid', 0);
        $where = '';
        if($exporttable == 'categories')
            $where = " WHERE extension = 'com_expautospro'";
        if ($catid) {
            switch ($exporttable) {
                case "expautos_make":
                    $where = " WHERE catid = $catid ";
                    break;
                case "expautos_model":
                    $where = " WHERE makeid = $catid ";
                    break;
                case "expautos_bodytype":
                    $where = " WHERE catid = $catid ";
                    break;
                case "expautos_extrafield1":
                    $where = " WHERE catid = $catid ";
                    break;
                case "expautos_catequipment":
                    $where = " WHERE catid = $catid ";
                    break;
                case "expautos_equipment":
                    $where = " WHERE catid = $catid ";
                    break;
                case "expautos_expuser":
                    $where = " WHERE userid = $catid ";
                    break;
                default:
                    break;
            }
        }

        $model = $this->getModel('export');
        if ($model->exportxml($exporttable, $where)) {
            $this->setMessage(JText::_('COM_EXPAUTOSPRO_COPYMOVE_SUCCESS'));
            return true;
        } else {
            $this->setMessage(JText::_(JText::sprintf('COM_EXPAUTOSPRO_ERROR_COPYMOVE_FAILED', $model->getError())));
            return false;
        }
        //ExpAutosProHelper::exportxml($exporttable, $where);
       // $this->setRedirect('index.php?option=com_expautospro&view=' . $table);
    }

}