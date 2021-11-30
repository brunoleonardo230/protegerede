<?php
    echo "<link rel='stylesheet' href='" . BASE . "/_cdn/widgets/translate/css/translate.wc.css'/>";
?>
<!-- Botões Com Bandeiras -->
<div class="main_nav_social_ main-translate-nav">
	<span class="main-translate-wrap">Idiomas</span>
	<div class="idiomas flex align-items-center">
	    <a href="javascript:void(0);" class="link pt" onclick="ChangeLang('pt')"><img class="image" src="<?=BASE?>/_cdn/widgets/translate/images/brazil.png" title="Português" alt="Português" /></a>
	    <a href="javascript:void(0);" class="link en" onclick="ChangeLang('en')"><img class="image" src="<?=BASE?>/_cdn/widgets/translate/images/united-states.png" title="Inglês" alt="Inglês" /></a>
	    <a href="javascript:void(0);" class="link es" onclick="ChangeLang('es')"><img class="image" src="<?=BASE?>/_cdn/widgets/translate/images/spain.png" title="Espanhol" alt="Espanhol" /></a>
	    <a href="javascript:void(0);" class="link fr" onclick="ChangeLang('fr')"><img class="image" src="<?=BASE?>/_cdn/widgets/translate/images/france.png" title="Francês" alt="Francês" /></a> 
	    <a href="javascript:void(0);" class="link it" onclick="ChangeLang('it')"><img class="image" src="<?=BASE?>/_cdn/widgets/translate/images/italy.png" title="Italiano" alt="Italiano" /></a>
	    <a href="javascript:void(0);" class="link de" onclick="ChangeLang('de')"><img class="image" src="<?=BASE?>/_cdn/widgets/translate/images/germany.png" title="Alemão" alt="Alemão" /></a> 
	</div>
</div> 

<div id="google_translate_element"></div> <!-- Div Para Google -->
<?php
    echo "<script src='" . BASE . "/_cdn/widgets/translate/js/jquery-3.1.0.min.js'></script>"; //Inclui a .js com as funções de tradução
    echo "<script src='" . BASE . "/_cdn/widgets/translate/js/tradutor.wc.js'></script>"; //Inclui a .js com as funções de tradução
    echo "<script src='https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit'></script>"; 
?>
