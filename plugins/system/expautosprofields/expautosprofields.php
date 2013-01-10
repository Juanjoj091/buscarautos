<?php

/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemExpAutosProFields extends JPlugin {

    var $plugin = null;
    var $plgParams = null;
    var $time = 0;

    public function __construct(& $subject, $config) {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

    function onContentPrepareForm($form, $data) {
        if ($form->getName() == 'com_categories.categorycom_expautospro') {
            $xmlFile = dirname(__FILE__) . DS . "expautosprofields" . DS . 'params';
            JForm::addFormPath($xmlFile);
            $form->loadFile('params', false);
        }
    }

}