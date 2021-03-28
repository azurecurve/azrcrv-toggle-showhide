/* toggle */
jQuery(document).ready(function(){
	jQuery(".azrcrv-tsh-toggle-container").hide();
	jQuery(".azrcrv-tsh-toggle").click(function(){
		jQuery(this).toggleClass("azrcrv-tsh-toggle-active").next().slideToggle('fast');
		return false;
	});
});
/* toggle already expanded */
jQuery(document).ready(function(){
	jQuery(".azrcrv-tsh-toggle-container-open").show();
	jQuery(".azrcrv-tsh-toggle-open-active").click(function(){
		jQuery(this).toggleClass("azrcrv-tsh-toggle-open").next().slideToggle('fast');
		return false;
	});
});


/* read more */
jQuery(document).ready(function(){
	jQuery(".azrcrv-tsh-readmore").hide();
	jQuery(".azrcrv-tsh-readmore-button").click(function(){
		jQuery(this).toggleClass("azrcrv-tsh-readmore-active").prev().slideToggle('medium');
		
        var buttontext = jQuery(this).text();

        if (buttontext === azrcrvtshvariables.readmore) {
            jQuery(this).text(azrcrvtshvariables.readless);
        } else {
            jQuery(this).text(azrcrvtshvariables.readmore);
        }
		
		return false;
	});
});