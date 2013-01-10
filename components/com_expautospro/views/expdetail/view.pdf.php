<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

// no direct access
defined('_JEXEC') or die;
/* The PDF library was removed in 1.6. */
jimport( 'joomla.application.component.view');

class ExpautosproViewExpdetail extends JView {

    function display($tpl = null) {
        ?>
        <h2>The PDF library was removed in 1.6.</h2>
        <?php
    }

}
?>