<?php
defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

function baweic_field_link() {
	$baweic_fields = get_option( 'baweic_fields' );
?>
	<label><input type="checkbox" name="baweic_fields[link]" <?php isset($baweic_fields['link'])? checked( $baweic_fields['link'], 'on' ):'' ?>/> <em><?php _e( 'Do you want an extra text/link on registration form?', 'baweic' ); ?></em></label>
<?php
}

function baweic_field_text_link() {
	$baweic_fields = get_option( 'baweic_fields' );
?>
	<label><input type="text" size="60" name="baweic_fields[text_link]" value="<?php echo !empty( $baweic_fields['text_link'] ) ? esc_attr( $baweic_fields['text_link'] ) : ''; ?>"/> <em><?php _e( 'You can use HTML tags to create links for example.', 'baweic' ); ?></em></label>
<?php
}

function baweic_field_count() {
?>
	<input type="number" size="3" min="1" name="baweic_field_count" value="1" /> <em><?php _e( 'How many time this code can be used?', 'baweic' ); ?></em>
<?php
}

function baweic_field_length() {
?>
	<input type="number" size="10" min="4" max="16" name="baweic_field_length" value="8" /> <em><?php _e( 'Length of generated codes (Min. 4, Max. 16)', 'baweic' ); ?></em>
<?php
}

function baweic_field_howmany() {
?>
	<input type="number" size="3" min="1" max="10" name="baweic_field_howmany" value="5" /> <em><?php _e( 'How many codes do you need?', 'baweic' ); ?></em>
<?php
}

function baweic_field_code() {
?>
	<input type="text" name="baweic_field_code" size="40" value="" style="text-transform: uppercase;" /> <em><?php _e( 'Avoid bad chars, use A-Z and 0-9.', 'baweic' ); ?></em>
<?php
}

function baweic_field_prefix() {
?>
	<input type="text" name="baweic_field_prefix" size="10" value="" style="text-transform: uppercase;" /> <em><?php _e( 'All generated codes will start with this.', 'baweic' ); ?></em>
<?php
}
