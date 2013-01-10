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
defined('_JEXEC') or die('Restricted access');

class ExpAutosProImages {

    public static function getExpcount($database, $name = '', $val = 0, $state = 1, $exptable = 'expautos_') {
        if (!$database) {
            $database = 'admanager';
        }
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('count(id)');
        $query->from('#__' . $exptable . $database);
        if ($state) {
            $query->where('state = 1');
        }
        if ($val && $name) {
            $query->where($name . ' = ' . $val);
        }

        $db->setQuery($query);

        $result = $db->loadResult();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        return $result;
    }

    public static function getExpParams($database, $id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('params');
        $query->from('#__expautos_' . (string) $database);
        $query->where('id = ' . (int) $id);
        $db->setQuery($query);

        $result = $db->loadResult();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        $value_params = new JRegistry();
        $value_params->loadJSON($result);
        return $value_params;

        /* For example
          $expparams = ExpAutosProImages::getExpParams('config',1);
          $expparams->get('c_user_req_logopatch');
         */
    }

    public static function getCatParams($id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('params');
        $query->from('#__categories');
        $query->where('id = ' . (int) $id);
        $db->setQuery($query);

        $result = $db->loadResult();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        $value_params = new JRegistry();
        $value_params->loadJSON($result);
        return $value_params;

        /* For example
          $expparams = ExpAutosProImages::getExpParams('config',1);
          $expparams->get('c_user_req_logopatch');
         */
    }

    public static function getExpgroupid($usergroups) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('MAX(id)');
        $query->from('#__expautos_userlevel');
        $query->where('userlevel IN (' . $usergroups . ')');
        // Get the options.
        $db->setQuery($query);

