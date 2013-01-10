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

class ExpAutosProControllerexpadmanagers extends JControllerAdmin {

    protected $text_prefix = 'COM_EXPAUTOSPRO_ADMANAGERS';

    public function __construct($config = array()) {
        parent::__construct($config);

        $this->registerTask('ftop_unpublish', 'ftop_publish');
        $this->registerTask('fcommercial_unpublish', 'fcommercial_publish');
        $this->registerTask('special_unpublish', 'special_publish');
        $this->registerTask('solid_unpublish', 'solid_publish');
        $this->registerTask('expreserved_unpublish', 'expreserved_publish');
    }

    /**
     * Proxy for getModel.
     * @since	1.6
     */
    public function getModel($name = 'Expadmanager', $prefix = 'ExpAutosProModel', $config = array('ignore_request' => true)) {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

    public function ftop_publish() {
        // Check for request forgeries.
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $user = JFactory::getUser();
        $ids = JRequest::getVar('cid', array(), '', 'array');
        $values = array('ftop_publish' => 1, 'ftop_unpublish' => 0);
        $task = $this->getTask();
        $value = JArrayHelper::getValue($values, $task, 0, 'int');
        $this->exptask($ids,$value,$task,'ftop');
    }

    public function fcommercial_publish() {
        // Check for request forgeries.
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $user = JFactory::getUser();
        $ids = JRequest::getVar('cid', array(), '', 'array');
        $values = array('fcommercial_publish' => 1, 'fcommercial_unpublish' => 0);
        $task = $this->getTask();
        $value = JArrayHelper::getValue($values, $task, 0, 'int');
        $this->exptask($ids,$value,$task,'fcommercial');
    }

    public function special_publish() {
        // Check for request forgeries.
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $user = JFactory::getUser();
        $ids = JRequest::getVar('cid', array(), '', 'array');
        $values = array('special_publish' => 1, 'special_unpublish' => 0);
        $task = $this->getTask();
        $value = JArrayHelper::getValue($values, $task, 0, 'int');
        $this->exptask($ids,$value,$task,'special');
    }

    public function solid_publish() {
        // Check for request forgeries.
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $user = JFactory::getUser();
        $ids = JRequest::getVar('cid', array(), '', 'array');
        $values = array('solid_publish' => 1, 'solid_unpublish' => 0);
        $task = $this->getTask();
        $value = JArrayHelper::getValue($values, $task, 0, 'int');
        $this->exptask($ids,$value,$task,'solid');
    }

    public function expreserved_publish() {
        // Check for request forgeries.
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $user = JFactory::getUser();
        $ids = JRequest::getVar('cid', array(), '', 'array');
        $values = array('expreserved_publish' => 1, 'expreserved_unpublish' => 0);
        $task = $this->getTask();
        $value = JArrayHelper::getValue($values, $task, 0, 'int');
        $this->exptask($ids,$value,$task,'expreserved');
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

        $this->setRedirect('index.php?option=com_expautospro&view=expadmanagers');
    }

}