<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<div class="mdl-card mdl-color--white mdl-shadow--4dp" style="width:100%;margin-bottom:80px;">
	<div class="mdl-card__supporting-text" style="padding: 0; width:100%;">
		<?php echo $module->content; ?>
	</div>
</div>


<?php /*<div class="custom<?php echo $moduleclass_sfx ?>" <?php if ($params->get('backgroundimage')) : ?> style="background-image:url(<?php echo $params->get('backgroundimage');?>)"<?php endif;?> >
	<?php echo $module->content;?>
</div> */?>
