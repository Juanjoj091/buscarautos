<?php

/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

defined('_JEXEC') or die;
require_once JPATH_COMPONENT . '/helpers/helper.php';
$params_file = JPATH_COMPONENT . '/skins/expmail/default/parameters/params.php';
if (file_exists($params_file))
    require_once $params_file;
ExpAutosProHelper::expskin_lang('expmail', 'default');
$mailhtml = '<div style="width:100%; background-color:#999999; padding-bottom:20px; color:#000; text-align:justify" align="center">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
                <tr>
                    <td><img src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/spacer.gif" width="35" height="1" border="0" alt="" /></td>
                    <td><img src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/spacer.gif" width="85" height="1" border="0" alt="" /></td>
                    <td><img src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/spacer.gif" width="418" height="1" border="0" alt="" /></td>
                    <td><img src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/spacer.gif" width="24" height="1" border="0" alt="" /></td>
                    <td><img src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/spacer.gif" width="3" height="1" border="0" alt="" /></td>
                    <td><img src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/spacer.gif" width="35" height="1" border="0" alt="" /></td>
                    <td><img src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/spacer.gif" width="1" height="1" border="0" alt="" /></td>
                </tr>
                <tr>
                    <td colspan="6"><img name="header_right" src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/header_right.png" width="600" height="40" border="0" id="header_right" alt="" /></td>
                    <td><img src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/spacer.gif" width="1" height="40" border="0" alt="" /></td>
                </tr>
                <tr>
                    <td colspan="6"><img name="top_logo" src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/top_logo.png" width="600" height="98" border="0" id="top_logo" alt="" /></td>
                    <td><img src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/spacer.gif" width="1" height="98" border="0" alt="" /></td>
                </tr>
                <tr>
                    <td colspan="4" width="562" height="25" valign="middle" style="text-align:right; background-color:#333; color:#FFF; font-size:12px;">';
        if($skin_top_text){
            $mailhtml .= $skin_top_text;
        }
        $mailhtml .= '</td>
                    <td rowspan="2" colspan="2" width="38" height="25" style="text-align:right; background-color:#333; color:#FFF; font-size:12px;"></td>
                    <td><img src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/spacer.gif" width="1" height="24" border="0" alt="" /></td>
                </tr>
                <tr>
                    <td colspan="6" width="600" height="20" style="background-color:#FFF;"></td>
                    <td><img src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/spacer.gif" width="1" height="20" border="0" alt="" /></td>
                </tr>
                <tr>
                    <td width="35" style="background-color:#FFF;"></td>
                    <td colspan="4" width="530" style="background-color:#FFF;color:#000;">';
        if($skin_body_text){
            $mailhtml .= $skin_body_text;
        }
        $mailhtml .= '</td>
                    <td width="35" style="background-color:#FFF;"></td>
                    <td><img src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/spacer.gif" width="1" border="0" alt="" /></td>
                </tr>
                <tr>
                    <td colspan="6" width="600" height="20" style="background-color:#FFF;"></td>
                    <td><img src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/spacer.gif" width="1" height="20" border="0" alt="" /></td>
                </tr>
                <tr>
                    <td colspan="3"><img name="footer_middle_top" src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/footer_middle_top.png" width="538" height="10" border="0" id="footer_middle_top" alt="" /></td>
                    <td colspan="3"><img name="footer_right_top" src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/footer_right_top.png" width="62" height="10" border="0" id="footer_right_top" alt="" /></td>
                    <td><img src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/spacer.gif" width="1" height="10" border="0" alt="" /></td>
                </tr>
                <tr>
                    <td colspan="2" style="background-color:#1E1E1E;"><img name="footer_middle_left" src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/footer_middle_left.png" width="120" height="15" border="0" id="footer_middle_left" alt="" /></td>
                    <td width="418" height="15" style="background-color:#1E1E1E;text-align: right;color:#FFF;">';
        if($skin_footer_text){
            $mailhtml .= $skin_footer_text;
        }
        $mailhtml .= '</td>
                    <td colspan="3" valign="middle" style="background-color:#1E1E1E;"><img name="footer_middle_contact" src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/footer_middle_contact.png" width="62" height="15" border="0" id="footer_middle_contact" alt="" /></td>
                    <td><img src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/spacer.gif" width="1" height="15" border="0" alt="" /></td>
                </tr>
                <tr>
                    <td colspan="6" width="35" style="background-color:#FFF;"><img name="footer_bottom" src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/footer_bottom.png" width="600" height="32" border="0" id="footer_bottom" alt="" /></td>
                    <td><img src="' . JURI::root() . 'components/com_expautospro/skins/expmail/default/images/spacer.gif" width="1" height="32" border="0" alt="" /></td>
                </tr>
            </table>
        </div>';
?>

