jQuery(document).ready(function(){
	jQuery(".azrcrv-tsh-toggle-container").hide();
	jQuery(".azrcrv-tsh-toggle").click(function(){
		jQuery(this).toggleClass("azrcrv-tsh-toggle-active").next().slideToggle('fast');
		return false;
	});
});
jQuery(document).ready(function(){
	jQuery(".azrcrv-tsh-toggle-container-open").show();
	jQuery(".azrcrv-tsh-toggle-open-active").click(function(){
		jQuery(this).toggleClass("azrcrv-tsh-toggle-open").next().slideToggle('fast');
		return false;
	});
});