<?php 
/*
Plugin Name: Doagency table of contents
Description: 
Version: 0.0.1
Author: Do Agency
Author URI: https://doagency.it
Plugin URI: https://doagency.it
License: Registrato - Non utilizzabile senza autorizzazione.
License URI: 
Text Domain: Do Agency
Domain Path: /languages
 */

 
 //Importa css per questa pagina backend!!!
function wpse_load_plugin_style() {
    $plugin_url = plugin_dir_url( __FILE__ );

    wp_enqueue_style( 'style', $plugin_url . '/style.css' );
	wp_enqueue_style('style');	
}
add_action( 'wp_enqueue_scripts', 'wpse_load_plugin_style' );
 
 
 
//Sicurezza
if(!defined('ABSPATH')) //Se non è definita la path assoluta
    die();


//Codice per admin area
//include ('admin-area-doa.php');

	

function wpb_hook_javascript() {
	
    ?>
        <script type="text/javascript">
             /*Cerco h dentro il post*/
			var elenco = jQuery('.post-content').find("h1, h2, h3, h4, h5, h6");
			//var elenco = elencoP.slice(1); //Tolgo il primo titolo che è titolo post - solo su pugs!!!!!!!!!!!!!!
			if (elenco.length > 0) {	

				//Indice: Leggo elenco di h e li mostro:
				jQuery('.indiceDoa').append('<div class="headingDoaToc">INDICE</div>');
				var contatoreParagraf = 0;  
				var contatoreSottoParagraf = 0;
				var numeroRiga;
				for( var i = 0; i < elenco.length; i++ ) {
					var numeroH = elenco[i].nodeName.split("H")[1];
					if ( numeroH > 2 ) {
						contatoreSottoParagraf +=1;
					} else {
						contatoreParagraf = contatoreParagraf + 1;
						contatoreSottoParagraf = 0;
					}
					
					//Pulisco stringa:
					var idNome = elenco[i].innerHTML;
					var idPulito = pulizia_stringhe(idNome);

					jQuery('.indiceDoa').append('<div class="riga doa-' + elenco[i].nodeName + '"><span class="numero">' + contatoreParagraf + '.' + contatoreSottoParagraf + '</span><span><a href="#' + idPulito + '">' + elenco[i].innerHTML + '</a></span></div>');
				};
				jQuery('.indiceDoa .riga').wrapAll('<div class="contenutoDoaToc"></div>');
				
				
				//Aggiungo la classe doa negli h nel testo:
				jQuery('h1, h2, h3, h4, h5, h6').addClass("doaLivelloH");
				//Prendo gli oggetti con classe .doaLivelloH (le voci dell'indice) e gli aggiungo l'id
				var contatoreParagraf = 0;   
				var contatoreSottoParagraf = 0;
				var primaVolta = 0;
				jQuery('.post-content .doaLivelloH').each(function(i, item) {
					/*if (primaVolta==0) {
						primaVolta=1;
						return;
					}*/
					var _item = jQuery(item);
					var idNome = _item.text();
					//Pulisco stringa:
					var idPulito = pulizia_stringhe(idNome);
					_item.attr('id', idPulito);
					
					//Aggiungo numeri ai titoli h: 
					var numeroH = item.nodeName.split("H")[1];
					if ( numeroH > 2 ) {
						contatoreSottoParagraf +=1;
					} else {
						contatoreParagraf = contatoreParagraf + 1;
						contatoreSottoParagraf = 0;
					}
					
					_item.wrapAll('<div class="titoloDoaToc"></div>');
					//_item.before('<p class="numeriTesto">' + contatoreParagraf + '.' + contatoreSottoParagraf + '</p>');
				});

				
				//Slow move by click on a:
				jQuery('.indiceDoa a').click(function(){
					var test = jQuery.attr(this, 'href');
					jQuery('html, body').animate({
						scrollTop: jQuery( jQuery.attr(this, 'href')  ).offset().top + (-220)
					}, 700);
					return false;
				});


			}
			
			
			//pulizia stringhe
			function pulizia_stringhe (inputString) {
				var idNome = inputString;
				idNome = idNome.replace('<span>','');
				idNome = idNome.replace('</span>','');
				idNome = idNome.replace('<strong>','');
				idNome = idNome.replace('</strong>','');
				idNome = idNome.replace('<em>','');
				idNome = idNome.replace('</em>','');
				idNome = idNome.replace('(','');
				idNome = idNome.replace(')','');
				idNome = idNome.replace('/','');
				idNome = idNome.replace('.','');
				idNome = idNome.replace(',','');
				idNome = idNome.replace(':','');
				idNome = idNome.replace('?','');
				idNome = idNome.replaceAll(' ', '');
				return idNome;
			}
			
			
			
        </script>
    <?php
}






//SHORTCODE
function doa_toc_shortcode() {
	echo "<div class='indiceDoa'></div>";
	add_action('wp_footer', 'wpb_hook_javascript');
}
add_shortcode('doa-toc', 'doa_toc_shortcode');




?>