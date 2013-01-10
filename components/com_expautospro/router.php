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

jimport('joomla.application.categories');
require_once JPATH_ROOT . '/components/com_expautospro/helpers/expparams.php';

function ExpautosproBuildRoute(&$query) {
    $segments = array();
    $app = JFactory::getApplication();
    $db = JFactory::getDBO();
    $menu = $app->getMenu();
    $params = JComponentHelper::getParams('com_expautospro');
    $expconfig = ExpAutosProExpparams::getExpParams('config', 1);
    $advanced	= $expconfig->get('c_general_sefadvanced', 0);
    if (empty($query['Itemid'])) {
        $menuItem = $menu->getActive();
    } else {
        $menuItem = $menu->getItem($query['Itemid']);
    }
    $mView = (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
    $mCatid = (empty($menuItem->query['catid'])) ? null : $menuItem->query['catid'];
    $mId = (empty($menuItem->query['id'])) ? null : $menuItem->query['id'];
    
    
    //print_r($query['Itemid']);
    //print_r($menu->getActive());
    if (isset($query['view'])) {
        $view = $query['view'];
        if (empty($query['Itemid'])) {
            $segments[] = $query['view'];
        }
        unset($query['view']);
    };
    if (isset($query['view']) && ($mView == $query['view']) and (isset($query['id'])) and ($mId == intval($query['id']))) {
        unset($query['view']);
        unset($query['catid']);
        unset($query['id']);

        return $segments;
    }
    /********** EXPAUTOSPRO *********/
    if (isset($view) && $view == 'categories') {
        $segments[] = 'categories';
    }
    /********** EXPMAKE *********/
    if (isset($view) && $view == 'expmake') {
        if (isset($query['catid'])) {
            $segments[] = 'makes';
            $db->setQuery(" SELECT
                id,alias
                FROM
                #__categories
                WHERE
                id = " . (int) $query['catid']);
            $result_array = $db->loadAssoc();
            if($advanced){
                $segments[] = $result_array['alias'];
            }else{
                $segments[] = $result_array['id'] . ':' . $result_array['alias'];
            }
        }
        unset($view);
        unset($query['catid']);
        //unset($query['markid']);
    }
    /********** EXPMODEL *********/
    if (isset($view) && $view == 'expmodel') {
        $segments[] = 'models';
        if (isset($query['makeid'])) {
            $db->setQuery(' SELECT mk.id AS mkid,mk.alias AS mkalias,cat.id AS catid,cat.alias AS catalias '
                . ' FROM #__expautos_make AS mk '
                . ' LEFT JOIN #__categories AS cat ON cat.id = mk.catid '
                . ' WHERE mk.id = ' . (int) $query['makeid']);
            $result_array = $db->loadAssoc();
            
            if($advanced){
                $categ = $result_array['catalias'];
                $markid = $result_array['mkalias'];
            }else{
                $categ = $result_array['catid'] . ':' . $result_array['catalias'];
                $markid = $result_array['mkid'] . ':' . $result_array['mkalias'];
            }
        }
        $segments[] = $categ;
        $segments[] = $markid;
        //$segments[] = $categ;
        unset($view);
        unset($query['catid']);
        unset($query['makeid']);
    }
    /********** EXPLIST *********/
    if (isset($view) && $view == 'explist') {
        $categ = null;
        $markid = null;
        $modelid = null;
        $segments[] = 'list';
        if (isset($query['catid'])) {
            $db->setQuery(" SELECT
                id,alias
                FROM
                #__categories
                WHERE
                id = " . (int) $query['catid']);
            $result_array = $db->loadAssoc();
            if($advanced){
                $categ = $result_array['alias'];
            }else{
                $categ = $result_array['id']."-".$result_array['alias'];
            }
        }
        if (isset($query['makeid'])) {
            $db->setQuery(" SELECT
                id,alias
                FROM
                #__expautos_make
                WHERE
                id = " . (int) $query['makeid']);
            $result_array = $db->loadAssoc();
            if($advanced){
                $markid = $result_array['alias'];
            }else{
                $markid = $result_array['id']."-".$result_array['alias'];
            }
        }
        if (isset($query['modelid'])) {
            $db->setQuery(" SELECT
                id,alias
                FROM
                #__expautos_model
                WHERE
                id = " . (int) $query['modelid']);
            $result_array = $db->loadAssoc();
            if($advanced){
                $modelid = $result_array['alias'];
            }else{
                $modelid = $result_array['id']."-".$result_array['alias'];
            }
        }
        $segments[] = $categ;
        $segments[] = $markid;
        $segments[] = $modelid;
        unset($view);
        unset($query['catid']);
        unset($query['makeid']);
        unset($query['modelid']);
    }
    /********** EXPDETAIL *********/
    if (isset($view) && $view == 'expdetail') {
        $segments[] = 'detail';
        if(isset($query['id'])){
            $segments[] = $query['id'];
            $db->setQuery(' SELECT a.id,a.make,a.model,a.specificmodel AS specificmodel,cat.alias AS catalias,mk.alias AS mkalias,md.alias AS mdalias,cat.id AS catid,mk.id AS mkid,md.id AS mdid '
                . ' FROM #__expautos_admanager AS a '
                . ' LEFT JOIN #__categories AS cat ON cat.id = a.catid '
                . ' LEFT JOIN #__expautos_make AS mk ON mk.id = a.make '
                . ' LEFT JOIN #__expautos_model AS md ON md.id = a.model '
                . ' WHERE a.id = '.(int)$query['id']);
                ;
            $result_array = $db->loadAssoc();
            //print_r($result_array);
            if($advanced){
                $segments[] = $result_array['catalias'];
                if($result_array['mkalias'])
                    $segments[] = $result_array['mkalias'];
                if($result_array['mdalias'])
                    $segments[] = $result_array['mdalias'];
            }else{
                $segments[] = $result_array['catid']."-".$result_array['catalias'];
                if($result_array['mkid'])
                    $segments[] = $result_array['mkid']."-".$result_array['mkalias'];
                if($result_array['mdid'])
                    $segments[] = $result_array['mdid']."-".$result_array['mdalias'];
            }
        }
        unset($view);
        unset($query['id']);
        unset($query['catid']);
        unset($query['makeid']);
        unset($query['modelid']);
    }
    /********** EXPDEALERLIST *********/
    if (isset($view) && $view == 'expdealerlist') {
        $segments[] = 'dealerlist';
        unset($view);
    }
    /********** EXPDEALERDETAIL *********/
    if (isset($view) && $view == 'expdealerdetail') {
        $segments[] = 'dealerdetail';
        if(isset($query['userid'])){
            $segments[] = $query['userid'];
        }
        unset($view);
        unset($query['userid']);
    }
    /********** EXPPAYMENTLEVEL *********/
    if (isset($view) && $view == 'exppaylevel') {
        $segments[] = 'paymentlevel';
        unset($view);
    }
    /********** EXPPAYMENT *********/
    if (isset($view) && $view == 'exppayment') {
        $segments[] = 'payment';
        if(isset($query['id'])){
            $segments[] = $query['id'];
        }
        unset($view);
        unset($query['id']);
    }
    /********** EXPUSER *********/
    if (isset($view) && $view == 'expuser') {
        $segments[] = 'user';
        if(isset($query['layout'])){
            $segments[] = $query['layout'];
        }
        unset($view);
        unset($query['layout']);
    }
    /********** EXPADD *********/
    if (isset($view) && $view == 'expadd') {
        $segments[] = 'add';
        if(isset($query['id'])){
            $segments[] = $query['id'];
        }
        unset($view);
        unset($query['id']);
    }
    /********** EXPIMAGES *********/
    if (isset($view) && $view == 'expimages') {
        $segments[] = 'images';
        if(isset($query['id'])){
            $segments[] = $query['id'];
        }
        unset($view);
        unset($query['id']);
    }
    /********** EXPCOMPARE *********/
    if (isset($view) && $view == 'expcompare') {
        $segments[] = 'compare';
    }
    //print_r($segments);

    return $segments;
}


function ExpautosproParseRoute($segments) {
    $vars = array();
    $app	= JFactory::getApplication();
    $menu	= $app->getMenu();
    $item	= $menu->getActive();
    $params     = JComponentHelper::getParams('com_expautospro');
    $expconfig = ExpAutosProExpparams::getExpParams('config', 1);
    $advanced	= $expconfig->get('c_general_sefadvanced', 0);
    //$segment = str_replace(':', '-', $segment);
    $count = count($segments);
    if (!isset($item)) {
        $vars['view'] = $segments[0];
        $vars['id'] = $segments[$count - 1];
        return $vars;
    }

    switch ($segments[0]) {
        case 'categories':
            $vars['view'] = 'categories';
            break;
        case 'makes':
            $vars['view'] = 'expmake';
            if (isset($segments[1])) {
                if($advanced){
                    $str_rep = str_replace(':', '-', $segments[1]);
                    $db = JFactory::getDBO();
                    $query = "SELECT id FROM #__categories WHERE extension = 'com_expautospro' AND alias = ".$db->Quote($str_rep);
                    $db->setQuery($query);
                    $cid = $db->loadResult(); 
                    $expcat = $cid;
                }else{
                    $seg = explode(":", $segments[1]);
                    if ($seg[0]) {
                        $expcat = $seg[0];
                    }
                }
                $vars['catid'] = $expcat;
            }
            break;
        case 'models':
            $vars['view'] = 'expmodel';
            //print_r($segments);
            if (isset($segments[1])) {
                if($advanced){
                    $str_rep = str_replace(':', '-', $segments[1]);
                    $db = JFactory::getDBO();
                    $query = "SELECT id FROM #__categories WHERE extension = 'com_expautospro' AND alias = ".$db->Quote($str_rep);
                    $db->setQuery($query);
                    $cid = $db->loadResult(); 
                    $expcat = $cid;
                }else{
                    $seg = explode(":", $segments[1]);
                    if ($seg[0]) {
                        $expcat = $seg[0];
                    }
                }
                $vars['catid'] = $expcat;
            }
            if (isset($segments[2])) {
                if($advanced){
                    $str_rep = str_replace(':', '-', $segments[2]);
                    $db = JFactory::getDBO();
                    $query = "SELECT id FROM #__expautos_make WHERE catid = ".$expcat." AND alias = ".$db->Quote($str_rep);
                    $db->setQuery($query);
                    $cid = $db->loadResult(); 
                    $expmake = $cid;
                }else{
                    $seg = explode(":", $segments[2]);
                    if ($seg[0]) {
                        $expmake = $seg[0];
                    }
                }
                $vars['makeid'] = $expmake;
            }
            break;
        case 'list':
            $vars['view'] = 'explist';
            //print_r($segments);
            if (isset($segments[1])) {
                if($advanced){
                    $str_rep = str_replace(':', '-', $segments[1]);
                    $db = JFactory::getDBO();
                    $query = "SELECT id FROM #__categories WHERE extension = 'com_expautospro' AND alias = ".$db->Quote($str_rep);
                    $db->setQuery($query);
                    $cid = $db->loadResult(); 
                    $expcat = $cid;
                }else{
                    $seg = explode(":", $segments[1]);
                    if ($seg[0]) {
                        $expcat = $seg[0];
                    }
                }
                $vars['catid'] = $expcat;
            }
            if (isset($segments[2])) {
                if($advanced){
                    $str_rep = str_replace(':', '-', $segments[2]);
                    $db = JFactory::getDBO();
                    $query = "SELECT id FROM #__expautos_make WHERE catid = ".$expcat." AND alias = ".$db->Quote($str_rep);
                    $db->setQuery($query);
                    $cid = $db->loadResult(); 
                    $expmake = $cid;
                }else{
                    $seg = explode(":", $segments[2]);
                    if ($seg[0]) {
                        $expmake = $seg[0];
                    }
                }
                $vars['makeid'] = $expmake;
            }
            if (isset($segments[3])) {
                if($advanced){
                    $str_rep = str_replace(':', '-', $segments[3]);
                    $db = JFactory::getDBO();
                    $query = "SELECT id FROM #__expautos_model WHERE makeid = ".$expmake." AND alias = ".$db->Quote($str_rep);
                    $db->setQuery($query);
                    $cid = $db->loadResult(); 
                    $expmodel = $cid;
                }else{
                    $seg = explode(":", $segments[3]);
                    if ($seg[0]) {
                        $expmodel = $seg[0];
                    }
                }
                $vars['modelid'] = $expmodel;
            }
            break;
        case 'detail':
            $vars['view'] = 'expdetail';
            //print_r($segments);
            if (isset($segments[2])) {
                if($advanced){
                    $str_rep = str_replace(':', '-', $segments[2]);
                    $db = JFactory::getDBO();
                    $query = "SELECT id FROM #__categories WHERE extension = 'com_expautospro' AND alias = ".$db->Quote($str_rep);
                    $db->setQuery($query);
                    $cid = $db->loadResult(); 
                    $expcat = $cid;
                }else{
                    $seg = explode(":", $segments[2]);
                    if ($seg[0]) {
                        $expcat = $seg[0];
                    }
                }
                $vars['catid'] = $expcat;
            }
            if (isset($segments[3])) {
                if($advanced){
                    $str_rep = str_replace(':', '-', $segments[3]);
                    $db = JFactory::getDBO();
                    $query = "SELECT id FROM #__expautos_make WHERE catid = ".$expcat." AND alias = ".$db->Quote($str_rep);
                    $db->setQuery($query);
                    $cid = $db->loadResult(); 
                    $expmake = $cid;
                }else{
                    $seg = explode(":", $segments[3]);
                    if ($seg[0]) {
                        $expmake = $seg[0];
                    }
                }
                $vars['makeid'] = $expmake;
            }
            if (isset($segments[4])) {
                if($advanced){
                    $str_rep = str_replace(':', '-', $segments[4]);
                    $db = JFactory::getDBO();
                    $query = "SELECT id FROM #__expautos_model WHERE makeid = ".$expmake." AND alias = ".$db->Quote($str_rep);
                    $db->setQuery($query);
                    $cid = $db->loadResult(); 
                    $expmodel = $cid;
                }else{
                    $seg = explode(":", $segments[4]);
                    if ($seg[0]) {
                        $expmodel = $seg[0];
                    }
                }
                $vars['modelid'] = $expmodel;
            }
            if (isset($segments[1])) {
                $vars['id'] = $segments[1];
            }
            break;
        case 'dealerlist':
            $vars['view'] = 'expdealerlist';
            break;
        case 'dealerdetail':
            $vars['view'] = 'expdealerdetail';
            if (isset($segments[1])) {
                $vars['userid'] = $segments[1];
            }
            break;
        case 'paymentlevel':
            $vars['view'] = 'exppaylevel';
            break;
        case 'payment':
            $vars['view'] = 'exppayment';
            if (isset($segments[1])) {
                $vars['id'] = $segments[1];
            }
            break;
        case 'user':
            $vars['view'] = 'expuser';
            if (isset($segments[1])) {
                    $vars['layout'] = $segments[1];
            }
            break;
        case 'add':
            $vars['view'] = 'expadd';
            if (isset($segments[1])) {
                $vars['id'] = $segments[1];
            }
            break;
        case 'images':
            $vars['view'] = 'expimages';
            if (isset($segments[1])) {
                $vars['id'] = $segments[1];
            }
            break;
        case 'compare':
            $vars['view'] = 'expcompare';
            break;
    }
    return $vars;
}

