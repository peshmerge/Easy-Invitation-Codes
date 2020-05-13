<?php
defined('ABSPATH') or die('Cheatin&#8217; uh?');

include('fields.php');

add_filter('plugin_action_links_' . plugin_basename(BAWEIC__FILE__), 'baweic_settings_action_links', 10, 2);
function baweic_settings_action_links($links, $file) {
	array_unshift($links, '<a href="' . admin_url('admin.php?page=baweic_add_code') . '">' . __('Add new code', 'baweic') . '</a>');
	return $links;
}

add_action('admin_init', 'baweic_l10n');
function baweic_l10n() {
	load_plugin_textdomain('baweic', '', dirname(plugin_basename(BAWEIC__FILE__)) . '/lang');
}

add_action('admin_menu', 'baweic_admin_menu');
function baweic_admin_menu() {
	// All my pages
	add_menu_page(__('Invitation Codes List', 'baweic'), __('Invitation Codes', 'baweic'), 'manage_options', 'baweic_list_codes', 'baweic_list_codes', 'dashicons-tickets', 30);
	add_submenu_page('baweic_list_codes', __('Add new code', 'baweic'), __('Add new code', 'baweic'), 'manage_options', 'baweic_add_code', 'baweic_add_code');
	add_submenu_page('baweic_list_codes', __('Generate codes', 'baweic'), __('Generate codes', 'baweic'), 'manage_options', 'baweic_rand_code', 'baweic_rand_code');
	add_submenu_page('baweic_list_codes', __('Codes list (raw)', 'baweic'), __('Codes list (raw)', 'baweic'), 'manage_options', 'baweic_raw_codes', 'baweic_raw_codes');
	add_submenu_page('baweic_list_codes', __('Some options', 'baweic'), __('Some Options', 'baweic'), 'manage_options', 'baweic_settings', 'baweic_settings_page');
	// and registered settings
	register_setting('baweic_add_code', 'baweic_field_code', 'baweic_fields_cb');
	register_setting('baweic_rand_code', 'baweic_field_prefix', 'baweic_fields_cb2');
	register_setting('baweic_settings', 'baweic_fields');
}

function baweic_raw_codes() {
	$baweic_options = get_option('baweic_options');

	?>
		<div class="wrap">
			<h1><?php _e('Invitation Codes List', 'baweic'); ?>
				<a class="add-new-h2" href="<?php echo admin_url('admin.php?page=baweic_list_codes'); ?>"><?php _e('Codes list', 'baweic') ;?></a>
				<a class="add-new-h2" href="<?php echo admin_url('admin.php?page=baweic_add_code'); ?>"><?php _e('Add new codes', 'baweic') ;?></a>
				<a class="add-new-h2" href="<?php echo admin_url('admin.php?page=baweic_rand_code'); ?>"><?php _e('Generate codes', 'baweic') ;?></a>
			</h1>
			<h3><?php _e('Only not used codes', 'baweic'); ?></h3>
			<?php
			foreach ($baweic_options['codes'] as $code => $val) {
				if (!$val['leftcount']) {
					unset($baweic_options['codes'][ $code ]);
				}
			}
			$codes = !empty($baweic_options['codes']) ? implode("\n", array_keys($baweic_options['codes'])) : __('-- No codes!Add one!--', 'baweic');
			?>
			<textarea cols="40" rows="10"><?php echo esc_textarea($codes); ?></textarea>
			<p class="description"><?php _e ('Tips: You can share these codes to allow users to register on your site/blog.', 'baweic'); ?></p>
		</div>
	<?php
}

