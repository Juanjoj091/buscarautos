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

jimport( 'joomla.application.component.view');
class ExpAutosProViewExpAutosPro extends JView {
	
	function display($tpl = null) {
		JHtml::stylesheet( 'administrator/components/com_expautospro/assets/expautospro.css' );
		jimport('joomla.html.pane');

		$db = JFactory::getDBO();
		
		$pane = JPane::getInstance('tabs', array('startOffset'=>0));

		$db->setQuery("SELECT count(*) FROM #__expautos_admanager");			
		$total_ad = $db->loadResult();

		$db->setQuery("SELECT count(*) FROM #__expautos_admanager WHERE state=1");			
		$total_publ = $db->loadResult();

		$db->setQuery("SELECT count(*) FROM #__expautos_admanager WHERE state=0");			
		$total_unpubl = $db->loadResult();

		$db->setQuery("SELECT count(*) FROM #__expautos_expuser");			
		$total_user = $db->loadResult();

		$db->setQuery("SELECT license FROM #__expautos_config");			
		$license_num = $db->loadResult();

		$db->setQuery("SELECT version FROM #__expautos_config");			
		$version_num = $db->loadResult();

                $this->assignRef('pane',	$pane);
                $this->assignRef('total_ad',	$total_ad);
                $this->assignRef('total_publ',	$total_publ);
                $this->assignRef('total_unpubl',$total_unpubl);
                $this->assignRef('total_user',	$total_user);
                $this->assignRef('license_num',	$license_num);
                $this->assignRef('version_num',	$version_num);
    	
        parent::display($tpl);
    }

	function iconimg($controller,$images,$text)
	{
		$lang = JFactory::getLanguage();
                $link = JRoute::_('index.php?option=com_expautospro&view='.$controller, false);
        ?>
        <div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
            <div class="icon">
                <a href="<?php echo $link; ?>">
                    <?php echo JHTML::_('image', JURI::root().'administrator/components/com_expautospro/assets/images/'.$images , NULL, NULL, $text ); ?>
                    <span><?php echo $text; ?></span>
		</a>
            </div>
	</div>
        <?php
	}
}
?>
