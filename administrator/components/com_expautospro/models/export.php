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

class ExpAutosProModelExport extends JModelList {
    public function __construct($config = array())
    {
		parent::__construct();
    }
    
    public static function exportcsv($name, $where = '') {
        $db = JFactory::getDBO();
        $file = $name;  //CSV File name
        $result = $db->setQuery(
                        "SELECT * FROM #__" . $name . " " . $where
        );
        $count = count($db->loadRow($result));
        $lists = $db->loadRowList();
        //$line = str_replace("'","\'",$line);
        $out = '';
        foreach ($lists as $row) {
            for ($i = 0; $i < $count; $i++) {
                $out .= '"' . str_replace('"', '\"', $row["$i"]) . '"';
                if ($i != $count - 1) {
                    $out .=',';
                }
            }
            $out .="\n";
        }
        $filename = $file . "_" . date("Y-m-d_H-i", time());
        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: csv" . date("Y-m-d") . ".csv");
        header("Content-disposition: filename=" . $filename . ".csv");
        print $out;
        /*
          if($out){
          $msg = JText::_( 'COM_EXPAUTOSPRO_CSVSUCCESS_EXPORT_TEXT' );
          }else{
          $msg = JText::_( 'COM_EXPAUTOSPRO_CSVERROR_EXPORT_TEXT' );
          }
          $this->setRedirect( 'index.php?option=com_expautospro&view='.$name, $msg );
         */
        exit();
    }

    public static function exportxml($name, $where = '') {

        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;
        $root = $doc->appendChild($doc->createElement('expautospro'));
        $root->setAttribute('database', $name);

        $db = JFactory::getDBO();
        $file = $name;  //CSV File name
        $result = $db->setQuery(
                        "SELECT * FROM #__" . $name . " " . $where
        );
        $count = count($db->loadRow($result));
        $lists = $db->loadObjectList();

        //$r = $root->appendChild($doc->createElement('general'));
        //$r->setAttribute('database', $name);
        foreach ($lists as $value) {
            $r = $root->appendChild($doc->createElement('general'));
            foreach ($value as $key => $val) {
                if ($val !== NULL) {
                    $f = $r->appendChild($doc->createElement($key));
                    $f->appendChild($doc->createTextNode($val));
                }
            }
            if ($name == 'expautos_admanager') {
                $images_array = $db->setQuery(
                                "SELECT * FROM #__expautos_images WHERE catid = " . $value->id
                );
                $listimages = $db->loadObjectList();
                if($listimages){
                    $f2 = $r->appendChild($doc->createElement('images'));
                    $f2->setAttribute('imgdatabase', 'expautos_images');
                    foreach ($listimages as $value2) {
                        $f3 = $f2->appendChild($doc->createElement('img'));
                        foreach ($value2 as $key2 => $val2) {
                            if ($val2 !== NULL) {
                                $f2a = $f3->appendChild($doc->createElement($key2));
                                $f2a->appendChild($doc->createTextNode($val2));
                            }
                        }
                    }
                }
            }
            if ($name == 'categories') {
                $expasstname = 'com_expautospro.category.'.$value->id;
                $images_array = $db->setQuery(
                                "SELECT * FROM #__assets WHERE name = " .$db->Quote($expasstname)." AND level > 1 "
                );
                $listimages = $db->loadObjectList();
                if($listimages){
                    $f2 = $r->appendChild($doc->createElement('assets'));
                    $f2->setAttribute('assetdatabase', 'assets');
                    foreach ($listimages as $value2) {
                        $f3 = $f2->appendChild($doc->createElement('ast'));
                        foreach ($value2 as $key2 => $val2) {
                            if ($val2 !== NULL) {
                                $f2a = $f3->appendChild($doc->createElement($key2));
                                $f2a->appendChild($doc->createTextNode($val2));
                            }
                        }
                    }
                }
            }
        }
        $filename = $file . "_" . date("Y-m-d_H-i", time());
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: filename=" . $filename . ".xml");
        header("Content-Transfer-Encoding: binary");
        print $doc->saveXML();
        exit();
    }

}