        $expgroupid = $db->loadResult();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        return $expgroupid;
    }

    public static function exp_convertsize($fs) {
        if ($fs >= 1073741824)
            $fs = round($fs / 1073741824 * 100) / 100 . " " . JText::_('COM_EXPAUTOSPRO_GB_TEXT');
        elseif ($fs >= 1048576)
            $fs = round($fs / 1048576 * 100) / 100 . " " . JText::_('COM_EXPAUTOSPRO_MB_TEXT');
        elseif ($fs >= 1024)
            $fs = round($fs / 1024 * 100) / 100 . " " . JText::_('COM_EXPAUTOSPRO_KB_TEXT');
        else
            $fs = $fs . " " . JText::_('COM_EXPAUTOSPRO_B_TEXT');
        return $fs;
    }

    public static function ImgUrlPatch() {
        $config = ExpAutosProImages::getExpParams('config', '1');
        $imgpath = $config->get('c_user_req_imgpatch');
        $url = JURI::root() . 'administrator/components/com_expautospro/';
        $image_urlpath = $url . $imgpath;
        return $image_urlpath;
    }

    public static function ImgAbsPath() {
        $config = ExpAutosProImages::getExpParams('config', '1');
        $imgpath = $config->get('c_user_req_imgpatch');
        $image_abspath = JPATH_ROOT . DS . $imgpath;
        return $image_abspath;
    }

    public static function ImgUrlPatchLogo() {
        $config = ExpAutosProImages::getExpParams('config', '1');
        $imgpath = $config->get('c_user_req_logopatch');
        $url = JURI::root();
        $image_urlpath = $url . $imgpath;
        return $image_urlpath;
    }

    public static function ImgAbsPathLogo() {
        $config = ExpAutosProImages::getExpParams('config', '1');
        $imgpath = $config->get('c_user_req_logopatch');
        $image_abspath = JPATH_ROOT . DS . $imgpath;
        return $image_abspath;
    }

    public static function ImgUrlPatchThumbs() {
        $config = ExpAutosProImages::getExpParams('config', '1');
        $imgpath = $config->get('c_images_thumbpatch');
        $url = JURI::root();
        $image_urlpaththumb = $url . $imgpath;
        return $image_urlpaththumb;
    }

    public static function ImgUrlPatchMiddle() {
        $config = ExpAutosProImages::getExpParams('config', '1');
        $imgpath = $config->get('c_images_middlepatch');
        $url = JURI::root();
        $image_urlpathmiddle = $url . $imgpath;
        return $image_urlpathmiddle;
    }

    public static function ImgUrlPatchBig() {
        $config = ExpAutosProImages::getExpParams('config', '1');
        $imgpath = $config->get('c_images_bigpatch');
        $url = JURI::root();
        $image_urlpathbig = $url . $imgpath;
        return $image_urlpathbig;
    }

    public static function ImgAbsPathThumbs() {
        $config = ExpAutosProImages::getExpParams('config', '1');
        $imgpath = $config->get('c_images_thumbpatch');
        $image_abspaththumb = JPATH_ROOT . DS . $imgpath;
        return $image_abspaththumb;
    }

    public static function ImgAbsPathMiddle() {
        $config = ExpAutosProImages::getExpParams('config', '1');
        $imgpath = $config->get('c_images_middlepatch');
        $image_abspathmiddle = JPATH_ROOT . DS . $imgpath;
        return $image_abspathmiddle;
    }

    public static function ImgAbsPathBig() {
        $config = ExpAutosProImages::getExpParams('config', '1');
        $imgpath = $config->get('c_images_bigpatch');
        $image_abspathbig = JPATH_ROOT . DS . $imgpath;
        return $image_abspathbig;
    }

    public static function UnlinkImg($filepath) {
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }

    public static function FilesAbsPath() {
        $config = ExpAutosProImages::getExpParams('config', '1');
        $filepath = $config->get('c_admanager_files_folder');
        $file_abspath = JPATH_ROOT . DS . $filepath;
        return $file_abspath;
    }

    public static function FilesUrlPatch() {
        $config = ExpAutosProImages::getExpParams('config', '1');
        $filepath = $config->get('c_admanager_files_folder');
        $url = JURI::root();
        $file_urlpathbig = $url . $filepath;
        return $file_urlpathbig;
    }

    public static function FoldersName($folder) {
        $folders = JFolder::folders(JPATH_ROOT . DS . 'components/com_expautospro/' . $folder . DS);
        return $folders;
    }

    public static function CreateFolder($patch, $folder = '') {
        $application = JFactory::getApplication();
        switch ($patch) {
            case 1:
                $patch_folder = ExpAutosProImages::ImgAbsPathLogo();
                break;
            case 2:
                $patch_folder = ExpAutosProImages::ImgAbsPathThumbs();
                break;
            case 3:
                $patch_folder = ExpAutosProImages::ImgAbsPathMiddle();
                break;
            case 4:
                $patch_folder = ExpAutosProImages::ImgAbsPathBig();
                break;
            case 5:
                $patch_folder = ExpAutosProImages::FilesAbsPath();
                break;
        }
        if ($folder == '') {
            $folder = $patch_folder;
            $newfolder = $patch_folder . DS . $folder;
        } else {
            $newfolder = JPATH_ROOT . DS . $folder;
        }
        if (JFolder::exists($newfolder)) {
            return true;
        } else {
            if (JFolder::create($newfolder)) {
                $application->enqueueMessage(JText::_('COM_EXPAUTOSPRO_CONFIGS_CREATEIMGDIR_TEXT') . ": $newfolder", 'notice');
                return true;
            } else {
                $application->enqueueMessage(JText::_('COM_EXPAUTOSPRO_CONFIGS_FAILEDCREATEIMGDIR_TEXT') . ": $newfolder", 'error');
                return false;
            }
        }
    }

    /**
     * This is a driver for the thumbnail creating
     *
     * PHP versions 4 and 5
     *
     * LICENSE:
     *
     * The PHP License, version 3.0
     *
     * Copyright (c) 1997-2005 The PHP Group
     *
     * This source file is subject to version 3.0 of the PHP license,
     * that is bundled with this package in the file LICENSE, and is
     * available through the world-wide-web at the following url:
     * http://www.php.net/license/3_0.txt.
     * If you did not receive a copy of the PHP license and are unable to
     * obtain it through the world-wide-web, please send a note to
     * license@php.net so we can mail you a copy immediately.
     *
     * @author      Ildar N. Shaimordanov <ildar-sh@mail.ru>
     * @license     http://www.php.net/license/3_0.txt
     *              The PHP License, version 3.0
     */

    /**
     * Create a GD image resource from given input.
     *
     * This method tried to detect what the input, if it is a file the
     * createImageFromFile will be called, otherwise createImageFromString().
     *
     * @param  mixed $input The input for creating an image resource. The value
     *                      may a string of filename, string of image data or
     *                      GD image resource.
     *
     * @return resource     An GD image resource on success or false
     * @access public
     * @static
     * @see    Thumbnail::imageCreateFromFile(), Thumbnail::imageCreateFromString()
     */
    function imageCreate($input) {
        if (is_file($input)) {
            return ExpAutosProImages::imageCreateFromFile($input);
        } else if (is_string($input)) {
            return ExpAutosProImages::imageCreateFromString($input);
        } else {
            return $input;
        }
    }

