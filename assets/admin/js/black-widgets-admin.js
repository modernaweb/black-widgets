jQuery(function ($) {
	'use strict';

	var $toggle = $('#bw_gsap_options');
	if (!$toggle.length) {
		return;
	}

	/**
	 * Show/enable CDN + GSAP-dependent settings only when JS → CDN is checked.
	 * Disabled fields are omitted from POST - PHP validate_options preserves prior values.
	 */
	function syncGsapChildren() {
		var on = $toggle.is(':checked');
		var $rows = $('tr.gsap-cdn, tr.gsap-dependent');

		$rows.toggle(on);
		$rows.find('input, select, textarea').prop('disabled', !on);

		if (on) {
			$rows.addClass('bw-gsap-child-visible');
		} else {
			$rows.removeClass('bw-gsap-child-visible');
		}
	}

	$toggle.on('change', syncGsapChildren);
	syncGsapChildren();
});
