<?php

/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class ExpAutosProControllerExpconfig extends JControllerForm {

    public function __construct($config = array()) {
        parent::__construct($config);

        $this->registerTask('expresize', 'expresize');
    }

    public function expresize() {
        $form = JRequest::getVar('jform');
        $folder_num = $form['params']['c_images_resize_folder'];
        $folder_from = $form['params']['c_images_resize_from'];
        if ($folder_num) {
            $model = $this->getModel();
            if (!$model->expresimg($folder_num, $folder_from)) {
                JError::raiseWarning(500, $model->getError());
            } else {
                $this->setMessage(JText::_('COM_EXPAUTOSPRO_CONFIGS_TAB_IMAGES_SEL_RESIZE_OK_TEXT'));
            }
        } else {
            JError::raiseWarning(500, JText::_('COM_EXPAUTOSPRO_CONFIGS_TAB_IMAGES_SEL_IMAGESVAR_NO_SEL_TEXT'));
        }
        $this->setRedirect('index.php?option=com_expautospro&view=expconfig&layout=edit&id=1');
    }

    protected function allowAdd($data = array()) {
        // Initialise variables.
        $user = JFactory::getUser();
        $categoryId = JArrayHelper::getValue($data, 'catid', JRequest::getInt('filter_category_id'), 'int');
        $allow = null;

        if ($categoryId) {
            $allow = $user->authorise('core.create', $this->option . '.expconfig.' . $categoryId);
        }

        if ($allow === null) {
            return parent::allowAdd($data);
        } else {
            return $allow;
        }
    }

    protected function allowEdit($data = array(), $key = 'id') {
        $recordId = (int) isset($data[$key]) ? $data[$key] : 0;
        return parent::allowEdit($data, 1);
    }

    public function expinstall() {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $model = $this->getModel('Expconfig');
        if ($model->expinstall()) {
            $cache = JFactory::getCache('mod_menu');
            $cache->clean();
        }
        $app = JFactory::getApplication();
        $redirect_url = $app->getUserState('com_expautospro.redirect_url');
        if (empty($redirect_url)) {
            $redirect_url = JRoute::_('index.php?option=com_expautospro&view=expconfig&layout=edit&id=1', false);
        }
        $this->setRedirect($redirect_url);
    }
    
    public function expdelskin(){
        $del_patch = JRequest::getVar('skinpatch',null, '', 'array');
        $exppatch = array_filter($del_patch);
        $exppatch = array_values($exppatch);
        $model = $this->getModel('Expconfig');
        if (!$model->expdeleteskin($exppatch[0])) {
            JError::raiseWarning(500, JText::_('COM_EXPAUTOSPRO_PROBLEM_DELETE_SKIN_TEXT'));
        }
        $app = JFactory::getApplication();
        $redirect_url = $app->getUserState('com_expautospro.redirect_url');
        if (empty($redirect_url)) {
            $redirect_url = JRoute::_('index.php?option=com_expautospro&view=expconfig&layout=edit&id=1', false);
        }
        $this->setRedirect($redirect_url);
    }

}