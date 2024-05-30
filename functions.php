<?php
// Inclure les styles et scripts nécessaires
function mon_theme_enqueue_styles()
{
    wp_enqueue_style('main-style', get_template_directory_uri() . '/main.css');
}
add_action('wp_enqueue_scripts', 'mon_theme_enqueue_styles');

// Enregistrer le menu de navigation
function custom_nav_menu()
{
    register_nav_menu('fcjmp_custom', 'FCJMP Custom Menu');
}
add_action('init', 'custom_nav_menu');

// Inclure les fonctions externes pour l'API de Monday.com
require get_template_directory() . '/functions/api-monday.php';
