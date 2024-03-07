import './bootstrap.js';
window.onload = init;
window.onresize = init;
var largeur = document.querySelector('#largeur');
var sensEcran = document.querySelector('#orientation');


function init() {
    largeur.textContent = window.innerWidth;
    if (window.innerHeight  > window.innerWidth) {
        sensEcran.textContent = "Portrait";
    } else {
        sensEcran.textContent = "Paysage";
    }
}
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/normalize.css';
import './styles/app.css';