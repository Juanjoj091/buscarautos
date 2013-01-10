<?php
/**
 * IceAccordion Extension for Joomla 1.6 By IceTheme
 * 
 * 
 * @copyright	Copyright (C) 2008 - 2011 IceTheme.com. All rights reserved.
 * @license		GNU General Public License version 2
 * 
 * @Website 	http://www.icetheme.com/Joomla-Extensions/iceaccordion.html
 * @Support 	http://www.icetheme.com/Forums/IceAccordion/
 *
 */
 

/* no direct access*/
defined('_JEXEC') or die;
?>
<?php for( $i=$limitstart; $i< $limit; $i++) : ?>
		<?php 
				$item = isset( $list[ $i ] )?$list[ $i ]:null;
				if( $item ):
		?>
        	<h4 class="toggler <?php echo $iceaccordion_toggler ?>"><span><?php echo $item->title ?></span></h4>
				<div class="accordion_content <?php echo $iceaccordion_content ?>">
                	<div class="padding">
            <?php if( $itemContent == 'introtext' ) : ?>
					<?php echo $item->introtext;?>
					<?php else: ?>
					<?php echo $item->mainImage; ?>
					<?php echo $item->description; ?>
          <?php endif; ?>          
					<?php if( $params->get('show_readmore','0') ) : ?>
                    	 <p class="readmore">
                          <a <?php echo $target;?>  href="<?php echo $item->link;?>" title="<?php echo $item->title;?>"><?php echo JText::_('Read more ...');?></a>
                          </p>
					<?php endif; ?>
                    </div>
                </div>
		<?php endif; ?>
        <?php endfor; ?>