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

jimport('joomla.application.component.modellist');

class ExpAutosProModelImport extends JModelList {

    public function __construct($config = array()) {
        parent::__construct();
    }

    public function importcsv($name, $select, $uploadfile) {
        $db = JFactory::getDBO();
        $postselect = $select;
        $fname = $uploadfile['name'];

        $chk_ext = explode(".", $fname);

        if (strtolower($chk_ext[1]) == "csv") {
            $filename = $uploadfile['tmp_name'];
            $handle = fopen($filename, "r");
            $lists = $db->getTableFields('#__' . $name);
            $list = array_keys($lists['#__' . $name]);
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $obj = new stdClass();
                for ($i = 0; $i < count($list); $i++) {
                    $obj->$list["$i"] = str_replace('\\"', '"', $data[$i]);
                }
                if ($postselect) {
                    $db->updateObject('#__' . $name, $obj, 'id');
                } else {
                    $db->insertObject('#__' . $name, $obj);
                }
            }
            fclose($handle);
            $this->setError($fname);
            return true;
        } else {
            $this->setError(JText::_('COM_EXPAUTOSPRO_CSVIMPORT_ERROR_NOCSVFILE_TEXT'));
            return false;
        }
    }

    public function importxml($uploadfile, $select) {
        $db = JFactory::getDBO();
        $fname = $uploadfile['name'];
        $chk_ext = explode(".", $fname);
        if (strtolower($chk_ext[1]) == "xml") {
            $xml = simplexml_load_file($uploadfile['tmp_name']);
            $name = $xml['database'];
            $countad = count($xml->general);
            $obj = new stdClass();
            for ($i = 0; $i < $countad; $i++) {
                if ($name == 'expautos_admanager') {
                    $countimg = count($xml->general[$i]->images->img);
                    $imgdatname = $xml['imgdatabase'];
                }
                if ($name == 'categories') {
                    $countast = count($xml->general[$i]->assets->ast);
                    $astdatname = $xml['assetdatabase'];
                }
                foreach ($xml->general[$i]->children() as $key => $value) {
                    if ($name == 'expautos_admanager' && $value->getName() == 'images') {
                        $obj2 = new stdClass();
                        for ($c = 0; $c < $countimg; $c++) {
                            foreach ($xml->general[$i]->images->img[$c]->children() as $key2 => $value2) {
                                $obj2->$key2 = str_replace('\\"', '"', $value2);
                            }
                            if ($select) {
                                $db->updateObject('#__expautos_images', $obj2, 'id');
                            } else {
                                $db->insertObject('#__expautos_images', $obj2);
                            }
                        }
                    }
                    elseif ($name == 'categories' && $value->getName() == 'assets') {
                        $obj2 = new stdClass();
                        for ($c = 0; $c < $countast; $c++) {
                            foreach ($xml->general[$i]->assets->ast[$c]->children() as $key2 => $value2) {
                                $obj2->$key2 = str_replace('\\"', '"', $value2);
                            }
                            if ($select) {
                                $db->updateObject('#__assets', $obj2, 'id');
                            } else {
                                $db->insertObject('#__assets', $obj2);
                            }
                        }
                    } else {
                        $obj->$key = str_replace('\\"', '"', $value);
                    }
                }
                if ($select) {
                    $db->updateObject('#__' . $name, $obj, 'id');
                } else {
                    $db->insertObject('#__' . $name, $obj);
                }
            }
            $this->setError($uploadfile['name']);
            return true;
        } else {
            $this->setError(JText::_('COM_EXPAUTOSPRO_CSVIMPORT_ERROR_NOXMLFILE_TEXT'));
            return false;
        }
    }

}