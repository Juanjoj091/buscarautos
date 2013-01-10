<?php

/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class com_expautosproInstallerScript {

    function preflight($type, $parent) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update('#__expautos_config');
        $query->set('version=' . $db->quote('3.5.3'));
        $query->where('id=1');
        $db->setQuery($query);
        $db->query();
        if ($db->getErrorNum()) {
            echo JText::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $db->getErrorNum(), $db->getErrorMsg()) . '<br />';
            return;
        } else {
            echo '<p>' . JText::_('COM_EXPAUTOSPRO_UPDATE_VERSIONNUM_TEXT') . '</p>';
        }
    }

    function update($parent) {
        $db = JFactory::getDbo();
        $expadm = $db->getTableFields('#__expautos_admanager');
        if (!isset($expadm['#__expautos_admanager']['zipcode'])) {
            $query = 'ALTER TABLE #__expautos_admanager ADD zipcode VARCHAR(20) NOT NULL ';
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                echo JText::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $db->getErrorNum(), $db->getErrorMsg()) . '<br />';
                echo '<p>' . JText::_('COM_EXPAUTOSPRO_UPDATE_ADMANAGER_ZIP_ERROR_TEXT') . '</p>';
                return;
            }
        }
        if (!isset($expadm['#__expautos_admanager']['imgcount'])) {
            $query = 'ALTER TABLE #__expautos_admanager ADD imgcount INT(5) DEFAULT NULL ';
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                echo JText::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $db->getErrorNum(), $db->getErrorMsg()) . '<br />';
                echo '<p>' . JText::_('COM_EXPAUTOSPRO_UPDATE_ADMANAGER_IMGMAIN_ERROR_TEXT') . '</p>';
                return;
            }
        }
        if (!isset($expadm['#__expautos_admanager']['imgmain'])) {
            $query = 'ALTER TABLE #__expautos_admanager ADD imgmain VARCHAR(255) DEFAULT NULL ';
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                echo JText::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $db->getErrorNum(), $db->getErrorMsg()) . '<br />';
                echo '<p>' . JText::_('COM_EXPAUTOSPRO_UPDATE_ADMANAGER_IMGMAIN_ERROR_TEXT') . '</p>';
                return;
            } else {
                jimport( 'joomla.database.table' );
                //$table = JTable::getInstance('expautos_images');
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('id');
                $query->from('#__expautos_admanager');
                $db->setQuery($query);
                $all_ads = $db->loadResultArray();
                foreach ($all_ads as $item) {
                    //$table->reorder('catid = ' . (int) $item);
                    $query = $db->getQuery(true);
                    $query->select('count(id)');
                    $query->from('#__expautos_images');
                    $query->where('catid = ' . $item);
                    $db->setQuery($query);
                    $img_count = $db->loadResult();
                    $query = $db->getQuery(true);
                    $query->select('name');
                    $query->from('#__expautos_images');
                    $query->where('catid = ' . $item);
                    $query->where('ordering = 1');
                    $db->setQuery($query);
                    $img_name = $db->loadResult();
                    $mainimage = ($img_name ? $img_name : '');
                    $obj = new stdClass();
                    $obj->id = (int) $item;
                    $obj->imgcount = (int) $img_count;
                    $obj->imgmain = $mainimage;
                    $db->updateObject('#__expautos_admanager', $obj, 'id');
                }
            }
        }
        if (!isset($expadm['#__expautos_admanager']['latitude'])) {
            $query = 'ALTER TABLE #__expautos_admanager ADD latitude DOUBLE DEFAULT NULL ';
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                echo JText::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $db->getErrorNum(), $db->getErrorMsg()) . '<br />';
                return;
            }
        }
        if (!isset($expadm['#__expautos_admanager']['longitude'])) {
            $query = 'ALTER TABLE #__expautos_admanager ADD longitude DOUBLE DEFAULT NULL ';
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                echo JText::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $db->getErrorNum(), $db->getErrorMsg()) . '<br />';
                return;
            }
        }
        $expuser = $db->getTableFields('#__expautos_expuser');
        if (!isset($expuser['#__expautos_expuser']['emailstyle'])) {
            $query = 'ALTER TABLE #__expautos_expuser ADD emailstyle TINYINT(1) DEFAULT 1';
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                echo JText::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $db->getErrorNum(), $db->getErrorMsg()) . '<br />';
                return;
            }
        }
        if (!isset($expuser['#__expautos_expuser']['latitude'])) {
            $query = 'ALTER TABLE #__expautos_expuser ADD latitude DOUBLE DEFAULT NULL';
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                echo JText::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $db->getErrorNum(), $db->getErrorMsg()) . '<br />';
                return;
            }
        }
        if (!isset($expuser['#__expautos_expuser']['longitude'])) {
            $query = 'ALTER TABLE #__expautos_expuser ADD longitude DOUBLE DEFAULT NULL';
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                echo JText::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $db->getErrorNum(), $db->getErrorMsg()) . '<br />';
                return;
            }
        }
        $exppay = $db->getTableFields('#__expautos_payment');
        if (!isset($exppay['#__expautos_payment']['paynotice'])) {
            $query = 'ALTER TABLE #__expautos_payment ADD paynotice TEXT DEFAULT NULL';
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                echo JText::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $db->getErrorNum(), $db->getErrorMsg()) . '<br />';
                return;
            }
        }
        if (!isset($exppay['#__expautos_payment']['payid'])) {
            $query = 'ALTER TABLE #__expautos_payment ADD payid INT(11) DEFAULT NULL';
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                echo JText::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $db->getErrorNum(), $db->getErrorMsg()) . '<br />';
                return;
            }
        }
        if (!isset($exppay['#__expautos_payment']['paysysval'])) {
            $query = 'ALTER TABLE #__expautos_payment ADD paysysval INT(11) DEFAULT NULL';
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                echo JText::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $db->getErrorNum(), $db->getErrorMsg()) . '<br />';
                return;
            }
        }
        if (!isset($exppay['#__expautos_payment']['state'])) {
            $query = 'ALTER TABLE #__expautos_payment ADD state TINYINT(1) NOT NULL DEFAULT 0';
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                echo JText::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $db->getErrorNum(), $db->getErrorMsg()) . '<br />';
                return;
            }
        }
        echo '<p>' . JText::_(JText::sprintf('COM_EXPAUTOSPRO_UPDATE_TEXT', '3.5.3')) . '</p>';
        $parent->getParent()->setRedirectURL('index.php?option=com_expautospro');
    }

    function install($parent) {
        $parent->getParent()->setRedirectURL('index.php?option=com_expautospro');
    }

    function uninstall($parent) {
        echo '<p>' . JText::_('COM_EXPAUTOSPRO_UNINSTALL_TEXT') . '</p>';
    }

    function postflight($type, $parent) {
        echo '<p>' . JText::_('COM_EXPAUTOSPRO_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
    }

}
