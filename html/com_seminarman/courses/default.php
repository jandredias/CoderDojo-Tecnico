<?php
/**
*
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011-2015 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.formvalidation');
// JHtml::register('behavior.tooltip', $this->clau_tooltip());
$mainframe = JFactory::getApplication();
$params = $mainframe->getParams('com_seminarman');

$Itemid = JRequest::getInt('Itemid');

// custom fields
$js = '';
foreach ($this->fields as $this->fieldGroup){
	foreach ($this->fieldGroup as $f) {
		$f = JArrayHelper::toObject ($f);
		$f->value = $this->escape($f->value);
		$checked = '';

		if ($f->published == 1 && $f->required == 1 && !($f->type == "checkboxtos" && $this->params->get('enable_payment_overview') == 1)) {
			switch ($f->type) {
				case 'checkbox':
					// one of the checkboxes contains invalid value -> error
					// none of the checkboxes selected -> error
					// important: form element is an array!
					$js .= '
		fields = form.elements["field' . $f->id . '[]"];

	    if (Object.prototype.toString.call(fields) != "[object RadioNodeList]") {
	    		field = fields;
				fields = new Array(field);
	    }

		for (i = 0; i < fields.length; i++) {
			if(fields[i] && (fields[i].getAttribute("aria-invalid") == "true" || fields[i].value == "")) {
				if(fields[i].className.indexOf("invalid") < 0) {
					fields[i].className += " invalid";
				}
				return alert("' . JText::sprintf('COM_SEMINARMAN_FIELD_N_CONTAINS_IMPROPER_VALUES', addslashes($f->name)) . '");
			}
		}';
					$js .= '
		var selected = false;
		for (i = 0; i < fields.length; i++) {
			if(fields[i] && fields[i].getAttribute("aria-invalid") == "false" && fields[i].checked) {
				selected = true;
			}
		}
		if (selected == false) {
            return alert("' . JText::sprintf('COM_SEMINARMAN_MISSING', addslashes($f->name)) . '");
		}';
					break;
				case 'radio':
					// one of the radio boxes contains invalid value -> error
					// none of the radio boxes selected -> error
					// important: form element is NOT an array!
					$js .= '
		fields = form.elements["field' . $f->id . '"];

	    if (Object.prototype.toString.call(fields) != "[object RadioNodeList]") {
	    		field = fields;
				fields = new Array(field);
	    }

		for (i = 0; i < fields.length; i++) {
			if(fields[i] && (fields[i].getAttribute("aria-invalid") == "true" || fields[i].value == "")) {
				if(fields[i].className.indexOf("invalid") < 0) {
					fields[i].className += " invalid";
				}
				return alert("' . JText::sprintf('COM_SEMINARMAN_FIELD_N_CONTAINS_IMPROPER_VALUES', addslashes($f->name)) . '");
			}
		}';
					$js .= '
		var selected = false;
		for (i = 0; i < fields.length; i++) {
			if(fields[i] && fields[i].getAttribute("aria-invalid") == "false" && fields[i].checked) {
				selected = true;
			}
		}
		if (selected == false) {
            return alert("' . JText::sprintf('COM_SEMINARMAN_MISSING', addslashes($f->name)) . '");
		}';
					break;
				case 'checkboxtos':
					// checktos field has its own warning message
			$js .= '
		fields = form.getElementById("field' . $f->id . '")
		if(fields.getAttribute("aria-invalid") == "true" || !fields.checked) {
			if(fields.className.indexOf("invalid") < 0) {
				fields.className += " invalid";
			}
			return alert("' . JText::sprintf('COM_SEMINARMAN_ACCEPT_TOS', addslashes($f->name)) . '");
		}';
			        break;
				default:
					// other fields such as text field, date field, drop down box, list field (incl. multi select), URL field...
					$js .= '
		fields = form.getElementById("field' . $f->id . '");
		if(fields.getAttribute("aria-invalid") == "true" || fields.value == ""' . $checked . ') {
			if(fields.className.indexOf("invalid") < 0) {
				fields.className += " invalid";
			}
			return alert("' . JText::sprintf('COM_SEMINARMAN_MISSING', addslashes($f->name)) . '");
		}';
			}
		}
	}
}
$course_attribs = new JRegistry();
$course_attribs->loadString($this->course->attribs);
$show_course_price = $course_attribs->get('show_price');
$site_timezone = SeminarmanFunctions::getSiteTimezone();
?>

<script type="text/javascript">
function submitbuttonSeminarman(task)
{
	var form = document.adminForm;
	var fields;
	/* we are going to call formvalidator twice, by the first call we get all invalid fields at once
	   and they will be marked */
	if (document.formvalidator.isValid(form)) {
		if (form.cm_email.value != form.cm_email_confirm.value) {
			if(form.cm_email.className.indexOf("invalid") < 0) {
				form.cm_email.className += " invalid";
			}
			if(form.cm_email_confirm.className.indexOf("invalid") < 0) {
				form.cm_email_confirm.className += " invalid";
			}
			return alert("<?php echo JText::_('COM_SEMINARMAN_MISSING_EMAIL_CONFIRM', true); ?>");
		}
	}

	if (form.first_name.value == "") {
		if(form.first_name.className.indexOf("invalid") < 0) {
			form.first_name.className += " invalid";
		}
		return alert("<?php echo JText::sprintf('COM_SEMINARMAN_MISSING', JText::_('COM_SEMINARMAN_FIRST_NAME', true)); ?>");
	}
	if (form.last_name.value == "") {
		if(form.last_name.className.indexOf("invalid") < 0) {
			form.last_name.className += " invalid";
		}
		return alert("<?php echo JText::sprintf('COM_SEMINARMAN_MISSING', JText::_('COM_SEMINARMAN_LAST_NAME', true)); ?>");
	}
	if (form.salutation.value == '') {
		if(form.salutation.className.indexOf("invalid") < 0) {
			form.salutation.className += " invalid";
		}
		return alert("<?php echo JText::sprintf('COM_SEMINARMAN_MISSING', JText::_('COM_SEMINARMAN_SALUTATION', true)); ?>");
	}
	if (form.cm_email.value == "") {
		if(form.cm_email.className.indexOf("invalid") < 0) {
			form.cm_email.className += " invalid";
		}
		return alert("<?php echo JText::sprintf('COM_SEMINARMAN_MISSING', JText::_('COM_SEMINARMAN_EMAIL', true)); ?>");
	}
	if (form.cm_email_confirm.value == "") {
		if(form.cm_email_confirm.className.indexOf("invalid") < 0) {
			form.cm_email_confirm.className += " invalid";
		}
		return alert("<?php echo JText::sprintf('COM_SEMINARMAN_MISSING', JText::_('COM_SEMINARMAN_EMAIL_CONFIRM', true)); ?>");
	}
	/* some fields such as checkbox, radio box, tos field can not be checked by formvalidator,
	   therefore this part has to be placed outside of formvalidaor before submit */
	<?php echo $js; ?>

	if (document.formvalidator.isValid(form)) {
		if(document.adminForm.submitSeminarman) {
			document.adminForm.submitSeminarman.disabled = true;
		}
	    Joomla.submitform( task );
	} else {
		return alert("<?php echo JText::_('COM_SEMINARMAN_FIELD_CONTAINS_IMPROPER_VALUES'); ?>");
	}
};
</script>