function baweic_settings_page() {
	settings_errors();
	add_settings_section('baweic_settings', __('Add new code', 'baweic'), '__return_false', 'baweic_settings');
	add_settings_field('baweic_field_code', __('Add link', 'baweic'), 'baweic_field_link', 'baweic_settings', 'baweic_settings');
	add_settings_field('baweic_field_count', __('Text link', 'baweic'), 'baweic_field_text_link', 'baweic_settings', 'baweic_settings');

	?>
		<div class="wrap">
			<h1><?php _e('Invitation Codes Settings', 'baweic'); ?>
				<a class="add-new-h2" href="<?php echo admin_url('admin.php?page=baweic_list_codes'); ?>"><?php _e('Codes list', 'baweic') ;?></a>
				<a class="add-new-h2" href="<?php echo admin_url('admin.php?page=baweic_add_code'); ?>"><?php _e('Add new codes', 'baweic') ;?></a>
				<a class="add-new-h2" href="<?php echo admin_url('admin.php?page=baweic_rand_code'); ?>"><?php _e('Generate codes', 'baweic') ;?></a>
			</h1>

			<form action="options.php" method="post">
				<?php settings_fields('baweic_settings'); ?>
				<?php do_settings_sections('baweic_settings'); ?>
				<?php submit_button(); ?>
			</form>
		</div>
	<?php
}

function baweic_add_code() {
	settings_errors();
	add_settings_section('baweic_add_code', __('Add new code', 'baweic'), '__return_false', 'baweic_add_code');
	add_settings_field('baweic_field_code', __('Code', 'baweic'), 'baweic_field_code', 'baweic_add_code', 'baweic_add_code');
	add_settings_field('baweic_field_count', __('Max count', 'baweic'), 'baweic_field_count', 'baweic_add_code', 'baweic_add_code');

	?>
		<div class="wrap">
			<h1><?php _e('Invitation Codes, add one!', 'baweic'); ?>
				<a class="add-new-h2" href="<?php echo admin_url('admin.php?page=baweic_list_codes'); ?>"><?php _e('Codes list', 'baweic') ;?></a>
				<a class="add-new-h2" href="<?php echo admin_url('admin.php?page=baweic_rand_code'); ?>"><?php _e('Generate codes', 'baweic') ;?></a>
			</h1>

			<form action="options.php" method="post">
				<?php settings_fields('baweic_add_code'); ?>
				<?php do_settings_sections('baweic_add_code'); ?>
				<?php submit_button(__('Add new code', 'baweic')); ?>
			</form>
		</div>
	<?php
}

function baweic_rand_code() {
	settings_errors();
	add_settings_section('baweic_rand_code', __('Add auto generated codes', 'baweic'), '__return_false', 'baweic_rand_code');
	add_settings_field('baweic_field_prefix', __('Code prefix', 'baweic'), 'baweic_field_prefix', 'baweic_rand_code', 'baweic_rand_code');
	add_settings_field('baweic_field_length', __('Length', 'baweic'), 'baweic_field_length', 'baweic_rand_code', 'baweic_rand_code');
	add_settings_field('baweic_field_howmany', __('How many codes', 'baweic'), 'baweic_field_howmany', 'baweic_rand_code', 'baweic_rand_code');
	add_settings_field('baweic_field_count', __('Max count', 'baweic'), 'baweic_field_count', 'baweic_rand_code', 'baweic_rand_code');

	?>
		<div class="wrap">
			<h1><?php _e('Invitation Codes, generate some!', 'baweic'); ?>
				<a class="add-new-h2" href="<?php echo admin_url('admin.php?page=baweic_list_codes'); ?>"><?php _e('Codes list', 'baweic') ;?></a>
				<a class="add-new-h2" href="<?php echo admin_url('admin.php?page=baweic_add_code'); ?>"><?php _e('Add new code', 'baweic') ;?></a>
			</h1>

			<form action="options.php" method="post">
				<?php settings_fields('baweic_rand_code'); ?>
				<?php do_settings_sections('baweic_rand_code'); ?>
				<?php submit_button(__('Generate codes', 'baweic')); ?>
			</form>
		</div>
	<?php
}

