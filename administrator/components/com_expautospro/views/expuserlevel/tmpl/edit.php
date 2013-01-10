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
        if (task == 'expuserlevel.cancel' || document.formvalidator.isValid(document.id('expautosprocat-form'))) {
            Joomla.submitform(task, document.getElementById('expautosprocat-form'));
        }
    }

</script>
<form action="<?php echo JRoute::_('index.php?option=com_expautospro&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="expautosprocat-form" class="form-validate">
    <div class="width-60 fltlft">
        <fieldset class="adminform">
            <legend><?php echo empty($this->item->id) ? JText::_('COM_EXPAUTOSPRO_USERLEVEL_NEW_TITLE') : JText::sprintf('COM_EXPAUTOSPRO_USERLEVEL_EDIT_TITLE', $this->item->id); ?></legend>
            <ul class="adminformlist">
                <li><?php echo $this->form->getLabel('userlevel'); ?>
                    <?php echo $this->form->getInput('userlevel'); ?></li>
                <li><?php echo $this->form->getLabel('state'); ?>
                    <?php echo $this->form->getInput('state'); ?></li>
                <li><?php echo $this->form->getLabel('ordering'); ?>
                    <?php echo $this->form->getInput('ordering'); ?></li>
                <li><?php echo $this->form->getLabel('id'); ?>
                    <?php echo $this->form->getInput('id'); ?></li>
            </ul>
        </fieldset>
    </div>
    <div class="width-40 fltrt">
        <?php 
        //print_r($this->form->getField('userfields'));
        ?>
        <?php echo JHtml::_('sliders.start', 'expuserlevel-sliders-' . $this->item->id, array('useCookie' => 1)); ?>
        <?php echo $this->loadTemplate('params'); ?>
        <?php echo JHtml::_('sliders.end'); ?>
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
    <div class="clr"></div>
</form>
