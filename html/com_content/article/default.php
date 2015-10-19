<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

// Create shortcuts to some parameters.
$params  = $this->item->params;
$images  = json_decode($this->item->images);
$urls    = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$user    = JFactory::getUser();
$info    = $params->get('info_block_position', 0);
JHtml::_('behavior.caption');
?>

<?php $useDefList = ($params->get('show_modify_date') ||
										 $params->get('show_publish_date') ||
										 $params->get('show_create_date') ||
										 $params->get('show_hits') ||
										 $params->get('show_category') ||
										 $params->get('show_parent_category') ||
										 $params->get('show_author') );
?>

<?php if ($this->params->get('show_page_heading') != 0) : ?>
<h3 class="mdl-typography--display-1-color-contrast"><?php echo $this->escape($this->params->get('page_heading')); ?></h3>
<?php endif;

if (!empty($this->item->pagination) &&
 		 $this->item->pagination &&
		 !$this->item->paginationposition &&
		 $this->item->paginationrelative){
			 echo $this->item->pagination;
}
?>

<div class="item-page<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="http://schema.org/Article">
	<div class="mdl-card mdl-shadow--2dp" style="width:100%;margin-bottom:80px;">
		<?php if ($params->get('show_title')) : ?>
			<div class="mdl-card__title"
				<?php if (isset($images->image_intro) && !empty($images->image_intro)) : ?>
				<?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
				style="background: url('<?php echo htmlspecialchars($images->image_intro); ?>') center / cover;height: 200px;"<?php endif; ?>
			>
				<h3 class="mdl-card__title-text mdl-typography--display-1-color-contrast"
				<?php if(isset($images->image_intro) && ! empty($images->image_intro)){ ?>
				style="color:white;"<?php } ?>><?php echo $this->escape($this->item->title); ?></h3>

			</div>
		<?php endif; ?>
		<div class="mdl-card__supporting-text" <?php if (!$params->get('show_title')){ echo 'style="padding-top:27px;"';}?>>
			<?php if ($this->item->state == 0) : ?>
				<!-- If not published it will show a message before the article's content -->
				<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
			<?php endif; ?>
			<?php if (strtotime($this->item->publish_up) > strtotime(JFactory::getDate())) : ?>
				<span class="label label-warning"><?php echo JText::_('JNOTPUBLISHEDYET'); ?></span>
			<?php endif; ?>
			<?php if ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != JFactory::getDbo()->getNullDate()) : ?>
				<span class="label label-warning"><?php echo JText::_('JEXPIRED'); ?></span>
			<?php endif; ?>
			<?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
			<?php endif; ?>
			<?php if (!$params->get('show_intro')) : ?>
				<?php echo $this->item->event->afterDisplayTitle; ?>
			<?php endif; ?>
			<?php echo $this->item->event->beforeDisplayContent; ?>
			<?php echo $this->item->text; ?>

			<?php if ($useDefList && ($info == 1 || $info == 2)) : ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'below')); ?>
				<?php if ($params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
					<?php echo JLayoutHelper::render('joomla.content.tags', $this->item->tags->itemTags); ?>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php if ($params->get('show_title') && $params->get('link_titles') && $params->get('access-view')) : ?>

		<?php if ($params->get('show_readmore') && $this->item->readmore) : ?>
		<div class="mdl-card__actions mdl-card--border">
			<a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="<?php echo $link; ?>">Read More</a>
		</div>
		<?php endif; ?>
		<?php endif; ?>
		<div class="mdl-card__menu">
			<button id="menu-options-<?php echo $this->escape($this->item->alias); ?>"
							class="mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect">
				<i class="material-icons">more_vert</i>
			</button>
			<ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
				for="menu-options-<?php echo $this->escape($this->item->alias); ?>">
				<?php if($params->get('show_print_icon')){ ?>
				<li class="mdl-menu__item" onclick="window.print();return false;" href="#">Imprimir</li>
				<?php } ?>
				<?php if($canEdit){ ?>
				<li class="mdl-menu__item"><?php echo JHtml::_('icon.edit', $this->item, $params); ?></li>
				<?php } ?>
				<?php if($params->get('show_email_icon')){ ?>
				<li class="mdl-menu__item"><?php echo JHtml::_('icon.email', $this->item, $params); ?></li>
				<?php } ?>
			</ul>
		</div>
	</div>

	<?php
	if (!empty($this->item->pagination) &&
	 		 $this->item->pagination &&
			 $this->item->paginationposition &&
			 $this->item->paginationrelative){
		echo $this->item->pagination;
	} ?>
	<?php echo $this->item->event->afterDisplayContent; ?>
</div>



<?php /*
TODO
----
<?php if (!$useDefList && $this->print) : ?>
	<div id="pop-print" class="btn hidden-print">

	</div>
	<div class="clearfix"> </div>
<?php endif; ?>

*/?>