// }}}
// {{{
    /**
     * Create a GD image resource from file (JPEG, PNG support).
     *
     * @param  string $filename The image filename.
     *
     * @return mixed            GD image resource on success, FALSE on failure.
     * @access public
     * @static
     */
    function imageCreateFromFile($filename) {
        if (!is_file($filename) || !is_readable($filename)) {
            user_error(JText::_('COM_EXPAUTOSPRO_UNABLEOPENFILE_TEXT') . ' "' . $filename . '"', E_USER_NOTICE);
            return false;
        }
// determine image format
        list(,, $type) = getimagesize($filename);
        switch ($type) {
            case IMAGETYPE_JPEG:
                return imagecreatefromjpeg($filename);
                break;
            case IMAGETYPE_PNG:
                return imagecreatefrompng($filename);
                break;
            case IMAGETYPE_GIF:
                return imagecreatefromgif($filename);
                break;
        }
        user_error(JText::_('EXPA_ADM_UNSUPPORTIMGTYPE_TEXT'), E_USER_NOTICE);
        return false;
    }

// }}}
// {{{
    /**
     * Create a GD image resource from a string data.
     *
     * @param  string $string The string image data.
     *
     * @return mixed          GD image resource on success, FALSE on failure.
     * @access public
     * @static
     */
    function imageCreateFromString($string) {
        if (!is_string($string) || empty($string)) {
            user_error(JText::_('COM_EXPAUTOSPRO_INVALIDIMGSTRING_TEXT'), E_USER_NOTICE);
            return false;
        }
        return imagecreatefromstring($string);
    }

// }}}
// {{{
    /**
     * Display rendered image (send it to browser or to file).
     * This method is a common implementation to render and output an image.
     * The method calls the render() method automatically and outputs the
     * image to the browser or to the file.
     *
     * @param  mixed   $input   Destination image, a filename or an image string data or a GD image resource
     * @param  array   $options Thumbnail options
     *         <pre>
     *         width   int    Width of thumbnail
     *         height  int    Height of thumbnail
     *         percent number Size of thumbnail per size of original image
     *         method  int    Method of thumbnail creating
     *         halign  int    Horizontal align
     *         valign  int    Vertical align
     *         </pre>
     *
     * @return boolean          TRUE on success or FALSE on failure.
     * @access public
     */
    function output($input, $output = null, $options = array()) {
//print_r($input);
//die();
        $config = ExpAutosProImages::getExpParams('config', '1');
        $imgquality = $config->get('c_images_quality');
// Load source file and render image
        $renderImage = ExpAutosProImages::render($input, $options);
        if (!$renderImage) {
            user_error(JText::_('COM_EXPAUTOSPRO_ERRORRENDIMG_TEXT'), E_USER_NOTICE);
            return false;
        }
// Set output image type
// By default PNG image
        $type = isset($options['type']) ? $options['type'] : IMAGETYPE_JPEG;
// Before output to browsers send appropriate headers
        if (empty($output)) {
            $content_type = image_type_to_mime_type($type);
            if (!headers_sent()) {
                header(JText::_('COM_EXPAUTOSPRO_CONTENTTYPE_TEXT') . $content_type);
            } else {
                user_error(JText::_('COM_EXPAUTOSPRO_NOTDISPIMG_TEXT'), E_USER_NOTICE);
                return false;
            }
        }
// Define outputing function
        switch ($type) {
            case IMAGETYPE_PNG:
                $result = empty($output) ? imagepng($renderImage) : imagepng($renderImage, $output, $imgquality);
                break;
            case IMAGETYPE_JPEG:
                $result = empty($output) ? imagejpeg($renderImage) : imagejpeg($renderImage, $output, $imgquality);
                break;
            case IMAGETYPE_GIF:
                $result = empty($output) ? imagegif($renderImage) : imagegif($renderImage, $output, $imgquality);
                break;
            default:
                user_error(JText::_('COM_EXPAUTOSPRO_IMGTYPE_TEXT') . $content_type . JText::_('COM_EXPAUTOSPRO_NOTSUPPORTPHP_TEXT'), E_USER_NOTICE);
                return false;
        }
// Output image (to browser or to file)
        if (!$result) {
            user_error(JText::_('COM_EXPAUTOSPRO_ERROROUTPUTIMG_TEXT'), E_USER_NOTICE);
            return false;
        }
// Free a memory from the target image
        imagedestroy($renderImage);
        return true;
    }

