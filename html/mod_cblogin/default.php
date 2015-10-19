<?php
/**
* Community Builder (TM)
* @version $Id: $
* @package CommunityBuilder
* @copyright (C) 2004-2015 www.joomlapolis.com / Lightning MultiCom SA - and its licensors, all rights reserved
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

JHtml::_( 'behavior.keepalive' );

?>

<div class="cblogin-card-square mdl-card mdl-shadow--2dp">
	<form action="<?php echo $_CB_framework->viewUrl( 'login', true, null, 'html', $secureForm ); ?>" method="post" id="login-form" class="cbLoginForm">
		<input type="hidden" name="option" value="com_comprofiler" />
		<input type="hidden" name="view" value="login" />
		<input type="hidden" name="op2" value="login" />
		<input type="hidden" name="return" value="B:<?php echo $loginReturnUrl; ?>" />
		<input type="hidden" name="message" value="<?php echo (int) $params->get( 'login_message', 0 ); ?>" />
		<input type="hidden" name="loginfrom" value="<?php echo htmlspecialchars( ( defined( '_UE_LOGIN_FROM' ) ? _UE_LOGIN_FROM : 'loginmodule' ) ); ?>" />

	  <div class="mdl-card__title mdl-card--expand">
	    <h2 class="mdl-card__title-text"><?php echo $module->title; ?></h2>
	  </div>
	  <div class="mdl-card__supporting-text">

			<?php echo modCBLoginHelper::getPlugins( $params, $type, 'almostStart' ); ?>
			<?php echo modCBLoginHelper::getPlugins( $params, $type, 'beforeForm' ); ?>
			<?php echo cbGetSpoofInputTag( 'login' ); ?>
			<?php echo modCBLoginHelper::getPlugins( $params, $type, 'start' ); ?>
			<?php if ( $preLogintText ) { ?>
				<div class="pretext">
					<p><?php echo $preLogintText; ?></p>
				</div>
			<?php } ?>
			<?php if ( $loginMethod != 4 ) { ?>
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				  <input class="mdl-textfield__input inputbox" id="modlgn-username"
								 type="text" name="username"
								 size="<?php echo $usernameInputLength; ?>"
								 />
				  <label class="mdl-textfield__label" for="username"><?php echo htmlspecialchars( $userNameText ); ?></label>
				</div>
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				  <input class="mdl-textfield__input inputbox" id="modlgn-passwd"
								 type="password" name="passwd"
								 size="<?php echo $passwordInputLength; ?>"
								 />
				  <label class="mdl-textfield__label" for="passwd"><?php echo htmlspecialchars( CBTxt::T( 'Password' ) ); ?></label>
				</div>


				<?php if ( count( $twoFactorMethods ) > 1 ) { ?>
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				  <input class="mdl-textfield__input inputbox" id="modlgn-secretkey"
								 type="text" name="secretkey"
								 size="<?php echo $secretKeyInputLength; ?>"
								 />
				  <label class="mdl-textfield__label" for="secretkey"><?php echo htmlspecialchars( CBTxt::T( 'Secret Key' ) ); ?></label>
				</div>
				<?php } ?>

				<?php if ( in_array( $showRememberMe, array( 1, 3 ) ) ) { ?>
						<p id="form-login-remember">
							<label for="modlgn-remember"><?php echo htmlspecialchars( CBTxt::T( 'Remember Me' ) ); ?></label>
							<input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"<?php echo ( $showRememberMe == 3 ? ' checked="checked"' : null ); ?> />
						</p>
					<?php } elseif ( $showRememberMe == 2 ) { ?>
						<input id="modlgn-remember" type="hidden" name="remember" class="inputbox" value="yes" />
					<?php } ?>
				</fieldset>
			<?php } else { ?>
				<?php echo modCBLoginHelper::getPlugins( $params, $type, 'beforeButton', 'p' ); ?>
				<?php echo modCBLoginHelper::getPlugins( $params, $type, 'afterButton', 'p' ); ?>
			<?php } ?>

	  </div>
		<?php if ( $postLoginText ) { ?>
			<div class="mdl-card__supporting-text">
				<?php echo $postLoginText; ?>
			</div>
		<?php } ?>
		<!-- Login Button -->
		<div class="mdl-card__actions mdl-card--border">
			<?php echo modCBLoginHelper::getPlugins( $params, $type, 'beforeButton', 'p' ); ?>
			<button type="submit" name="Submit" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
				<?php if ( in_array( $showButton, array( 1, 2, 3 ) ) ) { ?>
					<span class="<?php echo htmlspecialchars( $templateClass ); ?>">
						<span class="cbModuleLoginIcon fa fa-sign-in" title="<?php echo htmlspecialchars( CBTxt::T( 'Log in' ) ); ?>"></span>
					</span>
				<?php } ?>
				<?php if ( in_array( $showButton, array( 0, 1, 4 ) ) ) { ?>
					<?php echo htmlspecialchars( CBTxt::T( 'Log in' ) ); ?>
				<?php } ?>
			</button>
			<?php echo modCBLoginHelper::getPlugins( $params, $type, 'afterButton', 'p' ); ?>
		</div>
		<!-- End Login Button -->
		<!-- Register Button -->
		<?php if ( $showRegister ) { ?>
	  <div class="mdl-card__actions mdl-card--border">
	    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="<?php echo $_CB_framework->viewUrl( 'registers', true, null, 'html', $secureForm ); ?>">
	      <?php echo CBTxt::T( 'UE_REGISTER', 'Sign up' ); ?>
	    </a>
	  </div>
		<?php } ?>
		<!-- End Register Button -->
		<!-- Recover Login -->
		<?php if ( $showForgotLogin ) { ?>
	  <div class="mdl-card__actions mdl-card--border">
	    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="<?php echo $_CB_framework->viewUrl( 'lostpassword', true, null, 'html', $secureForm ); ?>">
		    <?php echo CBTxt::T( 'Forgot Login?' ); ?>
	    </a>
	  </div>
		<?php } ?>
		<!-- End Recover Login -->
		<?php echo modCBLoginHelper::getPlugins( $params, $type, 'almostEnd' ); ?>
		<?php echo modCBLoginHelper::getPlugins( $params, $type, 'end' ); ?>
		<?php echo modCBLoginHelper::getPlugins( $params, $type, 'afterForm' ); ?>
	</form>
</div>
