/*
 * Tabs
 */
jQuery(document).ready(function() {
	
	jQuery('.azrcrv-tsh-nav-wrapper .azrcrv-tsh-nav-tab').on('click',function(event) {
		var item_to_show = '.azrcrv-tsh-tab' + jQuery(this).data('item');

		jQuery(this).siblings().removeClass('azrcrv-tsh-nav-tab-active');
		jQuery(this).addClass("azrcrv-tsh-nav-tab-active");
		
		jQuery(item_to_show).siblings().css('display','none');
		jQuery(item_to_show).css('display','block');
	});
	
});