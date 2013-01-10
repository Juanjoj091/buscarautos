<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

// No direct access
defined('_JEXEC') or die;
require_once JPATH_COMPONENT . '/helpers/expimages.php';

class ExpAutosProTableExpconfig extends JTable {

    function __construct(&$_db) {
        parent::__construct('#__expautos_config', 'id', $_db);
    }

    public function bind($array, $ignore = array()) {
        if (isset($array['params']) && is_array($array['params'])) {
            $registry = new JRegistry();
            $registry->loadArray($array['params']);
            $array['params'] = (string) $registry;
        }
        $this->id = 1;

        return parent::bind($array, $ignore);
    }

    function store($updateNulls = false) {
        if (is_array($this->params)) {
            $registry = new JRegistry();
            $registry->loadArray($this->params);
            $this->params = (string) $registry;
        }
        $this->id = 1;

        $table = JTable::getInstance('Expconfig', 'ExpAutosProTable');
        return parent::store($updateNulls);
    }

    function check() {
        jimport('joomla.filter.output');
        $this->id = 1;

        $registry = new JRegistry();
        $registry->loadJSON($this->params);
        ExpAutosProImages::CreateFolder('1', $registry->get('c_user_req_logopatch'));
        ExpAutosProImages::CreateFolder('2', $registry->get('c_images_thumbpatch'));
        ExpAutosProImages::CreateFolder('3', $registry->get('c_images_middlepatch'));
        ExpAutosProImages::CreateFolder('4', $registry->get('c_images_bigpatch'));
        ExpAutosProImages::CreateFolder('5', $registry->get('c_admanager_files_folder'));
        return true;
    }

}
