<?php
/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */


defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');

class JFormFieldExpinstall extends JFormField {

    protected $type = 'Expinstall';

    public function getInput() {
        ?>
        <script type="text/javascript">
            Joomla.submitupload = function(pressbutton) {
                var formupload = document.getElementById('install_package');
                if (formupload.value == ""){
                    alert("<?php echo JText::_('COM_EXPAUTOSPRO_MSG_INSTALL_PLEASE_SELECT_A_PACKAGE'); ?>");
                    return false;
                } else {
                    Joomla.submitform('expconfig.expinstall', this.form,'5555');
                }
            }
        </script>
        <input class="input_box" id="install_package" name="install_package" type="file" size="17" />
        <input class="button" type="submit" value="<?php echo JText::_('COM_EXPAUTOSPRO_UPLOADSKIN_BUTTON_TEXT'); ?>" onclick="Joomla.submitupload();return false;" />
        <input type="hidden" name="type" value="" />
        <input type="hidden" name="installtype" value="upload" />
        <?php echo JHtml::_('form.token'); ?>
        <?php
    }

}