function baweic_fields_cb2($val) {
	$baweic_options = get_option('baweic_options');

	$prefix = trim($val);
	$count = isset($_POST['baweic_field_count']) ? (int) $_POST['baweic_field_count'] : 1;
	$length = isset($_POST['baweic_field_length']) ? (int) $_POST['baweic_field_length'] : 8;
	$howmany = isset($_POST['baweic_field_howmany']) ? (int) $_POST['baweic_field_howmany'] : 5;
	if ($count < 1) {
		add_settings_error('baweic', '', __('How many time this code can be used?', 'baweic') . sprintf(__(' (Minimum %d)', 'baweic'), 1), 'error');
	} elseif($length<4 || $length>16) {
		add_settings_error('baweic', '', __('Incorrect length.', 'baweic') . sprintf(__(' (Minimum %d)', 'baweic'), 4) . sprintf(__(' (Maximum %d)', 'baweic'), 16), 'error');
	} elseif($howmany<1) {
		add_settings_error('baweic', '', __('How many codes do you need?', 'baweic') . sprintf(__(' (Minimum %d)', 'baweic'), 1), 'error');
	} else {
		$temp = array();
		$i = 1;
		while ($i <= $howmany) {
			$temp = strtoupper($prefix . wp_generate_password($length, false));
			if (!in_array($temp, $baweic_options['codes'])) {
				++$i;
				$baweic_options['codes'][ $temp ] = array('maxcount' => $count, 'leftcount' => $count, 'users' => []);
			}
		}
		add_settings_error('baweic', '', sprintf(__('%d code(s) have been added. <a href="%s">Check the codes list &raquo;</a>', 'baweic'), $howmany, admin_url('admin.php?page=baweic_list_codes')), 'updated');
		update_option('baweic_options', $baweic_options);
	}

	return false;
}

function baweic_fields_cb($val) {
	$baweic_options = get_option('baweic_options');

	$code = trim(strtoupper(sanitize_key($val)));
	$count = isset($_POST['baweic_field_count']) ? (int)$_POST['baweic_field_count'] : 1;
	if(isset($baweic_options['codes'][$code])):
		add_settings_error('baweic', '', sprintf(__('The code <i>%s</i> already exists. Please choose another one.', 'baweic'), esc_html($code)), 'error');
	elseif($count<1):
		add_settings_error('baweic', '', __('How many time this code can be used?', 'baweic') . sprintf(__(' (Minimum %d)', 'baweic'), 1), 'error');
	elseif($code==''):
		add_settings_error('baweic', '', __('Empty code ...', 'baweic'), 'error');
	else:
		add_settings_error('baweic', '', sprintf(__('The code <i>%s</i> have been added. <a href="%s">Check the codes list &raquo;</a>', 'baweic'), esc_html($code), admin_url('admin.php?page=baweic_list_codes')), 'updated');
		create_invitation_code($code, $count);
	endif;
	return false;
}

function create_invitation_code($code, $count = 1) {
	$baweic_options = get_option('baweic_options');

	$count = (int) $count>0 ? $count : 1;
	$code = strtoupper(sanitize_key($code));
	if (isset($baweic_options['codes'][ $code ]) || !trim($code)) {
		return false;
	} else {
		$baweic_options['codes'][ $code ] = array('maxcount' => $count, 'leftcount' => $count, 'users' => []);
		update_option('baweic_options', $baweic_options);
		return true;
	}
}

register_activation_hook(BAWEIC__FILE__, 'baweic_activation');
function baweic_activation() {
	add_option('baweic_options', array('codes' => array('INVITATION' => array('maxcount' => 999999, 'leftcount' => 999999, 'users' => []))));
	add_option('baweic_fields', array('link' => 'on', 'text_link'=> sprintf(__('Need an invitation code? <a href="mailto:%s">Contact us!</a>', 'baweic'), get_option('admin_email'))));
}

register_uninstall_hook(BAWEIC__FILE__, 'baweic_uninstaller');
function baweic_uninstaller() {
	delete_option('baweic_options');
	delete_option('baweic_fields');
}

