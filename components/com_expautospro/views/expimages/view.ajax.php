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
defined('_JEXEC') or die;

// import Joomla view library
jimport('joomla.application.component.view');
require_once JPATH_COMPONENT . '/helpers/expparams.php';
require_once JPATH_COMPONENT . '/helpers/expfields.php';
require_once JPATH_COMPONENT . '/helpers/helper.php';

class ExpAutosProViewExpimages extends JView {

    public function display($tpl = null) {
        $imgupload = '';
        $imgupload = JRequest::getVar('adimages', array(), 'files', 'array');
        $imgupload2 = JRequest::getVar('adimages2', array(), 'files', 'array');
        $del_imgs = JRequest::getVar('del_image', array(), 'post', 'array');
        $delname = JRequest::getVar('delname', '');
        $delid = JRequest::getInt('delid', 0);
        $data_imgs = JRequest::getInt('data', 0);
        $upload_imgs = JRequest::getInt('upload', 0);
        $expcatid = JRequest::getInt('catid', -2);
        $user = JFactory::getUser();
        $checkaduser = ExpAutosProFields::expaddcheck((int) $expcatid, (int) $user->id);
        if ($checkaduser == $expcatid) {
            if ($imgupload && $expcatid) {
                $this->ajax_img($imgupload, $expcatid);
            }
            if ($imgupload2 && $expcatid) {
                $this->ajax_img2($imgupload2, $expcatid);
            }
            if ($del_imgs && $expcatid) {
                $this->ajax_delimg($del_imgs, $expcatid);
            }
            if ($data_imgs && $expcatid) {
                $this->ajax_data($expcatid);
            }
            if ($delname && $delid && $expcatid) {
                $this->ajax_delimg2($delname, $delid, $expcatid);
            }
        } else {
            die();
        }
    }

