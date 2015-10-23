<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

$params = JComponentHelper::getParams('com_seminarman');

$Itemid = JRequest::getInt('Itemid');
?>

<div class="mdl-card mdl-shadow--2dp" style="width:100%;margin-bottom:80px;">
	<?php if ($this->params->get('show_page_heading', 0)) {
		$page_heading = trim($this->params->get('page_heading'));
			if (!empty($page_heading)) { ?>
		<div class="mdl-card__title" style="width: calc(100% - 32px);margin:0 auto;">
			<h3 class="mdl-card__title-text mdl-typography--display-1-color-contrast">
				<?php echo $page_heading ?>
			</h3>

		</div>
		<?php } else { ?>
			<div class="mdl-card__title" style="width: calc(100% - 32px);margin:0 auto;">
				<h4 class="seminarman bookings">
				<?php echo JText::_('COM_SEMINARMAN_BOOKED_COURSES') ?>
			</h4>
		</div>
	<?php }
			} ?>
		<div class="mdl-card__supportedtext" style="width: calc(100% - 32px);margin:0 auto;padding-bottom:16px;">

			<?php if(!count($this->courses)){ ?>
				<?php echo JText::_('COM_SEMINARMAN_NO_CURRENT_BOOKINGS'); ?>
			<?php }else{ ?>
			<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" style="width:100%;">
			  <thead>

					<th><?php echo JText::_('COM_SEMINARMAN_COURSE_TITLE'); //JHTML::_('grid.sort', 'COM_SEMINARMAN_COURSE_TITLE', 'i.title', $this->lists['filter_order_Dir'], $this->lists['filter_order']); ?></th>
					<th><?php echo JText::_('COM_SEMINARMAN_START_DATE'); //JHTML::_('grid.sort', 'COM_SEMINARMAN_START_DATE', 'i.start_date', $this->lists['filter_order_Dir'], $this->lists['filter_order']);?></th>
					<th><?php echo JText::_('COM_SEMINARMAN_FINISH_DATE'); //JHTML::_('grid.sort', 'COM_SEMINARMAN_FINISH_DATE', 'i.finish_date', $this->lists['filter_order_Dir'],$this->lists['filter_order']);?></th>
					<th><?php echo JText::_('COM_SEMINARMAN_PRICE'); //JHTML::_('grid.sort', 'COM_SEMINARMAN_PRICE', 'i.price', $this->lists['filter_order_Dir'], $this->lists['filter_order']); ?><?php echo ($this->params->get('show_gross_price') != 2) ? "*" : ""; ?></th>
					<?php if ($params->get('invoice_generate') == 1){ ?>
					<th><?php echo JText::_('COM_SEMINARMAN_INVOICE'); ?></th>
					<?php } ?>
					<?php if ($this->params->get('enable_paypal')){ ?>
					<th><?php echo JText::_('COM_SEMINARMAN_PAY_ONLINE'); ?></th>
					<?php } ?>
					<th>Estado</th>

					<th>Disponivel</th>
					<th><!-- Cancelar --></th>
					<?php /*
					<th id="qf_title" class=""><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_COURSE_TITLE', 'i.title', $this->lists['filter_order_Dir'], $this->lists['filter_order']); ?></th>
					<th id="qf_start_date" class=""><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_START_DATE', 'i.start_date', $this->lists['filter_order_Dir'], $this->lists['filter_order']);?></th>
					<th id="qf_finish_date" class=""><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_FINISH_DATE', 'i.finish_date', $this->lists['filter_order_Dir'],$this->lists['filter_order']);?></th>
					<th id="qf_price" class=""><?php echo JHTML::_('grid.sort', 'COM_SEMINARMAN_PRICE', 'i.price', $this->lists['filter_order_Dir'], $this->lists['filter_order']); ?><?php echo ($this->params->get('show_gross_price') != 2) ? "*" : ""; ?></th>
					<?php if ($params->get('invoice_generate') == 1){ ?>
					<th id="qf_invoice" class=""><?php echo JText::_('COM_SEMINARMAN_INVOICE'); ?></th>
					<?php } ?>
					<?php if ($this->params->get('enable_paypal')){ ?>
					<th id="qf_application" class=""><?php echo JText::_('COM_SEMINARMAN_PAY_ONLINE'); ?></th>
					<?php }*/ ?>
			  </thead>
			  <tbody>
					<?php
						$jversion = new JVersion();
						$short_version = $jversion->getShortVersion();
						$count_id = 0;
						foreach ($this->courses as $course){
							$stati = $course->booking_state;
							if ($stati == 0) {
								$stati_text = JText::_( 'COM_SEMINARMAN_SUBMITTED' );
							} elseif ($stati == 1) {
								$stati_text = JText::_( 'COM_SEMINARMAN_PENDING' );
							} elseif ($stati == 2) {
								$stati_text = JText::_( 'COM_SEMINARMAN_PAID' );
							} elseif ($stati == 3) {
								$stati_text = JText::_( 'COM_SEMINARMAN_CANCELED' );
							}

							//$htmlclassnumber = $course->odd + 1;
							if (version_compare($short_version, "3.0", 'ge')) {
									$itemParams = new JRegistry($course->attribs);
							} else {
									$itemParams = new JParameter($course->attribs);
							}
							?>
							<tr>
							<?php if ( $itemParams->get('show_icons', $this->params->get( 'show_icons' ))){ ?>
				 			  <td headers="qf_publish_up" data-title="<?php echo JText::_('#'); ?>">
							    <?php echo $this->pageNav->getRowOffset( $course->count ); ?>
				 	 	    </td>
								<?php }else{ ?>
								<?php } ?>
								<td headers="qf_title"  data-title="<?php echo JText::_('COM_SEMINARMAN_COURSE_TITLE'); ?>">
									<strong><a href="<?php
									echo JRoute::_('index.php?option=com_seminarman&view=courses&cid=' . $this->category->slug . '&id=' . $course->
											slug . '&Itemid=' . $Itemid);
									?>"><?php
									echo $this->escape($course->title);
									?></a></strong><?php
									echo $course->show_new_icon;
									echo $course->show_sale_icon;
									?>
								</td>
								<td headers="qf_start_date" data-title="<?php echo JText::_('COM_SEMINARMAN_START_DATE'); ?>">
								<?php echo $course->start_date;?>
								</td>
								<td headers="qf_finish_date" data-title="<?php echo JText::_('COM_SEMINARMAN_FINISH_DATE'); ?>">
									<?php echo $course->finish_date; ?>
								</td>
								<td headers="qf_price" data-title="<?php echo JText::_('COM_SEMINARMAN_PRICE'); ?>">
									<?php echo $course->price_simple; ?>
								</td>
								<?php if ($params->get('invoice_generate') == 1){
									if (!empty($course->invoice_filename_prefix) && ($course->price > 0)){
										echo '<td class="centered" data-title="' . JText::_('COM_SEMINARMAN_INVOICE') . '"><a href="'. JRoute::_('index.php?option=com_seminarman&view=bookings&layout=invoicepdf&appid=' . $course->applicationid . '&Itemid=' . $Itemid) .'"><img alt="'.JText::_('COM_SEMINARMAN_INVOICE').'_'.$course->applicationid.'.pdf" src="components/com_seminarman/assets/images/mime-icon-16/pdf.png" /></a></td>';
									}else{
										echo '<td class="centered" data-title="' . JText::_('COM_SEMINARMAN_INVOICE') . '">-</td>';
									}
								}
								if ($this->params->get('enable_paypal')){ ?>
								<td headers="qf_book" data-title="<?php echo JText::_('COM_SEMINARMAN_PAY_ONLINE'); ?>"><?php if ($course->price > 0) echo $course->paypal_link; ?>
								</td>
								<?php } ?>
								<td><?php echo $stati_text; ?></td>
								<?php if ($itemParams->get('show_capacity', $this->params->get('show_capacity'))){ ?>
								<td>
									<?php echo $course->capacity; ?>
								</td>
								<?php } ?>
								<td>
									<?php if ($course->cancel_allowed): ?>
												<a class="mdl-button mdl-js-button mdl-js-ripple-effect" style="margin-top: -7px;" href="<?php echo JRoute::_('index.php?option=com_seminarman&controller=application&task=cancel_booking&row='.$count_id.'&'.JSession::getFormToken().'=1'); ?>"><?php echo JText::_('COM_SEMINARMAN_CANCEL'); ?></a>
									<?php endif; ?>
								</td>
							</tr>
						<?php $count_id++;
					  } ?>
						</tbody>
					</table>
			<?php } ?>
		</div>
</div>
