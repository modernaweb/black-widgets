<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

echo '<div id="bw-wrap">';
	settings_errors();
	echo '<div class="icon32" id="icon-options-general"></div>';
	echo '<h1>' . esc_html__( 'Black Widgets Settings', 'blackwidgets' ) . '</h1>';
	echo '<hr />';
	
	echo '<form action="options.php" method="post">';

		settings_fields('plugin_options');

		do_settings_sections('black_widgets_settings_general_settings');
		?>
		<p class="submit">
			<input name="Submit" type="submit" class="bw-backend-btn button-primary" value="<?php esc_attr_e('Save Changes' ,'blackwidgets'); ?>" />
		</p>
		<?php
	echo '</form>';
echo '</div>';
