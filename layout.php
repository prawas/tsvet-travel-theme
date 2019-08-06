<?php /* @var $content string */ ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php if (is_page('tez-order-tour')): ?>style="margin-top:0 !important;"<?php endif; ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php wp_head(); ?>
</head>
<body>
<div class="site-container">
    <?php if (is_page('tez-order-tour')): ?>
        <?= $content ?>
    <?php else: ?>
        <?php get_header(); ?>
        <?= $content ?>
        <?php get_footer(); ?>
    <?php endif; ?>
</div>
<?php if ( ! is_page('tez-order-tour')): ?>
<?php wp_footer(); ?>
<?php endif; ?>
</body>
</html>