// }}}
// {{{ render()
    /**
     * Draw thumbnail result to resource.
     *
     * @param  mixed   $input   Destination image, a filename or an image string data or a GD image resource
     * @param  array   $options Thumbnail options
     *
     * @return boolean TRUE on success or FALSE on failure.
     * @access public
     * @see    Thumbnail::output()
     */
    function render($input, $options = array()) {

        //$config = & ExpAutosProImages::getExpParams('config', '1');
// Create the source image
        $sourceImage = ExpAutosProImages::imageCreate($input);
//print_r($sourceImage);
//die();
        if (!is_resource($sourceImage)) {
            user_error(JText::_('COM_EXPAUTOSPRO_INVALIDIMGRES_TEXT'), E_USER_NOTICE);
            return false;
        }
        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);
// Set default options
        static $defOptions = array(
    'width' => 150,
    'height' => 150,
    'method' => 0 /* THUMBNAIL_METHOD_SCALE_MAX */,
    'percent' => 0,
    'halign' => 0 /* THUMBNAIL_ALIGN_CENTER */,
    'valign' => 0 /* THUMBNAIL_ALIGN_CENTER */,
    'watermark' => 0,
        );
        foreach ($defOptions as $k => $v) {
            if (!isset($options[$k])) {
                $options[$k] = $v;
            }
        }
// Estimate a rectangular portion of the source image and a size of the target image
        if ($options['method'] == 2 /* THUMBNAIL_METHOD_CROP */) {
            if ($options['percent']) {
                $W = floor($options['percent'] * $sourceWidth);
                $H = floor($options['percent'] * $sourceHeight);
            } else {
                $W = $options['width'];
                $H = $options['height'];
            }
            $width = $W;
            $height = $H;
            $Y = ExpAutosProImages::_coord($options['valign'], $sourceHeight, $H);
            $X = ExpAutosProImages::_coord($options['halign'], $sourceWidth, $W);
        } else {
            $X = 0;
            $Y = 0;
            $W = $sourceWidth;
            $H = $sourceHeight;
            if ($options['percent']) {
                $width = floor($options['percent'] * $W);
                $height = floor($options['percent'] * $H);
            } else {
                $width = $options['width'];
                $height = $options['height'];
                if ($options['method'] == 1/* THUMBNAIL_METHOD_SCALE_MIN */) {
                    $Ww = $W / $width;
                    $Hh = $H / $height;
                    if ($Ww > $Hh) {
                        $W = floor($width * $Hh);
                        $X = ExpAutosProImages::_coord($options['halign'], $sourceWidth, $W);
                    } else {
                        $H = floor($height * $Ww);
                        $Y = ExpAutosProImages::_coord($options['valign'], $sourceHeight, $H);
                    }
                } else {
                    if ($H > $W) {
                        $width = floor($height / $H * $W);
                    } else {
                        $height = floor($width / $W * $H);
                    }
                }
            }
        }
