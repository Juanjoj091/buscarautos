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

class ExpAutosProControllerExpcurrs extends JControllerAdmin {

    protected $text_prefix = 'COM_EXPAUTOSPRO_CURRS';

    /**
     * Proxy for getModel.
     * @since	1.6
     */
    public function getModel($name = 'Expcurr', $prefix = 'ExpAutosProModel', $config = array('ignore_request' => true)) {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

}