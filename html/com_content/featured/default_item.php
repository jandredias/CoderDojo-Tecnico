<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Create a shortcut for params.
$params  = &$this->item->params;
$images  = json_decode($this->item->images);
$canEdit = $this->item->params->get('access-edit');
$info    = $this->item->params->get('info_block_position', 0);

?>

<!-- Not published -->
<?php if ($this->item->state == 0 ||
					strtotime($this->item->publish_up) > strtotime(JFactory::getDate()) ||
					(	(strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) &&
						$this->item->publish_down != JFactory::getDbo()->getNullDate())) : ?>
	<div class="system-unpublished">
<?php endif; ?>

<?php
/* READ MORE Buttom computation */
if ($params->get('show_readmore') && $this->item->readmore) :
  if ($params->get('access-view')) :
		$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language));
	else :
		$menu = JFactory::getApplication()->getMenu();
		$active = $menu->getActive();
		$itemId = $active->id;
		$link = new JUri(JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId, false));
		$link->setVar('return', base64_encode(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language), false)));
	endif;

/*echo JLayoutHelper::render('joomla.content.readmore', array('item' => $this->item, 'params' => $params, 'link' => $link));*/
endif;
/* End of READ MORE computation */
?>
<?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
	|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') ); ?>


	<div class="mdl-card mdl-shadow--2dp" style="width:100%;margin-bottom:80px;">
		<?php if ($params->get('show_title')) : ?>
			<div class="mdl-card__title"
			<?php if (isset($images->image_intro) && !empty($images->image_intro)) : ?>
			<?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
			style="background: url('<?php echo htmlspecialchars($images->image_intro); ?>') center / cover;height: 200px;"<?php endif; ?>
		>

			<h2 class="mdl-card__title-text" style="color:white;"><?php echo $this->escape($this->item->title); ?></h2>

	  	</div>
		<?php endif; ?>
	  <div class="mdl-card__supporting-text">
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
			<?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
				<?php echo JLayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->item, 'print' => false)); ?>
			<?php endif; ?>
			<?php if (!$params->get('show_intro')) : ?>
				<?php echo $this->item->event->afterDisplayTitle; ?>
			<?php endif; ?>
			<?php echo $this->item->event->beforeDisplayContent; ?> <?php echo $this->item->introtext; ?>



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
				<li class="mdl-menu__item"><?php echo JHtml::_('icon.print_screen', $this->item, $params); ?></li>
				<?php if($canEdit){ ?>
				<li class="mdl-menu__item"><?php echo JHtml::_('icon.edit', $this->item, $params); ?></li>
				<?php } ?>
				<li class="mdl-menu__item"><?php echo JHtml::_('icon.email', $this->item, $params); ?></li>
			</ul>
		</div>

	</div>


<?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate())
	|| ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != '0000-00-00 00:00:00' )) : ?>
	</div>
	<!-- Not published -->
<?php endif; ?>

<?php echo $this->item->event->afterDisplayContent; ?>
