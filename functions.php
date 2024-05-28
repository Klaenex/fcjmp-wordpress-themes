<?php
function mon_theme_enqueue_styles()
{
    wp_enqueue_style('main-style', get_template_directory_uri() . '/main.css');
}
add_action('wp_enqueue_scripts', 'mon_theme_enqueue_styles');
