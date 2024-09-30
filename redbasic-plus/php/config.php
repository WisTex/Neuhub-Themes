<?php

namespace Zotlabs\Theme;

use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\OutputStyle;

class RedbasicPlusConfig {

	function get_schemas() {
		$files = glob('view/theme/redbasic-plus/schema/*.php');

		$scheme_choices = [];

		if($files) {

			if(in_array('view/theme/redbasic-plus/schema/default.php', $files)) {
				$scheme_choices['---'] = t('Default');
				$scheme_choices['focus'] = t('Focus (Hubzilla default)');
			}
			else {
				$scheme_choices['---'] = t('Focus (Hubzilla default)');
			}

			foreach($files as $file) {
				$f = basename($file, ".php");
				if($f != 'default') {
					$scheme_name = $f;
					$scheme_choices[$f] = $scheme_name;
				}
			}
		}

		return $scheme_choices;
	}

	function get() {
		if(! local_channel()) {
			return;
		}

		$arr['primary_color'] = get_pconfig(local_channel(),'redbasic-plus', 'primary_color');
		$arr['success_color'] = get_pconfig(local_channel(),'redbasic-plus', 'success_color');
		$arr['info_color'] = get_pconfig(local_channel(),'redbasic-plus', 'info_color');
		$arr['warning_color'] = get_pconfig(local_channel(),'redbasic-plus', 'warning_color');
		$arr['danger_color'] = get_pconfig(local_channel(),'redbasic-plus', 'danger_color');
		$arr['dark_mode'] = get_pconfig(local_channel(),'redbasic-plus', 'dark_mode');
		$arr['navbar_dark_mode'] = get_pconfig(local_channel(),'redbasic-plus', 'navbar_dark_mode');
		$arr['narrow_navbar'] = get_pconfig(local_channel(),'redbasic-plus', 'narrow_navbar' );
		$arr['nav_bg'] = get_pconfig(local_channel(),'redbasic-plus', 'nav_bg' );
		$arr['nav_bg_dark'] = get_pconfig(local_channel(),'redbasic-plus', 'nav_bg_dark' );
		$arr['bgcolor'] = get_pconfig(local_channel(),'redbasic-plus', 'background_color' );
		$arr['bgcolor_dark'] = get_pconfig(local_channel(),'redbasic-plus', 'background_color_dark' );
		$arr['background_image'] = get_pconfig(local_channel(),'redbasic-plus', 'background_image' );
		$arr['background_image_dark'] = get_pconfig(local_channel(),'redbasic-plus', 'background_image_dark' );
		$arr['font_size'] = get_pconfig(local_channel(),'redbasic-plus', 'font_size' );
		$arr['radius'] = get_pconfig(local_channel(),'redbasic-plus', 'radius' );
		$arr['converse_width']=get_pconfig(local_channel(),"redbasic-plus","converse_width");
		$arr['top_photo']=get_pconfig(local_channel(),"redbasic-plus","top_photo");
		$arr['reply_photo']=get_pconfig(local_channel(),"redbasic-plus","reply_photo");
		$arr['advanced_theming'] = get_pconfig(local_channel(), 'redbasic-plus', 'advanced_theming');
		return $this->form($arr);
	}

