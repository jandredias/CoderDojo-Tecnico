<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.formvalidation');

$params = JComponentHelper::getParams('com_seminarman');
$Itemid = JRequest::getInt('Itemid');
$rowid = (int)JRequest::getVar('row');
?>
<script type="text/javascript">
function submitbuttonSeminarman(task)
{
	var form = document.adminForm;
	Joomla.submitform( task );
}
</script>


<div class="mdl-card mdl-shadow--2dp" style="width:100%;margin-bottom:80px;">
	<div class="mdl-card__title" style="width: calc(100% - 32px);margin:0 auto;">
		<h3 class="mdl-card__title-text mdl-typography--display-1-color-contrast">
			<?php echo JText::_('COM_SEMINARMAN_CANCEL_CONFIRM'); ?>
		</h3>
	</div>
	<div class="mdl-card__title" style="width: calc(100% - 32px);margin:0 auto;">
		<h4 class="mdl-card__title-text mdl-typography--display-1-color-contrast">
			<?php echo JText::_('COM_SEMINARMAN_CANCEL_CONFIRM_ASK'); ?>
		</h4>
	</div>
	<div class="mdl-card__supportedtext" style="width: calc(100% - 32px);margin:0 auto;">
		<?php if ($this->courses[$rowid]->cancel_allowed){ ?>
			<form action="<?php echo $this->action ?>" method="post" name="adminForm" id="adminForm" class="form-validate"  enctype="multipart/form-data">
			<?php echo JText::_('COM_SEMINARMAN_COURSE_CODE').": ".$this->courses[$rowid]->code;?><br />
			<?php echo JText::_('COM_SEMINARMAN_COURSE_TITLE').": ".$this->courses[$rowid]->title;?><br />
			<?php echo JText::_('COM_SEMINARMAN_DATE').": ".$this->courses[$rowid]->start." - ".$this->courses[$rowid]->finish;?><br />
			<?php echo JText::_('COM_SEMINARMAN_LOCATION').": ".$this->courses[$rowid]->location;?><br />
			    <input type="hidden" name="application_id" value="<?php echo $this->courses[$rowid]->applicationid;?>" />
			    <input type="hidden" name="option" value="com_seminarman" />
			    <input type="hidden" name="controller" value="application" />
			    <input type="hidden" name="task" value="" />
			<?php echo JHTML::_('form.token'); ?>

			<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--accent" onclick="submitbuttonSeminarman('cancel_booking_process');"><?php echo JText::_('JYES'); ?></button>
			<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--accent" onclick="submitbuttonSeminarman('no_cancel_booking');"><?php echo JText::_('JNO'); ?></button>
			</form>
		<?php }else{ ?>
			<h5><?php echo JText::_('COM_SEMINARMAN_CANCEL_NOT_ALLOWED'); ?></h5>
		<?php } ?>
	</div>
</div>
