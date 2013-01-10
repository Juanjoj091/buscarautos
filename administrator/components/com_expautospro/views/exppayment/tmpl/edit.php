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
        if (task == 'exppayment.cancel' || document.formvalidator.isValid(document.id('expautosprocat-form'))) {
            Joomla.submitform(task, document.getElementById('expautosprocat-form'));
        }
    }

</script>
<form action="<?php echo JRoute::_('index.php?option=com_expautospro&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="expautosprocat-form" class="form-validate">
    <div class="width-100 fltlft">
        <fieldset class="adminform">
            <legend><?php echo empty($this->item->id) ? JText::_('COM_EXPAUTOSPRO_PAYMENT_NEW_TITLE') : JText::sprintf('COM_EXPAUTOSPRO_PAYMENT_EDIT_TITLE', $this->item->id); ?></legend>
            <ul class="adminformlist">
                <li><?php echo $this->form->getLabel('adid'); ?>
                    <?php echo $this->form->getInput('adid'); ?></li>
                <li><?php echo $this->form->getLabel('payname'); ?>
                    <?php echo $this->form->getInput('payname'); ?></li>
                <li><?php echo $this->form->getLabel('payval'); ?>
                    <?php echo $this->form->getInput('payval'); ?></li>
                <li><?php echo $this->form->getLabel('paysum'); ?>
                    <?php echo $this->form->getInput('paysum'); ?></li>
                <li><?php echo $this->form->getLabel('status'); ?>
                    <?php echo $this->form->getInput('status'); ?></li>
                <li><?php echo $this->form->getLabel('paydate'); ?>
                    <?php echo $this->form->getInput('paydate'); ?></li>
                <li><?php echo $this->form->getLabel('paynotice'); ?>
                    <?php echo $this->form->getInput('paynotice'); ?></li>
                <li><?php echo $this->form->getLabel('id'); ?>
                    <?php echo $this->form->getInput('id'); ?></li>
            </ul>
        </fieldset>
    </div>
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
    <div class="clr"></div>
</form>