	function post() {
		if(!local_channel()) {
			return;
		}

		if (isset($_POST['redbasic-plus-settings-submit'])) {
			if (isset($_POST['redbasic-plus_primary_color']) || isset($_POST['redbasic-plus_radius'])) {

				$primary_color = '';
				$success_color = '';
				$info_color = '';
				$warning_color = '';
				$danger_color = '';
				$radius = floatval($_POST['redbasic-plus_radius']);

				if (preg_match('/^#([A-Fa-f0-9]{3}){1,2}$/', $_POST['redbasic-plus_primary_color'])) {
					$primary_color = $_POST['redbasic-plus_primary_color'];
				}
				if (preg_match('/^#([A-Fa-f0-9]{3}){1,2}$/', $_POST['redbasic-plus_success_color'])) {
					$success_color = $_POST['redbasic-plus_success_color'];
				}
				if (preg_match('/^#([A-Fa-f0-9]{3}){1,2}$/', $_POST['redbasic-plus_info_color'])) {
					$info_color = $_POST['redbasic-plus_info_color'];
				}
				if (preg_match('/^#([A-Fa-f0-9]{3}){1,2}$/', $_POST['redbasic-plus_warning_color'])) {
					$warning_color = $_POST['redbasic-plus_warning_color'];
				}
				if (preg_match('/^#([A-Fa-f0-9]{3}){1,2}$/', $_POST['redbasic-plus_danger_color'])) {
					$danger_color = $_POST['redbasic-plus_danger_color'];
				}

				if ($primary_color || $success_color || $info_color || $warning_color || $danger_color || $radius) {

					try {
						$cache_dir = 'store/[data]/[scss]/';
						if(!is_dir($cache_dir)) {
							os_mkdir($cache_dir, STORAGE_DEFAULT_PERMISSIONS, true);
						}

						$options = [
							'cacheDir' => $cache_dir,
							'prefix' => 'redbasic-plus_',
							'forceRefresh' => false
						];

						$compiler = new Compiler($options);
						$compiler->setImportPaths('vendor/twbs/bootstrap/scss');
						$compiler->setOutputStyle(OutputStyle::COMPRESSED);

						// Set Variables
						if ($primary_color) {
							$variables['$primary'] = $primary_color;
						}
						if ($success_color) {
							$variables['$success'] = $success_color;
						}
						if ($info_color) {
							$variables['$info'] = $info_color;
						}
						if ($warning_color) {
							$variables['$warning'] = $warning_color;
						}
						if ($danger_color) {
							$variables['$danger'] = $danger_color;
						}
						if ($radius) {
							$variables['$border-radius'] = $radius . 'rem';
							$variables['$border-radius-sm'] = $radius/1.5 . 'rem';
							$variables['$border-radius-lg'] = $radius*1.333 . 'rem';
							$variables['$border-radius-xl'] = $radius*2.666 . 'rem';
							$variables['$border-radius-xxl'] = $radius*5.333 . 'rem';
						}

						// Replace Bootstrap Variables with Customizer Variables
						$compiler->replaceVariables($variables);

						$bs = $compiler->compileString('@import "bootstrap.scss";');

						set_pconfig(local_channel(), 'redbasic-plus', 'bootstrap', $bs->getCss());
					} catch (\Exception $e) {
						logger('scssphp: Unable to compile content');
					}
				}
				else {
					set_pconfig(local_channel(), 'redbasic-plus', 'bootstrap', '');
				}
			}

			set_pconfig(local_channel(), 'redbasic-plus', 'primary_color', $_POST['redbasic-plus_primary_color']);
			set_pconfig(local_channel(), 'redbasic-plus', 'success_color', $_POST['redbasic-plus_success_color']);
			set_pconfig(local_channel(), 'redbasic-plus', 'info_color', $_POST['redbasic-plus_info_color']);
			set_pconfig(local_channel(), 'redbasic-plus', 'warning_color', $_POST['redbasic-plus_warning_color']);
			set_pconfig(local_channel(), 'redbasic-plus', 'danger_color', $_POST['redbasic-plus_danger_color']);

			set_pconfig(local_channel(), 'redbasic-plus', 'narrow_navbar', $_POST['redbasic-plus_narrow_navbar']);
			set_pconfig(local_channel(), 'redbasic-plus', 'navbar_dark_mode', $_POST['redbasic-plus_navbar_dark_mode']);
			set_pconfig(local_channel(), 'redbasic-plus', 'dark_mode', $_POST['redbasic-plus_dark_mode']);
			set_pconfig(local_channel(), 'redbasic-plus', 'nav_bg', $_POST['redbasic-plus_nav_bg']);
			set_pconfig(local_channel(), 'redbasic-plus', 'nav_bg_dark', $_POST['redbasic-plus_nav_bg_dark']);
			set_pconfig(local_channel(), 'redbasic-plus', 'background_color', $_POST['redbasic-plus_background_color']);
			set_pconfig(local_channel(), 'redbasic-plus', 'background_color_dark', $_POST['redbasic-plus_background_color_dark']);
			set_pconfig(local_channel(), 'redbasic-plus', 'background_image', $_POST['redbasic-plus_background_image']);
			set_pconfig(local_channel(), 'redbasic-plus', 'background_image_dark', $_POST['redbasic-plus_background_image_dark']);
			set_pconfig(local_channel(), 'redbasic-plus', 'font_size', $_POST['redbasic-plus_font_size']);
			set_pconfig(local_channel(), 'redbasic-plus', 'radius', $_POST['redbasic-plus_radius']);
			set_pconfig(local_channel(), 'redbasic-plus', 'converse_width', $_POST['redbasic-plus_converse_width']);
			set_pconfig(local_channel(), 'redbasic-plus', 'top_photo', $_POST['redbasic-plus_top_photo']);
			set_pconfig(local_channel(), 'redbasic-plus', 'reply_photo', $_POST['redbasic-plus_reply_photo']);
			set_pconfig(local_channel(), 'redbasic-plus', 'advanced_theming', $_POST['redbasic-plus_advanced_theming']);

			// This is used to refresh the cache
			set_pconfig(local_channel(), 'system', 'style_update', time());
		}
	}

