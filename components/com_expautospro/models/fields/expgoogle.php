<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/


defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
require_once JPATH_SITE . '/components/com_expautospro/helpers/expparams.php';

class JFormFieldExpGoogle extends JFormField {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'ExpGoogle';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getInput() {
        $expparams = ExpAutosProExpparams::getExpParams('config', 1);
        $expgooglewidth = $expparams->get('c_admanager_useradd_googlemaps_width');
        $expgoogleheight = $expparams->get('c_admanager_useradd_googlemaps_height');
        $expgmap = '<div id="exp_map_canvas" style="width: '.$expgooglewidth.'px; height: '.$expgoogleheight.'px;"></div>';
        return $expgmap;
    }

}