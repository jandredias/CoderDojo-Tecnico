<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011-15 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.html.parameter' );

$colspan_hide = 0;
if (!($this->params->get('show_begin_date_in_table'))) $colspan_hide += 1;
if (!($this->params->get('show_begin_date_in_table'))) $colspan_hide += 1;
if (!($this->params->get('show_end_date_in_table'))) $colspan_hide += 1;
if (!($this->params->get('show_location'))) $colspan_hide += 1;
if (!($this->params->get('show_price_in_table'))) $colspan_hide += 1;
if (!($this->params->get('show_booking_deadline_in_table'))) $colspan_hide += 1;
if (!($this->params->get('enable_bookings'))) $colspan_hide += 1;
$colspan = 9 - $colspan_hide;
// $colspan = ($this->params->get('show_location')) ? 7 : 6;
$Itemid = JRequest::getInt('Itemid');
?>

<div class="mdl-card__supportedtext" style="width: calc(100% - 32px);margin:0 auto;">
	<form action="<?php echo $this->action;?>" method="post" id="adminForm">
		<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" style="width:100%;margin:0 auto; padding:calc(100%-32px);margin-bottom:16px;">
		  <thead>
		    <tr>
					<th><?php echo JText::_('COM_SEMINARMAN_COURSE_CODE'); ?></th>
					<th><?php echo JText::_('COM_SEMINARMAN_COURSE_TITLE'); ?></th>

					<?php if ($this->params->get('show_tags_in_table')){ ?>
					<th><?php echo JText::_('COM_SEMINARMAN_ASSIGNED_TAGS'); ?></th>
					<?php } ?>

					<?php if ($this->params->get('show_begin_date_in_table')){ ?>
					<th><?php echo JText::_('COM_SEMINARMAN_START_DATE'); ?></th>
					<?php } ?>

					<?php if ($this->params->get('show_end_date_in_table')){ ?>
					<th><?php echo JText::_('COM_SEMINARMAN_FINISH_DATE'); ?></th>
					<?php } ?>

					<?php if ($this->params->get('show_location')){ ?>
					<th><?php echo JText::_('COM_SEMINARMAN_LOCATION'); ?></th>
					<?php } ?>

					<?php if ($this->params->get('show_price_in_table')){ ?>
					<th><?php echo JText::_('COM_SEMINARMAN_PRICE'); ?>
						<?php echo ($this->params->get('show_gross_price') != 2) ? "*" : ""; ?>
					</th>
					<?php } ?>

					<?php if ($this->params->get('show_booking_deadline_in_table')){ ?>
					<th><?php echo JText::_('COM_SEMINARMAN_BOOKING_DEADLINE'); ?></th>
					<?php } ?>

					<?php if ($this->params->get('enable_bookings')){ ?>
					<th></th>
					<?php } ?>
		    </tr>
		  </thead>
		  <tbody>
				<?php
				$i = 0;
				foreach ($this->courses as $course){ ?>
		    <tr>
					<td data-title="<?php echo JText::_('COM_SEMINARMAN_COURSE_CODE'); ?>">
						<?php echo $this->escape($course->code); ?>
					</td>

					<td data-title="<?php echo JText::_('COM_SEMINARMAN_COURSE_TITLE'); ?>">
						<strong>
							<a href="<?php echo ($this->params->get('use_alt_link_in_table') && !( empty( $course->alt_url ) || $course->alt_url == "http://" || $course->alt_url == "https://" )) ? $course->alt_url : JRoute::_('index.php?option=com_seminarman&view=courses&cid=' . $this->category->slug . '&id=' . $course->slug . '&Itemid=' . $Itemid); ?>">
								<?php echo $this->escape($course->title); ?>
							</a>
						</strong>
						<?php echo $course->show_new_icon; echo $course->show_sale_icon; ?>
					</td>

					<?php if ($this->params->get('show_tags_in_table')){ ?>
					<td data-title="<?php echo JText::_('COM_SEMINARMAN_ASSIGNED_TAGS'); ?>">
						<?php
						$tags = $course->tags;
					    $n = count($tags);
					    $i = 0;
					    if ($n != 0){
					    	foreach ($tags as $tag){ ?>
									<span>
										<a href="<?php echo JRoute::_('index.php?option=com_seminarman&view=tags&id=' . $tag->slug . '&Itemid=' . $Itemid); ?>"><?php echo $this->escape($tag->name); ?></a>
									</span>
					      	<?php $i++; ?>
									<?php if ($i != $n) echo ',';
								}
							} ?>
					</td>
					<?php } ?>

					<?php if ($this->params->get('show_begin_date_in_table')){ ?>
					<td data-title="<?php echo JText::_('COM_SEMINARMAN_START_DATE'); ?>">
						<?php echo $course->start; ?>
					</td>
					<?php } ?>

					<?php if ($this->params->get('show_end_date_in_table')){ ?>
					<td data-title="<?php echo JText::_('COM_SEMINARMAN_FINISH_DATE'); ?>">
						<?php echo $course->finish;	?>
					</td>
					<?php } ?>

					<?php if ($this->params->get('show_location')){ ?>
						<td headers="qf_location" data-title="<?php echo JText::_('COM_SEMINARMAN_LOCATION'); ?>">
					        <?php
					    if (empty($course->location)) {
					            echo JText::_('COM_SEMINARMAN_NOT_SPECIFIED');
					    }else{
								if ( empty( $course->url ) || $course->url == "http://" ) {
									echo $course->location;
								}else {?>
									<a href='<?php echo $course->url; ?>' target="_blank"><?php echo $course->location; ?></a>
								<?php }
					    } ?>
						</td>
					<?php } ?>
					<?php if ($this->params->get('show_price_in_table')){ ?>
						<td headers="qf_price" data-title="<?php echo JText::_('COM_SEMINARMAN_PRICE'); ?>">
					<?php echo $course->price; ?>
					</td>
					<?php } ?>

					<?php if ($this->params->get('show_booking_deadline_in_table')){ ?>
					<td data-title="<?php echo JText::_('COM_SEMINARMAN_BOOKING_DEADLINE'); ?>">
						<?php echo $course->deadline; ?>
					</td>
					<?php } ?>
					<?php if ($this->params->get('enable_bookings')){ ?>
					<td>
						<?php echo $course->book_link; ?>
					</td>
					<?php } ?>
		    </tr>
				<?php $i++ ?>
				<?php } ?>
		  </tbody>
		</table>
		<input type="hidden" name="option" value="com_seminarman" />
		<input type="hidden" name="filter_order" value="<?php echo $this->lists['filter_order'];?>" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<input type="hidden" name="view" value="category" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $this->category->id;?>" />

		</div>
		<div class="mdl-card__actions mdl-card--border">
			<?php if ($this->params->get('filter') || $this->params->get('display')){ ?>
				<?php if ($this->params->get('filter')){?>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				    <input class="mdl-textfield__input" type="text" name="filter" id="filter" value="<?php echo $this->lists['filter']; ?>" size="15" />
				    <label class="mdl-textfield__label" for="sample3"><?php echo JText::_('COM_SEMINARMAN_COURSE') ?></label>
				  </div>
					<?php
					/*
					<?php echo JText::_('COM_SEMINARMAN_LEVEL') . ': ';?>
					<?php echo $this->lists['filter_experience_level'];?>
					<button  onclick="document.getElementById('adminForm').submit();"><?php echo JText::_('COM_SEMINARMAN_GO');?></button>
					*/
					?>
				<?php } ?>
				<?php if ($this->params->get('display')){ ?>
					<label for="limit"><?php echo JText::_('COM_SEMINARMAN_DISPLAY_NUM') ?></label>
					<?php echo $this->pageNav->getLimitBox(); ?>
				<?php } ?>
			<?php } ?>
		</div>
	</form>
	<?php /* <div class="pagination"><?php echo $this->pageNav->getPagesLinks(); ?></div> */ ?>
