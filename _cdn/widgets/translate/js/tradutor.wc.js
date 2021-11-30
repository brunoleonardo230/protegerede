function ChangeLang(a) {
	var b, elemento = "";
	if (document.createEvent) {
		var c = document.createEvent("HTMLEvents");
		c.initEvent("click", true, true)
	}
	if (a == 'pt') {
		elemento = $(".goog-te-banner-frame:eq(0)").contents().find("button[id*='restore']")
	} else {
		switch (a) {
		case 'en':
			b = "ngl";
			break;
		case 'es':
			b = "spanhol";
			break;
		case 'fr':
			b = "Francês";
			break;
		case 'it':
			b = "Italiano";
			break;		
		case 'de':
			b = "Alemão";
			break;
		}
		elemento = $(".goog-te-menu-frame:eq(0)").contents().find("span:contains('" + b + "')");
	}
	 	
	if (elemento.length > 0) {
		if (document.createEvent) {
			elemento[0].dispatchEvent(c)
		} else {
			elemento[0].click()
		}
	}
}

	$(".main-translate-wrap").on('click', function(){
		$(".main-translate-nav .idiomas").stop().fadeToggle();
	});

function googleTranslateElementInit() {
	new google.translate.TranslateElement({
		pageLanguage: 'pt',
		autoDisplay: false,
		includedLanguages: 'en,es,fr,it,de',
		layout: google.translate.TranslateElement.InlineLayout.SIMPLE
	},
	'google_translate_element');
}