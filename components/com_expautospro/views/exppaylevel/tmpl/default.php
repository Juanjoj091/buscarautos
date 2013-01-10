<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

defined('_JEXEC') or die;
$expskin = '';
if($this->expparams->get('c_admanager_paylevel_showskin')){
    $expgetpagecookie = JRequest::getVar('exppaylevel', null,  $hash= 'COOKIE');
    $expskin_post = (string) JRequest::getString('expskin', 0);
    if($expskin_post){
        $expskin = $expskin_post; 
    }else{
        $expskin = $expgetpagecookie;
    }
}
if($expskin && file_exists(JPATH_COMPONENT . '/skins/exppaylevel/'.$expskin.'/default.php')){
    include (JPATH_COMPONENT . '/skins/exppaylevel/'.$expskin.'/default.php');
}else{
    if (file_exists(JPATH_COMPONENT . '/skins/exppaylevel/'.$this->expparams->get('c_admanager_paylevel_skin').'/default.php')) {
        include (JPATH_COMPONENT . '/skins/exppaylevel/'.$this->expparams->get('c_admanager_paylevel_skin').'/default.php');
    } else {
        include (JPATH_COMPONENT . '/skins/exppaylevel/default/default.php');
    }
}
?>


