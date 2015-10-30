<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_search
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Including fallback code for the placeholder attribute in the search field.
JHtml::_('jquery.framework');
JHtml::_('script', 'system/html5fallback.js', false, true);

?>
<div class="coderdojo-search-box mdl-textfield mdl-js-textfield mdl-textfield--expandable mdl-textfield--floating-label mdl-textfield--align-right mdl-textfield--full-width is-upgraded">
	<form action="<?php echo JRoute::_('index.php');?>" method="post" class="form-inline">
		<label class="mdl-button mdl-js-button mdl-button--icon" for="search-field">
				<i class="material-icons">search</i>
		</label>
		<div class="mdl-textfield__expandable-holder">
				<input class="mdl-textfield__input" type="text" id="search-field">
		</div>
		<input type="hidden" name="task" value="search" />
		<input type="hidden" name="option" value="com_search" />
		<input type="hidden" name="Itemid" value="<?php echo $mitemid; ?>" />
	</form>
</div>