function baweic_list_codes() {
	$baweic_options = get_option('baweic_options');

	$admin_notices = array('updated' => array(), 'error' => array());
	if (isset($_GET['action'], $_GET['_wpnonce'])) { // do this in admin-post next time
		switch ($_GET['action']) {
			case 'delete':
				$code = isset($_GET['code']) ? strtoupper(sanitize_key($_GET['code'])) : false;
				if ($code && isset($baweic_options['codes'][ $code ]) && wp_verify_nonce($_GET['_wpnonce'], 'baweic-' . $_GET['action'] . '-' . $code)) {
					unset($baweic_options['codes'][ $code ]);
					update_option('baweic_options', $baweic_options);
					$admin_notices['updated'][] = sprintf(__('The code <b>%s</b> have been successfully deleted.', 'baweic'), esc_html($code));
				} else {
					$admin_notices['error'][] = sprintf(__('The code <b>%s</b> have not been deleted.', 'baweic'), esc_html($code));
				}
				break;
			case 'reset':
				if (wp_verify_nonce($_GET['_wpnonce'], 'baweic-' . $_GET['action'])) {
					$baweic_options['codes'] = array();
					update_option('baweic_options', $baweic_options);
					$admin_notices['updated'][] = __('All codes are gone, nobody can register now.', 'baweic');
				}
				break;
		}
	}
	// actions
	$counts['all'] = count($baweic_options['codes']);
	$counts['used'] = 0;
	$counts['not_used'] = 0;
	if($counts['all'] > 0)
		foreach ($baweic_options['codes'] as $c) {
			if (is_array($c['users']) && empty($c['users'])) {
				++$counts['not_used'];
			} else {
				++$counts['used'];
			}
		}
	?>
		<div class="wrap">
			<h1><?php _e('Invitation Codes List', 'baweic'); ?>
				<a class="add-new-h2" href="<?php echo admin_url('admin.php?page=baweic_add_code'); ?>"><?php _e('Add new code', 'baweic') ;?></a>
				<a class="add-new-h2" href="<?php echo admin_url('admin.php?page=baweic_rand_code'); ?>"><?php _e('Generate codes', 'baweic') ;?></a>
			<?php
			if (!empty($_GET['s'])) {
				printf('<span class="subtitle">' . __('Search results for &#8220;%s&#8221;', 'baweic') . '</span>', esc_html($_GET['s']));
			}
			?>
			</h1>
			<p>
			<?php
			foreach ($admin_notices['updated'] as $an) {
				echo '<div class="updated"><p>' . $an . '</p></div>';
			}
			foreach ($admin_notices['error'] as $an) {
				echo '<div class="error"><p>' . $an . '</p></div>';
			}
			unset($an);
			?>
			</p>
			<ul class="subsubsub">
				<li class=""><a class="<?php echo empty($_GET['status']) && empty($_GET['s']) ? 'current' : ''; ?>" href="<?php echo admin_url('admin.php?page=baweic_list_codes'); ?>"><?php _e('All', 'baweic'); ?> <span class="count">(<?php echo $counts['all']; ?>)</span></a> |</li>
				<li class=""><a class="<?php echo !empty($_GET['status']) && $_GET['status']=='used' ? 'current' : ''; ?>" href="<?php echo admin_url('admin.php?page=baweic_list_codes&status=used'); ?>"><?php _e('Used', 'baweic'); ?> <span class="count">(<?php echo $counts['used']; ?>)</span></a> |</li>
				<li class=""><a class="<?php echo !empty($_GET['status']) && $_GET['status']=='not_used'  ? 'current' : ''; ?>" href="<?php echo admin_url('admin.php?page=baweic_list_codes&status=not_used'); ?>"><?php _e('Not used', 'baweic'); ?> <span class="count">(<?php echo $counts['not_used']; ?>)</span></a></li>
			</ul>
			<form action="<?php echo admin_url('admin.php'); ?>">
			<p class="search-box">
				<label class="screen-reader-text" for="search-text"><?php _e('Search by keyword','baweic'); ?>:</label>
				<input type="search" id="search-text" name="s" value="<?php _admin_search_query(); ?>" />
				<input type="hidden" id="page" name="page" value="baweic_list_codes" />
				<?php submit_button(__('Search codes', 'baweic'), 'button', false, false, array('id' => 'search-submit')); ?>
			</p>
			</form>
			<table id="codes_table" class="widefat plugins datatables">
				<thead>
					<tr>
						<th scope="col" width="350"><?php _e('Code', 'baweic'); ?></th>
						<th scope="col" width="350"><?php _e('Counter', 'baweic'); ?></th>
						<th scope="col" width="350"><?php _e('User(s)', 'baweic'); ?></th>
						<th scope="col"><?php _e('Action', 'baweic'); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th scope="col"><?php _e('Code', 'baweic'); ?></th>
						<th scope="col"><?php _e('Counter', 'baweic'); ?></th>
						<th scope="col"><?php _e('User(s)', 'baweic'); ?></th>
						<th scope="col"><?php _e('Action', 'baweic'); ?></th>
					</tr>
				</tfoot>
				<tbody class="codes_table">
				<?php
				$empty = true;
				if (isset($baweic_options['codes']) && count($baweic_options['codes']) > 0) {
					foreach ($baweic_options['codes'] as $code=>$infos) {
						if (!empty($_GET['status']) && (('used' == $_GET['status'] && empty($infos['users'])) || ('not_used' == $_GET['status'] && $infos['users']))) {
							continue;
						}
						if (!empty($_GET['s']) && strstr($code, strtoupper($_GET['s'])) === false) {
							continue;
						}
						$empty = false;
					?>
						<tr class="token">
							<td>
								<div class="activation">
									<pre><b><?php echo esc_html($code); ?></b></pre>
								</div>
							</td>
							<td>
								<div class="activation">
									<?php echo '<b>' . $infos['leftcount'] . '</b> / ' . (int) $infos['maxcount']; ?>
								</div>
							</td>
							<td>
								<div class="activation">
									<?php echo !empty($infos['users']) ? implode(', ', array_map('esc_html', $infos['users'])) : '-'; ?>
								</div>
							</td>
							<td>
								<div class="activation">
									<span class="trash"><a href="<?php echo wp_nonce_url(admin_url('admin.php?page=baweic_list_codes&action=delete&code=' . esc_attr($code)), 'baweic-delete-' . $code); ?>"><?php _e('Delete', 'baweic'); ?></a></span>
								</div>
							</td>
						</tr>
					<?php
					}
				} else {
					echo '<tr><td colspan="4">' . sprintf(__('No codes yet, <a href="%s">add one</a>!', 'baweic'), admin_url('admin.php?page=baweic_add_code')) . '</td></tr>';
					$empty = false;
				}
				if ($empty) {
					echo '<tr><td colspan="4">' . sprintf(__('No codes yet, <a href="%s">add one</a>!', 'baweic'), admin_url('admin.php?page=baweic_add_code')) . '</td></tr>';
				}
				?>
				</tbody>
			</table>
			<p><a href="<?php echo wp_nonce_url(admin_url('admin.php?page=baweic_list_codes&action=reset'), 'baweic-reset'); ?>" class="button-secondary"><?php _e('Clear all codes', 'baweic'); ?></a></p>
		</div>
	<?php
}

add_action('admin_notices', 'baweic_admin_notice_noone');
function baweic_admin_notice_noone() {
	$baweic_options = get_option('baweic_options');

	$codes = $baweic_options['codes'];
	foreach ($codes as $code => $val)
		if (!$val['leftcount']) {
			unset($codes[$code]);
		}
	if (!count($codes)) {
		echo '<div class="error" id="message"><p>' . sprintf(__('Nobody can register because you did not set any invitation codes, <a href="%s">do it now</a>!', 'baweic'), admin_url('admin.php?page=baweic_add_code')) . '</p></div>';
	}
}
