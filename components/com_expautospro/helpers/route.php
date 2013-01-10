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
defined('_JEXEC') or die;

// Component Helper
jimport('joomla.application.component.helper');
jimport('joomla.application.categories');
require_once JPATH_ROOT . '/components/com_expautospro/helpers/expparams.php';

abstract class ExpautosproHelperRoute {

    protected static $lookup;

    public static function getExpautosproRoute($id, $catid) {
        $needles = array(
            'expmake' => array((int) $id)
        );

        //Create the link
        $link = 'index.php?option=com_expautospro&view=expmake&catid=' . $id;
        if ($catid > 1) {
            $categories = JCategories::getInstance('Expautospro');
            $category = $categories->get($catid);

            if ($category) {
                $needles['category'] = array_reverse($category->getPath());
                $needles['categories'] = $needles['category'];
                $link .= '&catid=' . $catid;
            }
        }
/*
        if ($item = self::_findItem($needles)) {
            $link .= '&Itemid=' . $item;
        } elseif ($item = self::_findItem()) {
            $link .= '&Itemid=' . $item;
        }
 */
        $link .= '&Itemid=' . ExpAutosProExpparams::getExpLinkItemid();

        return $link;
    }

    public static function getFormRoute($id, $return = null) {
        
    }

    public static function getCategoryRoute($catid,$explinkto='expmake') {
        if ($catid instanceof JCategoryNode) {
            $id = $catid->id;
            $category = $catid;
        } else {
            $id = (int) $catid;
            $category = JCategories::getInstance('Expautospro')->get($id);
        }
        if ($id < 1) {
            $link = '';
        } else {
            $needles = array(
                'category' => array($id)
            );

            if ($item = self::_findItem($needles)) {
                $link = 'index.php?Itemid=' . $item;
            } else {
                //Create the link
                $listcatfields = ExpAutosProExpparams::getCatParams($id);
                if($listcatfields->get('expfieldscatlinkto'))
                    $explinkto = $listcatfields->get('expfieldscatlinkto');
                $link = 'index.php?option=com_expautospro&amp;view='.$explinkto.'&amp;catid='. $id;

                if ($category) {
                    $catids = array_reverse($category->getPath());
                    $needles = array(
                        'category' => $catids,
                        'categories' => $catids
                    );
/*
                    if ($item = self::_findItem($needles)) {
                        $link .= '&Itemid=' . $item;
                    } elseif ($item = self::_findItem()) {
                        $link .= '&Itemid=' . $item;
                    }
 */
                    $link .= '&Itemid=' . ExpAutosProExpparams::getExpLinkItemid();
                    
                }
            }
        }

        return $link;
    }

    protected static function _findItem($needles = null) {
        $app = JFactory::getApplication();
        $menus = $app->getMenu('site');

        // Prepare the reverse lookup array.
        if (self::$lookup === null) {
            self::$lookup = array();

            $component = JComponentHelper::getComponent('com_expautospro');
            $items = $menus->getItems('component_id', $component->id);

            if ($items) {
                foreach ($items as $item) {
                    if (isset($item->query) && isset($item->query['view'])) {
                        $view = $item->query['view'];

                        if (!isset(self::$lookup[$view])) {
                            self::$lookup[$view] = array();
                        }

                        if (isset($item->query['id'])) {
                            self::$lookup[$view][$item->query['id']] = $item->id;
                        }
                    }
                }
            }
        }

        if ($needles) {
            foreach ($needles as $view => $ids) {
                if (isset(self::$lookup[$view])) {
                    foreach ($ids as $id) {
                        if (isset(self::$lookup[$view][(int) $id])) {
                            return self::$lookup[$view][(int) $id];
                        }
                    }
                }
            }
        } else {
            $active = $menus->getActive();
            if ($active) {
                return $active->id;
            }
        }

        return null;
    }

}