    public function ajax_img2($imgupload, $expcatid) {
        $config = ExpAutosProExpparams::getExpParams('config', '1');
        $db = JFactory::getDBO();
        //$application = JFactory::getApplication();
        $expgroupfields = $this->get('Expgroupfields')->get('g_imgcount');
        $restext = '';
        /* Add image */
        if ($imgupload) {
            foreach ($imgupload['name'] as $key => $val) {
                if (!empty($val)) {
                    $imgsize = $imgupload['size'][$key];
                    $name = $imgupload['name'][$key];
                    $tmpname = $imgupload['tmp_name'][$key];
                    $valid_types = array("gif", "jpg", "png", "jpeg", "GIF", "JPG", "PNG", "JPEG");
                    $ext = substr($name, 1 + strrpos($name, "."));

                    if ($name) {
                        $restext = '';
                        if (!in_array($ext, $valid_types)) {
                            $restext .= JText::_(JText::sprintf('COM_EXPAUTOSPRO_IMAGES_ACCEPTTYPE_TEXT', $name)) . " ";
                            $img_return = false;
                        } else {
                            $img_return = true;
                            if (!$size = getimagesize($tmpname)) {
                                $restext .= JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_ERROR_IMGSIZELIMIT_TEXT') . $name . " ";
                                $img_return = false;
                            }

                            if ($imgsize > $config->get('c_images_maxfilesize')) {
                                $imgsizeconvert = ExpAutosProExpparams::exp_convertsize($imgsize);
                                $imglimitsizeconvert = ExpAutosProExpparams::exp_convertsize($config->get('c_images_maxfilesize'));
                                $restext .= JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_ERROR_IMGSIZELIMIT_TEXT') . " $imgsizeconvert ." . JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_ERROR_IMGMAXSIZE_TEXT') . " $imglimitsizeconvert  ";
                                $img_return = false;
                            }

                            if ($size[0] < $config->get('c_images_minsize_width')) {
                                $restext .= JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_ERROR_IMGWIDTHSMALL_TEXT') . ": $name ";
                                $img_return = false;
                            }

                            if ($size[1] < $config->get('c_images_minsize_height')) {
                                $restext .= JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_ERROR_IMGHEIGHTSMALL_TEXT') . ": $name ";
                                $img_return = false;
                            }
                            $restext_all[] = array('error' => $restext);
                        }
                        if ($restext)
                            echo json_encode($restext_all);

                        if ($img_return) {
                            $sufix = uniqid();
                            $path_info = pathinfo($name);
                            $img_name = $path_info['extension'];
                            //$img_name = end(explode(".", strtolower($name)));
                            //$respons_img = $key+1 . "_" . $sufix . "." . $img_name;
                            $respons_img = $expcatid . "_" . $sufix . "." . $img_name;
                            $input_file = $tmpname;
                            $output_tmbfile = ExpAutosProExpparams::ImgAbsPathThumbs() . $respons_img;
                            $output_middlefile = ExpAutosProExpparams::ImgAbsPathMiddle() . $respons_img;
                            $output_bigfile = ExpAutosProExpparams::ImgAbsPathBig() . $respons_img;
                            $options_tmbfile = array(
                                'width' => $config->get('c_images_thumbsize_width'),
                                'height' => $config->get('c_images_thumbsize_height'),
                                'method' => $config->get('c_images_resmethod'),
                                'percent' => $config->get('c_images_percent'),
                                'halign' => $config->get('c_images_horalign'),
                                'valign' => $config->get('c_images_vertalign'),
                                'watermark' => 0,
                            );
                            $options_middlefile = array(
                                'width' => $config->get('c_images_middlesize_width'),
                                'height' => $config->get('c_images_middlesize_height'),
                                'method' => $config->get('c_images_resmethod'),
                                'percent' => $config->get('c_images_percent'),
                                'halign' => $config->get('c_images_horalign'),
                                'valign' => $config->get('c_images_vertalign'),
                                'watermark' => $config->get('c_images_wt_use'),
                            );
                            $img_return = ExpAutosProExpparams::output($input_file, $output_tmbfile, $options_tmbfile);
                            $img_return = ExpAutosProExpparams::output($input_file, $output_middlefile, $options_middlefile);

                            if ($config->get('c_images_maxsize_width') == 0 && $config->get('c_images_maxsize_height') == 0 || $size[0] < $config->get('c_images_maxsize_width') || $size[1] < $config->get('c_images_maxsize_height')) {
                                if ($config->get('c_images_wt_use')) {
                                    $options_bigfile = array(
                                        'percent' => 0,
                                        'method' => $config->get('c_images_resmethod'),
                                        'width' => $size[0],
                                        'height' => $size[1],
                                        'halign' => 0,
                                        'valign' => 0,
                                        'watermark' => $config->get('c_images_wt_use'),
                                    );
                                    $img_return = ExpAutosProExpparams::output($input_file, $output_bigfile, $options_bigfile);
                                } else {
                                    move_uploaded_file($input_file, $output_bigfile);
                                }
                            } else {

                                $options_bigfile = array(
                                    'percent' => $config->get('c_images_percent'),
                                    'method' => $config->get('c_images_resmethod'),
                                    'width' => $config->get('c_images_maxsize_width'),
                                    'height' => $config->get('c_images_maxsize_height'),
                                    'halign' => $config->get('c_images_horalign'),
                                    'valign' => $config->get('c_images_vertalign'),
                                    'watermark' => $config->get('c_images_wt_use'),
                                );
                                $img_return = ExpAutosProExpparams::output($input_file, $output_bigfile, $options_bigfile);
                            }

                            $imgcount = ExpAutosProHelper::getExpcount('images', 'catid', $expcatid);
                            $counterimg = $expgroupfields - $imgcount;
                            if ($counterimg > 0) {
                                if ($img_return) {
                                    $obj = new stdClass();
                                    $obj->catid = (int) $expcatid;
                                    $obj->name = $respons_img;
                                    $obj->ordering = 0;
                                    $obj->description = '';
                                    $db->insertObject('#__expautos_images', $obj);
                                    $resp_info = new stdClass();
                                    $resp_info->name = $respons_img;
                                    $resp_info->id = $db->insertid();
                                    $resp_info->catid = (int) $expcatid;
                                    $resp_info->ordering = '0';
                                    $imgcount = ExpAutosProHelper::getExpcount('images', 'catid', $expcatid);
                                    $resp_info->imgcount = $imgcount;
                                    echo json_encode(array($resp_info));
                                    
                                    ExpAutosProHelper::getExpdataImg($expcatid);
                                    return true;
                                }
                            } else {
                                $filepathtmb = ExpAutosProExpparams::ImgAbsPathThumbs() . $respons_img;
                                $filepathmiddle = ExpAutosProExpparams::ImgAbsPathMiddle() . $respons_img;
                                $filepathbig = ExpAutosProExpparams::ImgAbsPathBig() . $respons_img;

                                $unlinktmb = ExpAutosProExpparams::UnlinkImg($filepathtmb);
                                $unlinkmiddle = ExpAutosProExpparams::UnlinkImg($filepathmiddle);
                                $unlinkbig = ExpAutosProExpparams::UnlinkImg($filepathbig);
                                $restext[] = array('error' => JText::_('COM_EXPAUTOSPRO_IMAGES_LIMITIMG_TEXT'));
                                echo json_encode($restext);
                            }
                        }
                    }
                }
            }
        }
        //$table_img = $this->getTable('Expimages', $prefix = 'ExpautosproTable');
        //$table_img->reorder('catid = '.(int)$expcatid);
        //echo "--catid--".$catid;
        //print_r($imgupload);
    }

    public function ajax_delimg2($delname, $delid, $expcatid) {
        if ($delname && $delid && $expcatid) {
            $db = JFactory::getDBO();
            //echo $i."---num---".$del_img."--name---";
            $filepathtmb = ExpAutosProExpparams::ImgAbsPathThumbs() . $delname;
            $filepathmiddle = ExpAutosProExpparams::ImgAbsPathMiddle() . $delname;
            $filepathbig = ExpAutosProExpparams::ImgAbsPathBig() . $delname;

            $unlinktmb = ExpAutosProExpparams::UnlinkImg($filepathtmb);
            $unlinkmiddle = ExpAutosProExpparams::UnlinkImg($filepathmiddle);
            $unlinkbig = ExpAutosProExpparams::UnlinkImg($filepathbig);

            $query = $db->getQuery(true);
            $query->delete();
            $query->from('#__expautos_images');
            $query->where('id = ' . (int) $delid);
            $db->setQuery((string) $query);
            if ($db->Query()) {
                //$imgcount = ExpAutosProHelper::getExpcount('images', 'catid', $expcatid);
                $return = true;
            } else {
                $return = false;
            }
            ExpAutosProHelper::getExpdataImg($expcatid);
            echo $return;
        }
    }

    public function ajax_data($expcatid) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id,catid,name,ordering,state,description');
        $query->from('#__expautos_images');
        $query->where('catid = ' . $expcatid);
        $query->where('state = 1');
        $query->order('ordering');
        $db->setQuery($query);
        $options = $db->loadObjectList();
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        echo json_encode($options);
        //return json_encode($options);
    }

    public function ajax_img($imgupload, $expcatid) {
        $config = ExpAutosProExpparams::getExpParams('config', '1');
        $db = JFactory::getDBO();
        //$application = JFactory::getApplication();
        $expgroupfields = $this->get('Expgroupfields')->get('g_imgcount');
        $restext = '';
        /* Add image */
        if ($imgupload) {
            foreach ($imgupload['name'] as $key => $val) {
                if (!empty($val)) {
                    $imgsize = $imgupload['size'][$key];
                    $name = $imgupload['name'][$key];
                    $tmpname = $imgupload['tmp_name'][$key];
                    $valid_types = array("gif", "jpg", "png", "jpeg", "GIF", "JPG", "PNG", "JPEG");
                    $ext = substr($name, 1 + strrpos($name, "."));

                    if ($name) {

                        if (!in_array($ext, $valid_types)) {
                            $restext[] = array('text' => "<p class='warning alert alert-error'>" . JText::_(JText::sprintf('COM_EXPAUTOSPRO_IMAGES_ACCEPTTYPE_TEXT', $name)) . "</p>");
                            $img_return = false;
                        } else {
                            $img_return = true;
                            if (!$size = getimagesize($tmpname)) {
                                $restext[] = array('text' => "<p class='warning alert alert-error'>" . JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_ERROR_IMGSIZELIMIT_TEXT') . $name . "</p>");
                                $img_return = false;
                            }

                            if ($imgsize > $config->get('c_images_maxfilesize')) {
                                $imgsizeconvert = ExpAutosProExpparams::exp_convertsize($imgsize);
                                $imglimitsizeconvert = ExpAutosProExpparams::exp_convertsize($config->get('c_images_maxfilesize'));
                                $restext[] = array('text' => "<p class='warning alert alert-error'>" . JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_ERROR_IMGSIZELIMIT_TEXT') . " $imgsizeconvert ." . JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_ERROR_IMGMAXSIZE_TEXT') . " $imglimitsizeconvert </p>");
                                $img_return = false;
                            }

                            if ($size[0] < $config->get('c_images_minsize_width')) {
                                $restext[] = array('text' => "<p class='warning alert alert-error'>" . JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_ERROR_IMGWIDTHSMALL_TEXT') . ": $name </p>");
                                $img_return = false;
                            }

                            if ($size[1] < $config->get('c_images_minsize_height')) {
                                $restext[] = array('text' => "<p class='warning alert alert-error'>" . JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_ERROR_IMGHEIGHTSMALL_TEXT') . ": $name </p>");
                                $img_return = false;
                            }
                        }
                        if ($restext)
                            echo json_encode($restext);

                        if ($img_return) {
                            $sufix = uniqid();
                            $path_info = pathinfo($name);
                            $img_name = $path_info['extension'];
                            //$img_name = end(explode(".", strtolower($name)));
                            //$respons_img = $key+1 . "_" . $sufix . "." . $img_name;
                            $respons_img = $expcatid . "_" . $key + 1 . "_" . $sufix . "." . $img_name;
                            $input_file = $tmpname;
                            $output_tmbfile = ExpAutosProExpparams::ImgAbsPathThumbs() . $respons_img;
                            $output_middlefile = ExpAutosProExpparams::ImgAbsPathMiddle() . $respons_img;
                            $output_bigfile = ExpAutosProExpparams::ImgAbsPathBig() . $respons_img;
                            $options_tmbfile = array(
                                'width' => $config->get('c_images_thumbsize_width'),
                                'height' => $config->get('c_images_thumbsize_height'),
                                'method' => $config->get('c_images_resmethod'),
                                'percent' => $config->get('c_images_percent'),
                                'halign' => $config->get('c_images_horalign'),
                                'valign' => $config->get('c_images_vertalign'),
                                'watermark' => 0,
                            );
                            $options_middlefile = array(
                                'width' => $config->get('c_images_middlesize_width'),
                                'height' => $config->get('c_images_middlesize_height'),
                                'method' => $config->get('c_images_resmethod'),
                                'percent' => $config->get('c_images_percent'),
                                'halign' => $config->get('c_images_horalign'),
                                'valign' => $config->get('c_images_vertalign'),
                                'watermark' => $config->get('c_images_wt_use'),
                            );
                            $img_return = ExpAutosProExpparams::output($input_file, $output_tmbfile, $options_tmbfile);
                            $img_return = ExpAutosProExpparams::output($input_file, $output_middlefile, $options_middlefile);

                            if ($config->get('c_images_maxsize_width') == 0 && $config->get('c_images_maxsize_height') == 0 || $size[0] < $config->get('c_images_maxsize_width') || $size[1] < $config->get('c_images_maxsize_height')) {
                                if ($config->get('c_images_wt_use')) {
                                    $options_bigfile = array(
                                        'percent' => 0,
                                        'method' => $config->get('c_images_resmethod'),
                                        'width' => $size[0],
                                        'height' => $size[1],
                                        'halign' => 0,
                                        'valign' => 0,
                                        'watermark' => $config->get('c_images_wt_use'),
                                    );
                                    $img_return = ExpAutosProExpparams::output($input_file, $output_bigfile, $options_bigfile);
                                } else {
                                    move_uploaded_file($input_file, $output_bigfile);
                                }
                            } else {

                                $options_bigfile = array(
                                    'percent' => $config->get('c_images_percent'),
                                    'method' => $config->get('c_images_resmethod'),
                                    'width' => $config->get('c_images_maxsize_width'),
                                    'height' => $config->get('c_images_maxsize_height'),
                                    'halign' => $config->get('c_images_horalign'),
                                    'valign' => $config->get('c_images_vertalign'),
                                    'watermark' => $config->get('c_images_wt_use'),
                                );
                                $img_return = ExpAutosProExpparams::output($input_file, $output_bigfile, $options_bigfile);
                            }

                            $imgcount = ExpAutosProHelper::getExpcount('images', 'catid', $expcatid);
                            $counterimg = $expgroupfields - $imgcount;
                            if ($counterimg > 0) {
                                if ($img_return) {
                                    $obj = new stdClass();
                                    $obj->catid = (int) $expcatid;
                                    $obj->name = $respons_img;
                                    $obj->ordering = 0;
                                    $obj->description = '';
                                    $db->insertObject('#__expautos_images', $obj);
                                    $resp_info = new stdClass();
                                    $resp_info->imgname = $respons_img;
                                    $resp_info->imgid = $db->insertid();
                                    $imgcount = ExpAutosProHelper::getExpcount('images', 'catid', $expcatid);
                                    $resp_info->imgcount = $imgcount;
                                    echo json_encode(array($resp_info));
                                    ExpAutosProHelper::getExpdataImg($expcatid);
                                    return true;
                                }
                            } else {
                                $filepathtmb = ExpAutosProExpparams::ImgAbsPathThumbs() . $respons_img;
                                $filepathmiddle = ExpAutosProExpparams::ImgAbsPathMiddle() . $respons_img;
                                $filepathbig = ExpAutosProExpparams::ImgAbsPathBig() . $respons_img;

                                $unlinktmb = ExpAutosProExpparams::UnlinkImg($filepathtmb);
                                $unlinkmiddle = ExpAutosProExpparams::UnlinkImg($filepathmiddle);
                                $unlinkbig = ExpAutosProExpparams::UnlinkImg($filepathbig);
                                $restext[] = array('text' => "<p class='warning alert alert-error'>" . JText::_('COM_EXPAUTOSPRO_IMAGES_LIMITIMG_TEXT') . "</p>");
                                echo json_encode($restext);
                            }
                        }
                    }
                }
            }
        }
        //$table_img = $this->getTable('Expimages', $prefix = 'ExpautosproTable');
        //$table_img->reorder('catid = '.(int)$expcatid);
        //echo "--catid--".$catid;
        //print_r($imgupload);
    }

    public function ajax_delimg($del_imgs, $expcatid) {
        if ($del_imgs) {
            $db = JFactory::getDBO();
            foreach ($del_imgs as $i => $del_img) {
                //echo $i."---num---".$del_img."--name---";
                $filepathtmb = ExpAutosProExpparams::ImgAbsPathThumbs() . $del_img;
                $filepathmiddle = ExpAutosProExpparams::ImgAbsPathMiddle() . $del_img;
                $filepathbig = ExpAutosProExpparams::ImgAbsPathBig() . $del_img;

                $unlinktmb = ExpAutosProExpparams::UnlinkImg($filepathtmb);
                $unlinkmiddle = ExpAutosProExpparams::UnlinkImg($filepathmiddle);
                $unlinkbig = ExpAutosProExpparams::UnlinkImg($filepathbig);

                $query = $db->getQuery(true);
                $query->delete();
                $query->from('#__expautos_images');
                $query->where('id = ' . (int) $i);
                $db->setQuery((string) $query);
                if ($db->Query()) {
                    $imgcount = ExpAutosProHelper::getExpcount('images', 'catid', $expcatid);
                    $return[] = array('imgid' => $i, 'imgcount' => $imgcount);
                } else {
                    
                }
                ExpAutosProHelper::getExpdataImg($expcatid);
            }
            echo json_encode($return);
        }
    }

}