	function form($arr) {

		$expert = false;
		if(get_pconfig(local_channel(), 'redbasic-plus', 'advanced_theming')) {
			$expert = true;
		}

	  	$o = replace_macros(get_markup_template('theme_settings.tpl'), array(
			'$submit' => t('Submit'),
			'$baseurl' => z_root(),
			'$theme' => \App::$channel['channel_theme'],
			'$expert' => $expert,
			'$title' => t("Theme settings"),
			'$dark' => t('Dark style'),
			'$light' => t('Light style'),
			'$common' => t('Common settings'),
			'$primary_color' => array('redbasic-plus_primary_color', t('Primary theme color'), $arr['primary_color'], '<i class="fa fa-circle text-primary"></i> ' . t('Current color, leave empty for default')),
			'$success_color' => array('redbasic-plus_success_color', t('Success theme color'), $arr['success_color'], '<i class="fa fa-circle text-success"></i> ' . t('Current color, leave empty for default')),
			'$info_color' => array('redbasic-plus_info_color', t('Info theme color'), $arr['info_color'], '<i class="fa fa-circle text-info"></i> ' . t('Current color, leave empty for default')),
			'$warning_color' => array('redbasic-plus_warning_color', t('Warning theme color'), $arr['warning_color'], '<i class="fa fa-circle text-warning"></i> ' . t('Current color, leave empty for default')),
			'$danger_color' => array('redbasic-plus_danger_color', t('Danger theme color'), $arr['danger_color'], '<i class="fa fa-circle text-danger"></i> ' . t('Current color, leave empty for default')),
			'$dark_mode' => array('redbasic-plus_dark_mode',t('Default to dark mode'),$arr['dark_mode'], '', array(t('No'),t('Yes'))),
			'$navbar_dark_mode' => array('redbasic-plus_navbar_dark_mode',t('Always use light icons for navbar'),$arr['navbar_dark_mode'], t('Enable this option if you use a dark navbar color in light mode'), array(t('No'),t('Yes'))),
			'$narrow_navbar' => array('redbasic-plus_narrow_navbar',t('Narrow navbar'),$arr['narrow_navbar'], '', array(t('No'),t('Yes'))),
			'$nav_bg' => array('redbasic-plus_nav_bg', t('Navigation bar background color'), $arr['nav_bg']),
			'$nav_bg_dark' => array('redbasic-plus_nav_bg_dark', t('Dark navigation bar background color'), $arr['nav_bg_dark']),
			'$bgcolor' => array('redbasic-plus_background_color', t('Set the background color'), $arr['bgcolor']),
			'$bgcolor_dark' => array('redbasic-plus_background_color_dark', t('Set the dark background color'), $arr['bgcolor_dark']),
			'$background_image' => array('redbasic-plus_background_image', t('Set the background image'), $arr['background_image']),
			'$background_image_dark' => array('redbasic-plus_background_image_dark', t('Set the dark background image'), $arr['background_image_dark']),
			'$font_size' => array('redbasic-plus_font_size', t('Set font-size for the entire application'), $arr['font_size'], t('Examples: 1rem, 100%, 16px')),
			'$radius' => array('redbasic-plus_radius', t('Set radius of corners in rem'), $arr['radius'], t('Leave empty for default radius')),
			'$converse_width' => array('redbasic-plus_converse_width',t('Set maximum width of content region in rem'),$arr['converse_width'], t('Leave empty for default width')),
			'$top_photo' => array('redbasic-plus_top_photo', t('Set size of conversation author photo'), $arr['top_photo'], t('Leave empty for default size')),
			'$reply_photo' => array('redbasic-plus_reply_photo', t('Set size of followup author photos'), $arr['reply_photo'], t('Leave empty for default size')),
			'$advanced_theming' => ['redbasic-plus_advanced_theming', t('Show advanced settings'), $arr['advanced_theming'], '', [t('No'), t('Yes')]]
			));

		return $o;
	}

}

function redbasic-plus_theme_admin_enable() {
	// This function is called once when the theme is being enabled by the admin
	// It can be used to register hooks etc.
}

function redbasic-plus_theme_admin_disable() {
	// This function is called once when the theme is being disabled by the admin
	// It can be used to unregister hooks etc.
}




