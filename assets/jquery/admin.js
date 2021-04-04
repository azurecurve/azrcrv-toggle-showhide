/*
 * Tabs
 */
jQuery(document).ready(function($) {
	$('#azrcrv-tsh-tabs').tabs();

	//hover states on the static widgets
	$('#azrcrv-tsh-tabs, ul#icons li').hover(
		function() { $(this).addClass('ui-state-hover'); },
		function() { $(this).removeClass('ui-state-hover'); }
	);
});