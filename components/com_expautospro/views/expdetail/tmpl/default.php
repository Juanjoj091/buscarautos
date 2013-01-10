<?php

/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

defined('_JEXEC') or die;

$expgetpage = JRequest::getVar('page', 0);

$expskin = '';
if ($expgetpage === 'print' && file_exists(JPATH_COMPONENT . '/skins/expdetail/default_print/default.php')) {
    include (JPATH_COMPONENT . '/skins/expdetail/default_print/default.php');
} else {
    if ($this->expparams->get('c_admanager_detailpage_showskin')) {
        $expgetpagecookie = JRequest::getVar('expdetail', null, $hash = 'COOKIE');
        $expskin_post = (string) JRequest::getString('expskin', 0);
        if ($expskin_post) {
            $expskin = $expskin_post;
        } else {
            $expskin = $expgetpagecookie;
        }
    }
    if ($expskin && file_exists(JPATH_COMPONENT . '/skins/expdetail/' . $expskin . '/default.php')) {
        include (JPATH_COMPONENT . '/skins/expdetail/' . $expskin . '/default.php');
    } else {
        if (file_exists(JPATH_COMPONENT . '/skins/expdetail/' . $this->expparams->get('c_admanager_detailpage_skin') . '/default.php')) {
            include (JPATH_COMPONENT . '/skins/expdetail/' . $this->expparams->get('c_admanager_detailpage_skin') . '/default.php');
        } else {
            include (JPATH_COMPONENT . '/skins/expdetail/default/default.php');
        }
    }
}
?>