// Create the target image
        if (function_exists('imagecreatetruecolor')) {
            $targetImage = imagecreatetruecolor($width, $height);
        } else {
            $targetImage = imagecreate($width, $height);
        }
        if (!is_resource($targetImage)) {
            user_error(JText::_('COM_EXPAUTOSPRO_NOTINITGDIMG_TEXT'), E_USER_NOTICE);
            return false;
        }
// Copy the source image to the target image
        if ($options['method'] == 2 /* THUMBNAIL_METHOD_CROP */) {
            $result = imagecopy($targetImage, $sourceImage, 0, 0, $X, $Y, $W, $H);
            if ($options['watermark']) {
                ExpAutosProImages::watermark($targetImage, $width, $height, $W, $H);
            }
        } elseif (function_exists('imagecopyresampled')) {
            $result = imagecopyresampled($targetImage, $sourceImage, 0, 0, $X, $Y, $width, $height, $W, $H);
            if ($options['watermark']) {
                ExpAutosProImages::watermark($targetImage, $width, $height, $W, $H);
            }
        } else {
            $result = imagecopyresized($targetImage, $sourceImage, 0, 0, $X, $Y, $width, $height, $W, $H);
            if ($options['watermark']) {
                ExpAutosProImages::watermark($targetImage, $width, $height, $W, $H);
            }
        }
        if (!$result) {
            user_error(JText::_('COM_EXPAUTOSPRO_NOTRESIZEIMG_TEXT'), E_USER_NOTICE);
            return false;
        }
// Free a memory from the source image
        imagedestroy($sourceImage);
// Save the resulting thumbnail
        return $targetImage;
    }

    function watermark($img, $width, $height, $W, $H) {
        $config = ExpAutosProImages::getExpParams('config', '1');
        $wtpath = $config->get('c_images_wt_imagename');
        $patch_folder = ExpAutosProImages::ImgAbsPath();
        $watermark_src = $patch_folder . '/administrator/components/com_expautospro/assets/images/' . $wtpath;
        $watermark = imagecreatefrompng($watermark_src);
        list($watermark_width, $watermark_height) = getimagesize($watermark_src);
        // calculates the x position
        switch ($config->get('c_images_wt_horalign')) {
            case 0:
                $final_x = $config->get('c_images_wt_hormargin');
                break;
            case 1:
                $final_x = round(($width - $watermark_width) / 2);
                break;
            case 2:
            default:
                $final_x = $width - $watermark_width - $config->get('c_images_wt_hormargin');
                break;
        }

        // calculates the y position
        switch ($config->get('c_images_wt_vertalign')) {
            case 0:
                $final_y = $config->get('c_images_wt_vertmargin');
                break;
            case 1:
                $final_y = round(($height - $watermark_height) / 2);
                break;
            case 2:
            default:
                $final_y = $height - $watermark_height - $config->get('c_images_wt_vertmargin');
                break;
        }

        settype($final_x, 'integer');
        settype($final_y, 'integer');
        imagealphablending($watermark, false);

        imagecopy($img, $watermark, $final_x, $final_y, 0, 0, $W, $H);
    }

// }}}
// {{{ _coord()
    function _coord($align, $param, $src) {
        if ($align < 0 /* THUMBNAIL_ALIGN_CENTER */) {
            $result = 0;
        } elseif ($align > 0 /* THUMBNAIL_ALIGN_CENTER */) {
            $result = $param - $src;
        } else {
            $result = ($param - $src) >> 1;
        }
        return $result;
    }

    public static function getExpDealersParams($UserId = 0) {
        $listusergroups = implode(',', JAccess::getGroupsByUser($UserId));
        $listusergroupid = ExpAutosProImages::getExpgroupid($listusergroups);
        $listgroupparams = ExpAutosProImages::getExpParams('userlevel', $listusergroupid);
        return $listgroupparams;
    }

}

?>
