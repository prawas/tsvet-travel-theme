<?php /* @var $content string */ ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php if (is_page('tez-order-tour')): ?>style="margin-top:0 !important;"<?php endif; ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="yandex-verification" content="9d5a53f5b54f8e06" />

    <?php wp_head(); ?>
</head>
<body>

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(55831912, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/55831912" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

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