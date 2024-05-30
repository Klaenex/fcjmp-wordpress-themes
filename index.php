<?php get_header(); ?>
<main>
    <h2>Ensemble pour l'égalité des chances </h2>
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
    ?>
            <article>
                <h2><?php the_title(); ?></h2>
                <div><?php the_content(); ?></div>
            </article>
        <?php
        endwhile;
    else :
        ?>
        <p>Aucun contenu disponible</p>
    <?php
    endif;
    ?>
</main>
<?php get_footer(); ?>