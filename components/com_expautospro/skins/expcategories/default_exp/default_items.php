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

$class = ' first';
if (count($this->items[$this->parent->id]) > 0 && $this->maxLevelcat != 0) :
?>
<ul class="expcat_general">
<?php foreach($this->items[$this->parent->id] as $id => $item) : 
    $class2 = '';
    if($item->level == 1)
        $class2 = ' exptoplevel';
?>
	<?php
	if($this->params->get('show_empty_categories_cat') || $item->numitems || count($item->getChildren())) :
	if(!isset($this->items[$this->parent->id][$id + 1]))
	{
		$class = ' last';
	}
	?>
	<li class="<?php echo $class.$class2; ?>" >
	<?php $class = ''; ?>
                <?php if ($this->params->get('show_description_image') && $item->getParams()->get('image')) : ?>
                    <img class="expcatimg" src="<?php echo $item->getParams()->get('image'); ?>"/>
                <?php endif; ?>
		<span class="item-title<?php echo $class2; ?>">
                    <?php if(in_array($item->id, $this->allowcat)):?>
                        <a href="<?php echo JRoute::_(ExpautosproHelperRoute::getCategoryRoute($item->id));?>">
                    <?php endif;?>
			<?php echo $this->escape($item->title); ?>
                        <?php if ($this->params->get('show_cat_num_links_cat') == 1) :?>
                            <?php 
                            $nexpcount = 0;
                                if($item->level == 1)
                                    $nexpcount = $item->getNumItems(true);
                                else
                                    $nexpcount = $item->numitems;
                                ?>
                                <?php echo JText::_(JText::sprintf('COM_EXPAUTOSPRO_CATSKIN_DEFAULT_EXP_COUNT_NUM', $nexpcount)); ?>
                        <?php endif; ?>
                    <?php if(in_array($item->id, $this->allowcat)):?>
                        </a>
                    <?php endif;?>
		</span>
		<?php if ($this->params->get('show_subcat_desc_cat') == 1) :?>
                    <?php if ($item->description) : ?>
                            <div class="category-desc">
                                    <?php echo JHtml::_('content.prepare', $item->description, '', 'com_expautospro.categories'); ?>
                            </div>
                    <?php endif; ?>
                <?php endif; ?>

            
            <?php
           // print_r($item->level);
            ?>
            
            
		<?php if(count($item->getChildren()) > 0) :
			$this->items[$item->id] = $item->getChildren();
			$this->parent = $item;
			$this->maxLevelcat--;
			echo $this->loadTemplate('items');
			$this->parent = $item->getParent();
			$this->maxLevelcat++;
		endif; ?>

	</li>
	<?php endif; ?>
<?php endforeach; ?>
</ul>
<?php endif; ?>
