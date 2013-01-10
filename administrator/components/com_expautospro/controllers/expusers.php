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

class ExpAutosProControllerExpusers extends JControllerAdmin {

    protected $text_prefix = 'COM_EXPAUTOSPRO_USERS';

    public function __construct($config = array()) {
        parent::__construct($config);

        $this->registerTask('utop_unpublish', 'utop_publish');
        $this->registerTask('ucommercial_unpublish', 'ucommercial_publish');
        $this->registerTask('uspecial_unpublish', 'uspecial_publish');
    }

    /**
     * Proxy for getModel.
     * @since	1.6
     */
    public function getModel($name = 'Expuser', $prefix = 'ExpAutosProModel', $config = array('ignore_request' => true)) {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }
    
    public function utop_publish() {
        // Check for request forgeries.
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $user = JFactory::getUser();
        $ids = JRequest::getVar('cid', array(), '', 'array');
        $values = array('utop_publish' => 1, 'utop_unpublish' => 0);
        $task = $this->getTask();
        $value = JArrayHelper::getValue($values, $task, 0, 'int');
        $this->exptask($ids,$value,$task,'utop');
    }

    public function ucommercial_publish() {
        // Check for request forgeries.
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $user = JFactory::getUser();
        $ids = JRequest::getVar('cid', array(), '', 'array');
        $values = array('ucommercial_publish' => 1, 'ucommercial_unpublish' => 0);
        $task = $this->getTask();
        $value = JArrayHelper::getValue($values, $task, 0, 'int');
        $this->exptask($ids,$value,$task,'ucommercial');
    }

    public function uspecial_publish() {
        // Check for request forgeries.
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $user = JFactory::getUser();
        $ids = JRequest::getVar('cid', array(), '', 'array');
        $values = array('uspecial_publish' => 1, 'uspecial_unpublish' => 0);
        $task = $this->getTask();
        $value = JArrayHelper::getValue($values, $task, 0, 'int');
        $this->exptask($ids,$value,$task,'uspecial');
    }
    
    public function exptask ($ids,$value,$task,$expmodel){
        if (empty($ids)) {
            JError::raiseWarning(500, JText::_('COM_EXPAUTOSPRO_ADMANAGERS_NO_ADS_SELECTED'));
        } else {
            // Get the model.
            $model = $this->getModel();

            // Change the state of the records.
            if (!$model->exptask($ids,$value,$expmodel)) {
                JError::raiseWarning(500, $model->getError());
            } else {
                if ($value == 1) {
                    $ntext = 'COM_EXPAUTOSPRO_ADMANAGERS_ADS_TASK_PUBLISHED';
                } else {
                    $ntext = 'COM_EXPAUTOSPRO_ADMANAGERS_ADS_TASK_UNPUBLISHED';
                }
                $this->setMessage(JText::plural($ntext, count($ids)));
            }
        }

        $this->setRedirect('index.php?option=com_expautospro&view=expusers');
    }

}