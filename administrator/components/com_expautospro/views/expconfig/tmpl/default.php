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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'expconfig.cancel' || document.formvalidator.isValid(document.id('expautosprocat-form'))) {
            Joomla.submitform(task, document.getElementById('expautosprocat-form'));
        }
    }

</script>
<form enctype="multipart/form-data" action="<?php echo JRoute::_('index.php?option=com_expautospro&layout=edit&id=' . (int) isset($this->item->id)); ?>" method="post" name="adminForm" id="expautosprocat-form" class="form-validate">
    <div class="width-100 fltlft">
        <fieldset class="adminform">
        <ul class="adminformlist">
                    <li><?php echo $this->form->getLabel('license'); ?>
                        <?php echo $this->form->getInput('license'); ?></li>
        </ul>
        </fieldset>
    </div>
    <div class="width-100 fltlft">
        <fieldset class="adminform">
            <?php echo JHtml::_('tabs.start', 'expcategory-tabs-' . isset($this->item->id), array('useCookie' => 1)); ?>
            <?php echo $this->loadTemplate('params'); ?>
            <?php echo JHtml::_('tabs.end'); ?>
            <input type="hidden" name="task" value="" />
            <?php echo JHtml::_('form.token'); ?>
            <div class="clr"></div>
        </fieldset>
    </div>
</form>
