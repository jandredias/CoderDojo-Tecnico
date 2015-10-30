<?php
/**
*
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011-2014 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

$html_prices = JHTMLSeminarman::get_price_view($this->course->id, '', $this->vmlink);

if ($this->params->get('enable_payment_overview') == 1) {
	if (($this->params->get('trigger_virtuemart') == 1) && !is_null($this->vmlink)) {
		$next_action = 'save';
	} else {
		$next_action = 'cart';
	}
} else {
	$next_action = 'save';
}

?>
<form action="<?php echo $this->action ?>" method="post" name="adminForm" id="adminForm" class="form-validate"  enctype="multipart/form-data">

<h5><?php echo JText::_('COM_SEMINARMAN_PRICE_SINGLE');?></h5>

<label for="jformprice">* <?php echo JText::_('COM_SEMINARMAN_PRICE_BOOKING'); ?></label>
<fieldset id="booking_price" class="radio" style="margin: 0 0 10px; padding: 0;">
<?php echo $html_prices; ?>
</fieldset>

<?php
	$dispatcher=JDispatcher::getInstance();
	JPluginHelper::importPlugin('seminarman');
	$html_tmpl=$dispatcher->trigger('onGetAddPriceInfo',array($this->course));  // we need the course id
	if(isset($html_tmpl) && !empty($html_tmpl)) echo $html_tmpl[0];
?>

<h5><?php echo JText::_('COM_SEMINARMAN_ATTENDEE_DATA');?></h5>
<p>
	<label for="jformsalutation">* <?php echo JText::_('COM_SEMINARMAN_SALUTATION'); ?>:</label>
	<?php
	$sal = substr_replace($this->lists['salutation'], 'required ', strpos($this->lists['salutation'], 'class=', 0) + 7, 0);
	if (isset($_POST['salutation'])) {
		$sal = substr_replace($sal, 'selected="selected" ', strpos($sal, 'value="' . $_POST['salutation'], 0), 0);
	}
	echo $sal; ?>
</p>

<div class="mdl-grid">
  <input name="title" type="hidden" id="title" value="NA" title="<?php echo JText::_('COM_SEMINARMAN_TITLE') . '::' . JText::_('COM_SEMINARMAN_FILL_IN_DETAILS'); ?>" />
  <?php /*
	<div class="mdl-cell mdl-cell--4-col">
		<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label"> 
			<input class="mdl-textfield__input hasTip tipRight inputbox required"
			name="title" size="20" maxlength="250" type="text" id="title"
			value="<?php echo isset($_POST['title']) ? $_POST['title'] : $this->escape($this->attendeedata->title); ?>"
			title="<?php echo JText::_('COM_SEMINARMAN_TITLE') . '::' . JText::_('COM_SEMINARMAN_FILL_IN_DETAILS'); ?>" />
			<label class="mdl-textfield__label" for="title"><?php echo JText::_('COM_SEMINARMAN_TITLE'); ?></label>
		</div>
	</div>
  */ ?>
	<div class="mdl-cell mdl-cell--4-col">
		<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
			<input class="mdl-textfield__input hasTip tipRight inputbox required"
				name="first_name" size="50" maxlength="250" type="text" id="first_name"
				value="<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : $this->escape($this->attendeedata->first_name); ?>"
				title="<?php echo JText::_('COM_SEMINARMAN_FIRST_NAME') . '::' . JText::_('COM_SEMINARMAN_FILL_IN_DETAILS'); ?>" />
		    <label class="mdl-textfield__label" for="jformfirstname">* <?php echo JText::_('COM_SEMINARMAN_FIRST_NAME'); ?></label>
		  </div>
	</div>

	<div class="mdl-cell mdl-cell--4-col">
		<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		    <input class="mdl-textfield__input hasTip tipRight inputbox required"
				name="last_name" size="50" maxlength="250" type="text" id="last_name"
				value="<?php echo isset($_POST['last_name']) ? $_POST['last_name'] : $this->escape($this->attendeedata->last_name); ?>"
				title="<?php echo JText::_('COM_SEMINARMAN_LAST_NAME') . '::' . JText::_('COM_SEMINARMAN_FILL_IN_DETAILS'); ?>" />
		    <label class="mdl-textfield__label" for="jformlastname">* <?php echo JText::_('COM_SEMINARMAN_LAST_NAME'); ?></label>
		  </div>
	</div>

	<div class="mdl-cell mdl-cell--4-col">
		<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		    <input class="mdl-textfield__input hasTip tipRight inputbox required"
				type="text" maxlength="100"
				class="hasTip tipRight inputbox validate-email required" type="text" id="cm_email" name="email" size="50"
				value="<?php echo isset($_POST['email']) ? $_POST['email'] : $this->escape($this->attendeedata->email); ?>"
				title="<?php echo JText::_('COM_SEMINARMAN_EMAIL') . '::' . JText::_('COM_SEMINARMAN_FILL_IN_DETAILS'); ?>" />
		    <label class="mdl-textfield__label" for="jformemail">* <?php echo JText::_('COM_SEMINARMAN_EMAIL'); ?></label>
		  </div>
	</div>
	<div class="mdl-cell mdl-cell--4-col">
		<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
			<input class="mdl-textfield__input hasTip tipRight inputbox required"
			title="<?php echo JText::_('COM_SEMINARMAN_EMAIL_CONFIRM') . '::' . JText::_('COM_SEMINARMAN_FILL_IN_DETAILS'); ?>"
			class="hasTip tipRight inputbox validate-email required" type="text"
			id="cm_email_confirm" name="email_confirm" size="50" maxlength="100"
			value="<?php echo isset($_POST['email']) ? $_POST['email'] : $this->escape($this->attendeedata->email); ?>" />
			<label class="mdl-textfield__label" for="jformemailconfirm">* <?php echo JText::_('COM_SEMINARMAN_EMAIL_CONFIRM'); ?></label>
		</div>
	</div>
	<?php if ($this->params->get('enable_num_of_attendees')){ ?>
	<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input class="mdl-textfield__input hasTip tipRight inputbox required validate-numeric"
				id='ccb' title="<?php echo JText::_('COM_SEMINARMAN_NR_ATTENDEES') . '::' . JText::_('COM_SEMINARMAN_FILL_IN_DETAILS'); ?>"
				type="text" id="attendees" name="attendees" size="5" maxlength="3"
				value="<?php echo isset($_POST['attendees']) ? $_POST['attendees'] : $this->escape($this->attendeedata->attendees); ?>"/>
				<label class="mdl-textfield__label" for="jformattendees">* <?php echo JText::_('COM_SEMINARMAN_NR_ATTENDEES'); ?></label>
			</div>
	</div>
	<?php } ?>
