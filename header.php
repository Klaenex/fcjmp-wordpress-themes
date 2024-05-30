<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo('name'); ?></title>
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
    <?php wp_head(); ?>
</head>

<body <?php body_class();
        ?>>
    <header>
        <nav class="main-navigation">
            <h1 class="site-title"><?php bloginfo('name');
                                    ?></h1>

            <?php
            wp_nav_menu(array(
                'theme_location' => 'fcjmp_custom',
                'container' => 'nav',
                'container_class' => 'main-menu',
                'menu_class' => 'menu',
                'fallback_cb' => false,
            ));

            ?>
        </nav>
    </header>

    <main>