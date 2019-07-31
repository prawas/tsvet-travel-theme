<?php /* @var $content string */ ?>
<?php //$css = []; for($i = 1; $i < 33; $i++) {
	//if(file_exists(__DIR__ . "/css/SearchForm-{$i}.css")) {
	//	$css[] = file_get_contents(__DIR__ . "/css/SearchForm-{$i}.css");
	//}
	//file_put_contents(__DIR__ . "/css/SearchForm.css", implode("\n", $css));
//} ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php wp_head(); ?>
</head>
<body>
<div class="site-container">
    <?php get_header(); ?>

    <?= $content ?>

    <?php get_footer(); ?>
</div>
<?php wp_footer(); ?>
</body>
</html>