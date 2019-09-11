<?php
/**
 * Template Name: Страница без конструктора
 */
?>

<section class="section section_gutenberg">
    <div class="section__container container">
    <?php the_post(); ?>
    <h1><?php the_title(); ?></h1>
    <?php the_content(); ?>
    </div>
</section>