<div class="mdl-card mdl-shadow--2dp" style="width:100%;margin-bottom:80px;">
	<?php if ($this->params->get('show_page_heading', 0)) { ?>
	<div class="mdl-card__title">
		<h3 class="mdl-card__title-text mdl-typography--display-1-color-contrast">
			<?php $page_heading = trim($this->params->get('page_heading'));
        if (!empty($page_heading)) {
          echo $page_heading . ": " . $this->escape($this->course->title);
        } else {
        	echo $this->course->title;
        }
			?>
		</h3>
	</div>
	<?php } ?>
	<div class="mdl-card__supportedtext">
		<div class="mdl-grid">

			<?php if ($this->params->get('show_start_date') || is_null($this->params->get('show_start_date'))){ ?>
		  <div class="mdl-cell mdl-cell--4-col">
										<dt class="start_date"><?php echo JText::_('COM_SEMINARMAN_START_DATE') . ':'; ?></dt>
										<dd class="start_date"><div><?php echo $this->course->start; ?></div></dd>
			</div>
			<?php } ?>

			<?php if ($this->params->get('show_finish_date') || is_null($this->params->get('show_finish_date'))){ ?>
		  <div class="mdl-cell mdl-cell--4-col">
										<dt class="finish_date"><?php echo JText::_('COM_SEMINARMAN_FINISH_DATE') . ':'; ?></dt>
										<dd class="finish_date"><div><?php echo $this->course->finish; ?></div></dd>
			</div>
			<?php } ?>

			<?php if ($this->params->get('show_booking_deadline')){ ?>
			<div class="mdl-cell mdl-cell--4-col">
										<dt class="booking_deadline"><?php echo JText::_('COM_SEMINARMAN_BOOKING_DEADLINE') . ':'; ?></dt>
										<dd class="booking_deadline"><div><?php echo $this->course->deadline ?></div></dd>
			</div>
			<?php } ?>

			<?php if ($this->params->get('show_modify_date')){ ?>
			<div class="mdl-cell mdl-cell--4-col">
										<dt class="modified"><?php echo JText::_('COM_SEMINARMAN_LAST_REVISED') . ':'; ?></dt>
										<dd class="modified"><div><?php echo $this->course->modified; ?></div></dd>
			</div>
			<?php } ?>

			<div class="mdl-cell mdl-cell--4-col">
				<dt class="reference"><?php echo JText::_('COM_SEMINARMAN_COURSE_CODE') . ':'; ?></dt>
				<dd class="reference"><div><?php if ($this->course->code<>"") echo $this->course->code; else echo "-"; ?></div></dd>
			</div>

			<?php if ($this->params->get('show_hits')){?>
			<div class="mdl-cell mdl-cell--4-col">
										<dt class="hits"><?php echo JText::_('COM_SEMINARMAN_HITS') . ':'; ?></dt>
										<dd class="hits"><div><?php echo $this->course->hits; ?></div></dd>
			</div>
			<?php } ?>

			<?php if ($this->params->get('show_favourites')){ ?>
			<div class="mdl-cell mdl-cell--4-col">
										<dt class="favourites"><?php echo JText::_('COM_SEMINARMAN_FAVOURED') . ':'; ?></dt>
										<dd class="favourites"><div><?php echo $this->favourites . ' ' . seminarman_html::favoure($this->course, $this->params, $this->favoured); ?></div></dd>
			</div>
			<?php } ?>

			<?php if ($show_course_price !== 0){ ?>
			<div class="mdl-cell mdl-cell--4-col">
					<dt class="price"><?php echo JText::_('COM_SEMINARMAN_PRICE') . ':'; ?></dt>
					<dd class="price">
						<div>
							<?php echo $this->course->price_detail; ?>
						</div>
					</dd>
					<?php
					$dispatcher=JDispatcher::getInstance();
					JPluginHelper::importPlugin('seminarman');
					$html_tmpl=$dispatcher->trigger('onGetAddPriceInfoForCourse',array($this->course));  // we need the course id
					if (isset($html_tmpl) && !empty($html_tmpl)) echo $html_tmpl[0];
					?>
			</div>
			<?php } ?>

			<?php if ($this->params->get('show_location')){ ?>
			<div class="mdl-cell mdl-cell--4-col">
					<dt class="location"><?php echo JText::_('COM_SEMINARMAN_LOCATION') . ':'; ?></dt>
					<dd class="location"><div>
					<?php
					if ( empty( $this->course->location ) ) {
						echo JText::_('COM_SEMINARMAN_NOT_SPECIFIED');
					}else {
						if ( empty( $this->course->url ) || $this->course->url == "http://" ) {
							echo $this->course->location;
						}else {?>
							<a href='<?php echo $this->course->url; ?>' target="_blank"><?php echo $this->course->location; ?></a>
						<?php }
					} ?>
					</div></dd>
			</div>
			<?php } ?>

			<?php if ($this->params->get('show_group')){ ?>
			<div class="mdl-cell mdl-cell--4-col">
					<dt class="group"><?php echo JText::_('COM_SEMINARMAN_GROUP') . ':'; ?></dt>
					<dd class="group"><div><?php echo empty($this->course->cgroup) ? JText::_('COM_SEMINARMAN_NOT_SPECIFIED') : $this->course->cgroup; ?></div></dd>
			</div>
			<?php } ?>

			<?php if ( $this->course->image <> '' ){ ?>
			<div class="mdl-cell mdl-cell--4-col">
					<dd class="centered">
						<img src="<?php $baseurl = JURI::base(); echo $baseurl . $this->params->get('image_path', 'images') . '/' . $this->course->image; ?>" alt="">
					</dd>
			</div>
			<?php } ?>



			<?php if ($this->params->get('show_experience_level')){ ?>
			<div class="mdl-cell mdl-cell--4-col">
					<dt class="level"><?php echo JText::_('COM_SEMINARMAN_LEVEL') . ':'; ?></dt>
					<dd class="level"><div><?php $level = $this->escape($this->course->level); echo empty($level) ? JText::_('COM_SEMINARMAN_NOT_SPECIFIED') : $level; ?></div></dd>
			</div>
			<?php } ?>

			<?php if ($this->params->get('show_capacity')){ ?>
			<div class="mdl-cell mdl-cell--4-col">
					<dt class="capacity"><?php
						if ($this->params->get('current_capacity'))
							echo JText::_('COM_SEMINARMAN_FREE_SEATS') . ':';
						else
							echo JText::_('COM_SEMINARMAN_SEATS') . ':';?>
					</dt>
					<dd class="capacity">
						<div>
							<?php echo $this->course->capacity; ?>
						</div>
					</dd>
			</div>
			<?php } ?>

			<?php if ($this->params->get('show_tutor')){ ?>
			<div class="mdl-cell mdl-cell--4-col">
					<dt class="tutor"><?php echo JText::_('COM_SEMINARMAN_TUTOR') . ':'; ?></dt>
					<dd class="tutor">
						<div>
						<?php
								foreach($this->course->tutors as $tutor_key => $tutor_content) {
								if ($tutor_content['tutor_published']) {
																echo '<a href="' . JRoute::_('index.php?option=com_seminarman&view=tutor&id=' . $tutor_key . '&Itemid=' . $Itemid) . '">' . $tutor_content['tutor_display'] . '</a><br />';
														} else {
									echo $tutor_content['tutor_display'] . '<br />';
								}
							}
						?>
						</div>
					</dd>
			</div>
			<?php } ?>

			<div class="mdl-cell mdl-cell--4-col">

			</div>
			<div class="mdl-cell mdl-cell--4-col">

			</div>
			<?php if ($this->course->bookable){ ?>
			<div class="mdl-cell mdl-cell--4-col">
					<dd class="centered">
						<a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent"
						onclick="setVisibility();return false;"
						href="<?php echo JURI::getInstance()->toString(); ?>#course_appform">
						<?php echo JText::_('COM_SEMINARMAN_BOOK_COURSE'); ?>
					</a>
					</dd>
			</div>
			<?php } ?>
		</div>
	</div>

	<?php if (($this->course->count_sessions > 0) &&  ($this->params->get('show_sessions'))){?>
	<!-- Sessions -->
	<div class="mdl-card__supportedtext">
		<h4 style="padding-left:16px;">Sessions</h4>
			<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" style="width:calc(100% - 32px); margin:0 auto; margin-bottom:16px;">
			  <thead>
			    <tr>
			      <th><?php echo JText::_('COM_SEMINARMAN_DATE'); ?></th>
			      <th><?php echo JText::_('COM_SEMINARMAN_START_TIME'); ?></th>
			      <th><?php echo JText::_('COM_SEMINARMAN_FINISH_TIME'); ?></th>
						<th><?php echo JText::_('COM_SEMINARMAN_DURATION'); ?></th>
						<th><?php echo JText::_('COM_SEMINARMAN_ROOM'); ?></th>
			    </tr>
			  </thead>
			  <tbody>
					<?php foreach ($this->course_sessions as $course_session){ ?>
						<tr>
							<td class="centered"><?php echo $course_session->session_date ?></td>
							<td class="centered"><?php echo date('H:i', strtotime($course_session->start_time)) ?></td>
							<td class="centered"><?php echo date('H:i', strtotime($course_session->finish_time)) ?></td>
							<td class="centered"><?php echo $course_session->duration ?></td>
							<td class="centered"><?php echo $course_session->session_location ?></td>
						</tr>
					<?php	} ?>
				</tbody>
			</table>
	</div>
	<!-- End Sessions -->
	<?php } ?>

	<!-- Description -->
	<div class="mdl-card__supportedtext" style="width: calc(100% - 32px);margin:0 auto;">
    <h4 class="description underline"><?php echo JText::_('COM_SEMINARMAN_DESCRIPTION'); ?></h4>
    <div class="description course_text"><?php echo $this->course->text; ?></div>
	</div>
	<!-- End Description -->

	<div class="mdl-card__supportedtext">
		<?php
		$n = count($this->files);
		$i = 0;
		if ($n != 0){ ?>
		  <h3 class="seminarman course_files underline"><?php echo JText::_('COM_SEMINARMAN_FILES_FOR_DOWNLOAD'); ?></h3>
		  <div class="filelist">
		      <?php
		      foreach ($this->files as $file){
						echo JHTML::image($file->icon, '') . ' '; ?>
						<strong>
							<a href="<?php echo JRoute::_('index.php?option=com_seminarman&fileid=' . $file->fileid . '&task=download' . '&Itemid=' . $Itemid); ?>">
								<?php echo $file->altname ? $this->escape($file->altname) : $this->escape($file->filename); ?>
							</a>
						</strong>
						<?php
						$i++;
						if ($i != $n):
						echo ',';
						endif;
					} /* End of ForEach */
		      ?>
		  </div>
		<?php } ?>
	</div>
	<?php if($this->course->bookable){ ?>
	<!-- Booking form -->
	<div class="mdl-card__supportedtext">
		<?php include "default_loadappform.php"; ?>
	</div>
	<!-- End Booking form -->
	<?php } ?>

	<?php if ($this->params->get('show_categories')){ ?>
	<!-- Categories -->
	<div class="mdl-card__supportedtext">
		<h3 class="seminarman course_categories underline"><?php echo JText::_('COM_SEMINARMAN_CATEGORY'); ?></h3>
		<?php $n = count($this->categories);
		$i = 0; ?>
		<div class="categorylist">
				<?php foreach ($this->categories as $category): ?>
					<strong><a href="<?php echo JRoute::_('index.php?option=com_seminarman&view=category&cid=' . $category->slug . '&Itemid=' . $Itemid); ?>"><?php echo $this->escape($category->title); ?></a></strong>
					<?php $i++;
					if ($i != $n):
						echo ',';
					endif;
				endforeach; ?>
		</div>
	</div>
	<!-- End Categories -->
	<?php } ?>

	<?php if ($this->params->get('show_tags')){ ?>
	<!-- Tags -->
	<div class="mdl-card__suportedtext">
		<?php $n = count($this->tags);
		$i = 0;
		if ($n != 0){ ?>
		<h3 class="seminarman course_tags underline"><?php echo JText::_('COM_SEMINARMAN_ASSIGNED_TAGS'); ?></h3>
		<div class="taglist">
			<?php foreach ($this->tags as $tag): ?>
							<strong><a href="<?php echo JRoute::_('index.php?option=com_seminarman&view=tags&id=' . $tag->slug . '&Itemid=' . $Itemid); ?>"><?php echo $this->escape($tag->name); ?></a></strong>
							<?php $i++; if ($i != $n) echo ','; ?>
			<?php endforeach; ?>
		</div>
		<?php } ?>
	</div>
	<!-- End Tags -->
	<?php } ?>

	<?php if ($this->params->get('show_jcomments') || $this->params->get('show_jomcomments')){ ?>
	<!-- Comments -->
	<div class="mdl-card__suportedtext">
			<div class="qf_comments">
					<?php if ($this->params->get('show_jcomments')){
						if (file_exists(JPATH_SITE . DS . 'components' . DS . 'com_jcomments' . DS .
						'jcomments.php')){
							require_once (JPATH_SITE . DS . 'components' . DS . 'com_jcomments' . DS .
							'jcomments.php');
							echo JComments::showComments($this->course->id, 'com_seminarman', $this->escape($this->course->title));
						}
					}
					if ($this->params->get('show_jomcomments')){
						if (file_exists(JPATH_SITE . DS . 'plugins' . DS . 'content' . DS .
						'jom_comment_bot.php')){
							require_once (JPATH_SITE . DS . 'plugins' . DS . 'content' . DS .
							'jom_comment_bot.php');
							echo jomcomment($this->course->id, 'com_seminarman');
						}
					} ?>
		</div>
	</div>
	<!-- End Comments -->
	<?php } ?>

</div>