</div>




	<table class="ccontentTable paramlist">
        <tbody>

    <?php
    // custom fields
    foreach ($this->fields as $name => $this->fieldGroup){
    if ($name != 'ungrouped'){?>
    <tr><td colspan="2"><h3 class="underline"><?php echo JText::_($name);?></h3></td></tr>
    <?php
    }

    ?>

            <?php

            foreach ($this->fieldGroup as $f){
            $f = JArrayHelper::toObject ($f);

            if (isset($_POST['field' . $f->id])) {
				$fp = $_POST['field' . $f->id];
				if (is_array($fp)) {
					switch ($f->type) {
						case "time":
							$f->value = implode(':', $fp);
							break;
						case "url":
							$f->value = implode('', $fp);
							break;
						default:
							$f->value = implode(',', $fp);
					}
				} else {
					$f->value = $fp;
				}
            } else {
				$f->value = $this->escape($f->value);
			}

            ?>
            <tr>
                <td class="paramlist_key vtop" id="lblfield<?php echo $f->id;?>"><label for="lblfield<?php echo $f->id;?>"><?php if ($f->type != "checkboxtos") { if ($f->required == 1) echo '* '; echo JText::_($f->name) . ':'; } ?></label></td>
                <td class="paramlist_value vtop">
                    <?php
                        if (($f->type == "checkboxtos") && ($this->params->get('enable_payment_overview') == 1)) {
                        	if (($this->params->get('trigger_virtuemart') == 1) && !is_null($this->vmlink)) {
                        		echo SeminarmanCustomfieldsLibrary::getFieldHTML($f , '');
                        	}
                        } else {
                        	echo SeminarmanCustomfieldsLibrary::getFieldHTML($f , '');
                        }
                    ?>
                </td>
            </tr>
            <?php
            }

            ?>
    <?php
    }

    ?>
      </tbody>
    </table>




  <div style="margin: 0 auto;margin-bottom: 16px;">
      <?php if (!$this->params->get('enable_multiple_bookings_per_user') && ($this->attendeedata->id > 0) && (!$this->attendeedata->jusertype)){ ?>
      <button type="button" class="btn btn-primary validate" disabled="disabled">
      	<?php echo JText::_('COM_SEMINARMAN_ALREADY_BOOKED'); ?>
      </button>
      <?php }else{ ?>
      <button id="submitSeminarman" type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent btn btn-primary validate" onclick="
      if (document.getElementById('cca') && document.getElementById('ccb')) {
          if (parseInt(document.getElementById('cca').innerHTML) < parseInt(document.getElementById('ccb').value)) {
      	    alert( '<?php echo JText::_( 'COM_SEMINARMAN_BOOKING_GREATER_FREESPACES2' ) ?>' + ' (' + document.getElementById('ccb').value + ') ' + '<?php echo JText::_( 'COM_SEMINARMAN_BOOKING_GREATER_FREESPACES3' ) ?>' + ' (' + document.getElementById('cca').innerHTML + ').' );
          } else {
      	    submitbuttonSeminarman('<?php echo $next_action; ?>')
          }
      }
      else {
      	submitbuttonSeminarman('<?php echo $next_action; ?>')
      }
      ">
				<?php
				  if (($this->params->get('trigger_virtuemart') == 1) && !is_null($this->vmlink)) {
				  	echo JText::_('COM_SEMINARMAN_BOOKING_IN_VM');
				  } else {
				      echo JText::_('COM_SEMINARMAN_SUBMIT');
				  }
				?>
      </button>
      <?php } ?>
  </div>

  <input type="hidden" name="course_id" value="<?php echo $this->course->id;?>" />
  <input type="hidden" name="option" value="com_seminarman" />
  <input type="hidden" name="controller" value="application" />
  <input type="hidden" name="task" value="" />
  <?php echo JHTML::_('form.token'); ?>
</form>








<script type="text/javascript">
HTMLElement.prototype.removeClass = function(remove) {
    var newClassName = "";
    var i;
    var classes = this.className.split(" ");
    for(i = 0; i < classes.length; i++) {
        if(classes[i] !== remove) {
            newClassName += classes[i] + " ";
        }
    }
    this.className = newClassName;
}

var show_styled_tip = <?php echo $this->params->get('show_tooltip_in_form'); ?>;

if (show_styled_tip == 0) {
var list = document.getElementById("course_appform").getElementsByClassName("hasTip");
var listlen= list.length;
for (var i = 0; i < listlen; i++) {
    list[i].removeAttribute("title");
}
for (var i = 0; i < listlen; i++) {
    list[0].removeClass("hasTip");
}
}
</script>