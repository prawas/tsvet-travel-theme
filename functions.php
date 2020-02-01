<?php
//remove_filter('show_admin_bar', 'wc_disable_admin_bar', 10);
//add_filter('show_admin_bar', '__return_false');

require __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/inc/disabled-rest-api.php';
require __DIR__ . '/inc/template-functions.php';
require __DIR__ . '/inc/override-functions.php';
require __DIR__ . '/inc/customizer.php';

require __DIR__ . '/inc/class/simple_html_dom.php';

use Sendpulse\RestApi\ApiClient;
use Sendpulse\RestApi\Storage\FileStorage;

define('SUFFIX', SCRIPT_DEBUG ? '' : '.min');

define('API_USER_ID', 'ee70451c8fee76d0e0cdadab70aa5a27');
define('API_SECRET', '5bbe1d01643c15c10a14fde095035a95');
define('SP_BOOK_ID', 88982519);
define('PATH_TO_ATTACH_FILE', __FILE__);

/**
 * Устанавливает тему по умолчанию и регистрирует поддержку различных функций WordPress.
 *
 * Обратите внимание, что эта функция навешиваются на хук after_setup_theme,
 * который запускакется до хука init. Init хук запускается слишком поздно для
 * некоторых функций, таких как add_theme_support
 */
function tsvet_setup()
{
	// Разрешить WordPress управлять заголовком документа.
	add_theme_support('title-tag');

	// Включить поддержку custom лого
	add_theme_support('custom-logo');

	// Включить поддержку фонового изображения
	add_theme_support('custom-background', [
		'default-color' => '#000',
//        'default-image' => get_template_directory_uri() . '/images/wallpaper.jpg',
		'wp-head-callback' => '__return_false'
	]);

	register_nav_menus([
		'general' => __('General menu', 'tsvet'),
		'top-left' => __('Top left menu', 'tsvet'),
		'top-right' => __('Top right menu', 'tsvet'),
		'footer' => __('Footer menu', 'tsvet'),
		'legal-information' => __('Legal information', 'tsvet')
	]);

	// Indicate widget sidebars can use selective refresh in the Customizer.
	add_theme_support('customize-selective-refresh-widgets');

	add_theme_support('post-thumbnails');
	add_image_size('post-thumbnail', 970, 345, true);
	add_image_size('shop_catalog', 370, 180, true);

	add_theme_support('menus');

	add_theme_support('woocommerce');
	add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'tsvet_setup');

function tsvet_wp_get_attachment_metadata($data)
{
	if (isset($data['sizes']['shop_catalog'])) {
		$data['sizes']['shop_catalog']['width'] = 370;
	}
	return $data;
}
add_filter('wp_get_attachment_metadata', 'tsvet_wp_get_attachment_metadata');

/**
 * Регистрация виджетов
 */
function tsvet_widgets_init()
{
	register_sidebar([
		'name' => __('Exchange rates', 'tsvet'),
		'id' => 'exchange-rates',
		'before_widget' => '<div class="exchange-rates">',
		'after_widget' => '</div>',
		'before_title' => '',
		'after_title' => ''
	]);
	register_sidebar([
		'name' => __('Exchange left', 'tsvet'),
		'id' => 'footer-left',
		'before_widget' => '<div class="footer-left-menu">',
		'after_widget' => '</div>',
		'before_title' => '<div class="footer-title-menu">',
		'after_title' => '</div>'
	]);
	register_sidebar([
		'name' => __('Exchange right', 'tsvet'),
		'id' => 'footer-right',
		'before_widget' => '<div class="footer-right-menu">',
		'after_widget' => '</div>',
		'before_title' => '',
		'after_title' => ''
	]);
}
add_action('widgets_init', 'tsvet_widgets_init');

/**
 * Подключаем скрипты и стили
 */
function tsvet_scripts()
{
	init_bootstrap();

	wp_enqueue_style('tsvet-style', get_stylesheet_uri(), array_filter([
		'bxslider',
		'select2',
		'datepicker',
		'datepicker.standalone',
		'woocommerce-general',
		'archive-product'
	], function ($style) {
		return wp_style_is($style, 'registered');
	}), null);

	wp_deregister_script('jquery');
	wp_deregister_script('jquery-migrate');

	// wp_enqueue_script('ga', "https://www.googletagmanager.com/gtag/js?id=UA-119417579-1", [], null);
	// wp_add_inline_script('ga', "window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'UA-119417579-1');");

	wp_enqueue_script('jquery', get_template_directory_uri() . "/js/jquery" . SUFFIX . ".js", [], '3.2.1', true);
	wp_add_inline_script('jquery', 'jQuery.migrateMute = true');
	wp_enqueue_script('jquery-migrate', get_template_directory_uri() . "/js/jquery-migrate" . SUFFIX . ".js", ['jquery'], '1.4.1', true);

	$deps_scripts = [
		'jquery-migrate',
		'bxslider',
		'select2',
		'datepicker',
		'archive-product'
	];
	if (class_exists('ALM_SHORTCODE') && ALM_SHORTCODE::$counter > 0) {
		$deps_scripts[] = 'ajax-load-more';
		$deps_scripts[] = 'ajax-load-more-progress';
	}
	wp_enqueue_script('tsvet-script', get_template_directory_uri() . "/app.js", array_filter($deps_scripts, function ($script) {
		return wp_script_is($script, 'registered');
	}), null, true);
}
add_action('wp_enqueue_scripts', 'tsvet_scripts', 11);

function tsvet_init_poll() {
	$PATH = get_template_directory() . "/poll/build/";
	$URI = get_template_directory_uri() . "/poll/build/";
	$ASSETS_JSON = file_get_contents($PATH . "asset-manifest.json");
	$ASSETS = json_decode($ASSETS_JSON, true);
	$FILES = $ASSETS["files"];

	wp_enqueue_style("main.css", $URI . $FILES["main.css"]);

	wp_enqueue_script("main.js", $URI . $FILES["main.js"]);

	foreach ($FILES as $file => $hashed) {
		$js = substr( $file, -strlen( ".js" ) ) === ".js";
		$chunk2 = strpos($file, "static/js/2.") !== FALSE;
		if ($js && $chunk2) {
			wp_enqueue_script($file, $URI . $hashed);
		}
	}
}
add_action('wp_enqueue_scripts', 'tsvet_init_poll', 12);

function tsvet_alm_js_dependencies()
{
	return ['jquery-migrate'];
}
add_filter('alm_js_dependencies', 'tsvet_alm_js_dependencies', 12);

function init_bxslider()
{
	wp_enqueue_style('bxslider', get_template_directory_uri() . "/plugins/bxslider/jquery.bxslider" . SUFFIX . ".css", [], '4.2.12');
	wp_enqueue_script('bxslider', get_template_directory_uri() . "/plugins/bxslider/jquery.bxslider" . SUFFIX . ".js", ['jquery-migrate'], '4.2.12', true);
	wp_add_inline_script('bxslider', "$('.slider').bxSlider({pager: false, oneToOneTouch: false, touchEnabled: false})");
}

// [slider label="slider"]$content[/slider]
function slider_shortcode($atts, $content)
{
	$category = shortcode_atts(['label' => ''], $atts);
	$category = get_category_by_slug($category['label']);

	add_action('wp_enqueue_scripts', 'init_bxslider');

	return tsvet_render_file("template-parts/slider", null, [
		'category' => $category,
		'slider_posts' => get_posts([
			'category__in' => $category->cat_ID
		]),
		'slider_content' => do_shortcode($content)
	]) ? : '';
}
add_shortcode('slider', 'slider_shortcode');

function init_form()
{
	// init_bootstrap();
	init_datepicker();

	wp_enqueue_script('jScrollPane', get_template_directory_uri() . "/plugins/jScrollPane/jquery.jscrollpane" . SUFFIX . ".js", ['jquery-migrate'], '2.0.23', true);
}

function init_bootstrap()
{
	wp_enqueue_style('bootstrap', get_template_directory_uri() . "/bootstrap/dist/css/bootstrap.css", [], '3.3.7');
	wp_enqueue_style('bootstrap-theme', get_template_directory_uri() . "/bootstrap/dist/css/bootstrap-theme.css", ['bootstrap'], '3.3.7');
	wp_enqueue_script('bootstrap', get_template_directory_uri() . "/bootstrap/dist/js/bootstrap.js", ['jquery-migrate'], '3.3.7');
}

function init_datepicker()
{
	wp_enqueue_style('datepicker', get_template_directory_uri() . "/plugins/bootstrap-datepicker/css/bootstrap-datepicker" . SUFFIX . ".css", ['bootstrap'], '1.6.4');
	wp_enqueue_style('datepicker.standalone', get_template_directory_uri() . "/plugins/bootstrap-datepicker/css/bootstrap-datepicker.standalone" . SUFFIX . ".css", ['datepicker'], '1.6.4');
	wp_enqueue_script('datepicker', get_template_directory_uri() . "/plugins/bootstrap-datepicker/js/bootstrap-datepicker" . SUFFIX . ".js", ['bootstrap'], '1.6.4', true);
	wp_enqueue_script('datepicker-locale', get_template_directory_uri() . "/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ru.min.js", ['bootstrap', 'datepicker'], '1.6.4', true);
	wp_add_inline_script('datepicker-locale', "$('input.datepicker').datepicker({format: 'dd/mm/yyyy', language: 'ru'})");
	wp_enqueue_script('stackblur', get_template_directory_uri() . "/plugins/StackBlur/stackblur" . SUFFIX . ".js", ['bootstrap'], '0.5', true);
}

function init_searchform()
{
	wp_deregister_script('select2');

	wp_enqueue_style('select2', get_template_directory_uri() . "/plugins/select2/dist/css/select2" . SUFFIX . ".css", [], '4.0.3');
	wp_enqueue_script('select2', get_template_directory_uri() . "/plugins/select2/dist/js/select2" . SUFFIX . ".js", ['jquery-migrate'], '4.0.3', true);
	wp_add_inline_script('select2', "$('select').select2()");

	init_form();
}

function searchform_shortcode($atts, $content)
{
	global $product_search_form_index;
	init_searchform();

	if (empty($product_search_form_index)) {
		$product_search_form_index = 0;
	}
	return tsvet_render_file("woocommerce/product-searchform", null, [
		'countries' => [],
		'cities' => [],
		'index' => $product_search_form_index++,
		'content' => do_shortcode($content)
	]) ? : '';
}
add_shortcode('searchform', 'searchform_shortcode');

// [post title="title_post"]
function post_shortcode($atts)
{
	$post = shortcode_atts(['title' => ''], $atts);

	$page = get_page_by_title($post['title'], OBJECT, 'post');

	return $page->post_content ? : '';
}
add_shortcode('post', 'post_shortcode');

function category_shortcode($atts)
{
	$atts = shortcode_atts([
		'slug' => '',
		'post_template' => '',
		'number_posts' => 4,
		'order' => 'DESC'
	], $atts);
	$category = get_category_by_slug($atts['slug']);
	$params = [
		'posts_category' => get_posts([
			'category' => $category->cat_ID,
			'numberposts' => $atts['number_posts'],
			'order' => $atts['order']
		]),
	];
	if ($atts['post_template']) {
		return tsvet_render_file("template-parts/posts_category", $atts['post_template'], $params);
	}

	return tsvet_render_file("template-parts/posts_category", null, $params);
}
add_shortcode('category', 'category_shortcode');

function logo_shortcode($atts)
{
	$atts = shortcode_atts([
		'name' => '',
		'class' => '',
		'viewbox' => ''
	], $atts);
	if ($atts['name']) {
		$name = $atts['name'];
		unset($atts['name']);
		return tsvet_render_file("template-parts/logos/logo", $name, ['attributes' => $atts]);
	}

	return "<svg class=\"{$atts['class']}\" viewBox=\"{$atts['viewbox']}\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\">
    		<use href=\"#logo\"></use>
        </svg>";
}
add_shortcode('logo', 'logo_shortcode');

function init_vk_comments()
{
	wp_enqueue_script('vk_comments', "https://vk.com/js/api/openapi.js?154", [], null);
}
function vk_comments_shortcode($atts)
{
	add_action('wp_enqueue_scripts', 'init_vk_comments');
	return
		"<div id=\"vk_comments\"></div>" .
		"<script type=\"text/javascript\">
			window.onload = function () {
				$(window).scroll(function() {
					if ($(window).scrollTop() + $(window).height() >= $('#vk_comments').offset().top) {
						if(!$('#vk_comments').attr('loaded')) {
							$('#vk_comments').attr('loaded', true);               
							VK.init({apiId: 6481404, onlyWidgets: true});
							VK.Widgets.Comments('vk_comments', {
	            			    limit: 10, 
	            			    attach: 'photo', 
	            			    autoPublish: 1
 							});
						}
					}
				});	  
			}
        </script>";
}
add_shortcode('vk_comments', 'vk_comments_shortcode');

function gmaps_shortcode($atts)
{
	$atts = shortcode_atts([
		'lat' => '',
		'lng' => '',
		'lat1' => '',
		'lng1' => '',
		'zoom' => 15
	], $atts);
	if (!$atts['lat'] || !$atts['lng']) {
		return '';
	}

	wp_enqueue_script('gmaps', "https://maps.googleapis.com/maps/api/js?key=AIzaSyAzAvN4B9FEABFIHuC14r14IoF_OOqCPdA&callback=initMap", [], '', true);
	wp_add_inline_script('gmaps', "function initMap() {
		var myLatLng = {lat: {$atts['lat']}, lng: {$atts['lng']}};
		" . ((isset($atts['lat1']) && isset($atts['lng1'])) ? "var myLatLng1 = {lat: {$atts['lat1']}, lng: {$atts['lng1']}};" : "var myLatLng1 = null;")
		  . "
		var center = myLatLng1 ? {lat: 0.5 * (myLatLng.lat + myLatLng1.lat), lng: 0.5 * (myLatLng.lng + myLatLng1.lng)} : myLatLng;
		var map = new google.maps.Map(document.getElementById('map'), {
			zoom: {$atts['zoom']},
			center: center
		});
		var marker = new google.maps.Marker({
			position: myLatLng,
			map: map
		});
		if (myLatLng1) {
			var marker1 = new google.maps.Marker({
				position: myLatLng1,
				map: map
			});
		}
	}", 'before');
	return "<div id=\"map\"></div>";
}
add_shortcode('gmaps', 'gmaps_shortcode');

function get_item_array($array, $item_name, $default)
{
	if (!empty($array) && isset($array) && isset($array[$item_name]) && !empty($array[$item_name]) && $array[$item_name]) {
		return $array[$item_name];
	}
	return $default;
}

function tsvet_get_query_var($name, $default, $name_array = '')
{
	if ($name_array != '') {
		return get_item_array($name_array, $name, $default);
	}
	if ($value = get_item_array($_POST, $name, false)) {
		return $value;
	}
	if ($value = get_item_array($_GET, $name, false)) {
		return $value;
	}
	return $default;
}

function request_url($url, $data, $source = '')
{
	$response = tsvet_remote_get($url, [
		'body' => $data,
		'source' => $source
	]);
	return json_decode($response instanceof WP_Error ? "" : $response['body'], true);
}

function tsvet_get_filters_itaka()
{
	$data = [
		'language' => 'ru',
		'date-from' => tsvet_get_query_var('date-from', date('Y-m-d'))
	];

	// children-age - даты рождения детей, '2016-06-01,2012-06-26'
	// dest-region - Куда
	// dep-region - Вылет из, список городов (alanya-province,antalya-province,belek-province) или название страны (turcja)

	foreach (['date-to', 'package-type', 'adults', 'children-age', 'dest-region', 'dep-region'] as $filter_name) {
		if ($value = tsvet_get_query_var($filter_name, '')) {
			$data[$filter_name] = $value;
		}
	}
	$response = request_url('https://www.itaka.pl/sipl-v2/data/filters', $data, 'itaka');
	wp_send_json_success($response['data']);
}
//add_action('wp_ajax_get_filters_itaka', 'tsvet_get_filters_itaka');
//add_action('wp_ajax_nopriv_get_filters_itaka', 'tsvet_get_filters_itaka');

function tsvet_search_itaka()
{
	$data = [
		'language' => 'ru',
		'package-type' => 'wczasy',
		'date-from' => tsvet_get_query_var('date-from', date('Y-m-d')),
		'page' => tsvet_get_query_var('page', date('1'))
	];

	// children-age - даты рождения детей, '2016-06-01,2012-06-26'
	// dest-region - Куда, список городов (alanya-province,antalya-province,belek-province) или название страны (turcja)
	// dep-region - Вылет из

	foreach (['date-to', 'adults', 'kids', 'children-age', 'dest-region', 'dep-region', 'country-to'] as $filter_name) {
		if ($value = tsvet_get_query_var($filter_name, false)) {
			$data[$filter_name] = $value;
		}
	}
	if (isset($data['country-to'])) {
		$data['dest-region'] .= $data['country-to'];
		unset($data['country-to']);
	}
	$response = request_url('https://www.itaka.pl/sipl-v2/data/holiday/search', $data, 'itaka');
	wp_send_json_success($response['data']);
}
add_action('wp_ajax_search_itaka', 'tsvet_search_itaka');
add_action('wp_ajax_nopriv_search_itaka', 'tsvet_search_itaka');


/**
 * @param $handle
 */
function tsvet_http_api_curl(&$handle, $attr)
{
	$user_agent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36';
	if (isset($attr['source'])) {
		$referers = [
			'itaka' => 'https://www.itaka.pl/wyniki-wyszukiwania/wczasy',
			'sletat' => 'https://sletat.ru'
		];
		$referer = isset($referers[$attr['source']]) ? $referers[$attr['source']] : get_home_url();
		curl_setopt($handle, CURLOPT_REFERER, $referer);
	} else {
		curl_setopt($handle, CURLOPT_REFERER, get_home_url());
	}
	curl_setopt($handle, CURLOPT_USERAGENT, $user_agent);
}
add_action('http_api_curl', 'tsvet_http_api_curl', 10, 2);

function tsvet_mime_types($mimes)
{
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter('upload_mimes', 'tsvet_mime_types');

function tsvet_get_attachment_image_attributes($attr)
{
	if (isset($attr['class']) && strpos($attr['class'], 'attachment-shop_catalog') !== false) {
		$attr['class'] = "product__thumbnail";
	}
	return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'tsvet_get_attachment_image_attributes');

function tsvet_woocommerce_currency_symbols($symbols)
{
	$symbols['BYR'] = 'руб';
	return $symbols;
}
add_filter('woocommerce_currency_symbols', 'tsvet_woocommerce_currency_symbols');

function tsvet_formatted_woocommerce_price($price)
{
	return str_replace(".00", "", $price);
}
add_filter('formatted_woocommerce_price', 'tsvet_formatted_woocommerce_price');

function tsvet_woocommerce_shortcode_products_query($query_args)
{
	global $wp_query;
	$_wp_query = clone $wp_query;
	$query_args['paged'] = isset($_POST['page']) && is_numeric($_POST['page']) ? $_POST['page'] : 1;
	$GLOBALS['post_last'] = get_post();
	$posts = $_wp_query->query([
		'post_type' => 'product',
		'name' => 'temp'
	]);
	if (count($posts) > 0) {
		$query_args['post__not_in'] = array_map(function ($post) {
			return $post->ID;
		}, $posts);
	}
	return $query_args;
}
add_filter('woocommerce_shortcode_products_query', 'tsvet_woocommerce_shortcode_products_query');
function tsvet_woocommerce_shortcode_after_recent_products_loop()
{
	global $wp_query;
	if (isset($GLOBALS['post_last'])) {
		$wp_query->post = $GLOBALS['post_last'];
	}
}
add_filter('woocommerce_shortcode_after_recent_products_loop', 'tsvet_woocommerce_shortcode_after_recent_products_loop');

function tsvet_woocommerce_template_loader_files($atts, $default_file)
{
	if (in_array($default_file, ["archive-product.php", "single-product.php"])) {
		init_form();
		wp_enqueue_script('underscore', get_template_directory_uri() . '/plugins/underscore/underscore' . SUFFIX . '.js', [], "1.8.3", true);
		wp_enqueue_style("archive-product", get_template_directory_uri() . '/css/SearchForm' . SUFFIX . '.css', ['datepicker', 'datepicker.standalone'], null);
		wp_enqueue_script('archive-product', get_template_directory_uri() . '/js/SearchForm' . SUFFIX . '.js', ['underscore', 'datepicker', 'jScrollPane'], null, true);
	}
}
add_filter('woocommerce_template_loader_files', 'tsvet_woocommerce_template_loader_files', 10, 2);

function tsvet_rewrite_rules_array($rules)
{
	return array_merge([
		'product/(itaka|sletat)/(.*)/?$' => 'index.php?category_name=$matches[1]&product=$matches[2]',
		'country/([0-9]{1,2})/?$' => 'index.php?tax=pa_country&terms=$matches[1]',
		'country/([0-9]{1,2})/page/([0-9]{1,2})/?$' => 'index.php?tax=pa_country&terms=$matches[1]&paged=$matches[2]',

		'(tourType/1|tury/pljazhnye-tury)/?$' => 'index.php?product_cat=пляжный-отдых',
		'(tourType/2|tury/ekskursionnye)/?$' => 'index.php?product_cat=экскурсионные-туры',
		'(tourType/3|tury/shopping)/?$' => 'index.php?product_cat=шоппинг-туры',
		'(tourType/4|tury/gornolyzhnye)/?$' => 'index.php?product_cat=горнолыжные-туры',
		'(tourType/5|tury/iz-polshi-itaka)/?$' => 'index.php?product_cat=туры-из-польши-от-itaka',
		'(tourType/11|tury/morskie-kruizy)/?$' => 'index.php?product_cat=морские-круизы',
		'(tourType/20|transfer-iz-bresta-v-evropu)/?$' => 'index.php?product_cat=трансфер-из-бреста-в-европу',
		'(tourType/22|tury/ekskursionnye-tury/kaliningrad)/?$' => 'index.php?product_cat=экскурсионный-тур-в-калининград',
		'(tourType/23|agentskij-otdel)/?$' => 'index.php?name=агентский-отдел',
		'(tourType/14|tury/ekskursionnye/belarus)/?$' => 'index.php?product_cat=экскурсии-по-беларуси',

		'(tourType/1|tury/pljazhnye-tury)/page/([0-9]{1,2})/?$' => 'index.php?product_cat=пляжный-отдых&paged=$matches[2]',
		'(tourType/2|tury/ekskursionnye)/page/([0-9]{1,2})/?$' => 'index.php?product_cat=экскурсионные-туры&paged=$matches[2]',
		'(tourType/3|tury/shopping)/page/([0-9]{1,2})/?$' => 'index.php?product_cat=шоппинг-туры&paged=$matches[2]',
		'(tourType/4|tury/gornolyzhnye)/page/([0-9]{1,2})/?$' => 'index.php?product_cat=горнолыжные-туры&paged=$matches[2]',
		'(tourType/5|tury/iz-polshi-itaka)/page/([0-9]{1,2})/?$' => 'index.php?product_cat=туры-из-польши-от-itaka&paged=$matches[2]',
		'(tourType/11|tury/morskie-kruizy)/page/([0-9]{1,2})/?$' => 'index.php?product_cat=морские-круизы&paged=$matches[2]',
		'(tourType/20|transfer-iz-bresta-v-evropu)/page/([0-9]{1,2})/?$' => 'index.php?product_cat=трансфер-из-бреста-в-европу&paged=$matches[2]',
		'(tourType/22|tury/ekskursionnye-tury/kaliningrad)/page/([0-9]{1,2})/?$' => 'index.php?product_cat=экскурсионный-тур-в-калининград&paged=$matches[2]',
		'(tourType/23|agentskij-otdel)/page/([0-9]{1,2})/?$' => 'index.php?name=агентский-отдел&paged=$matches[2]',
		'(tourType/14|tury/ekskursionnye/belarus)/page/([0-9]{1,2})/?$' => 'index.php?product_cat=экскурсии-по-беларуси&paged=$matches[2]',

		'(tour/146|tury/ekskursionnyj-tur-v-kaliningrad)/?$' => 'index.php?product=туры-в-калининград',
		'(tour/169|tury/andusalija-pljazhnyj-otdyh-itaka)/?$' => 'index.php?product=пляжный-отдых-от-итаки-в-андалусии',
		'(tour/23|tury/pljazhnye-tury-sharm-el-shejh-itaka)/?$' => 'index.php?product=пляжный-отдых-от-итаки-в-шарм-эль-шейхе',
		'(tour/27|tury/otdyh-v-chernogorii-itaka)/?$' => 'index.php?product=отдых-в-черногории-от-итака',
		'(tour/29|tury/otdyh-v-horvatii-itaka)/?$' => 'index.php?product=отдых-в-хорватии-от-итака',
		'(tour/32|tury/otdyh-v-dominikane-itaka)/?$' => 'index.php?product=отдых-в-доминикане-от-itaka',
		'(tour/60|tury/otdyh-na-madejre-itaka)/?$' => 'index.php?product=отдых-на-мадейре-от-itaka',
		'(tour/22|tury/otdyh-grecija-itaka)/?$' => 'index.php?product=отдых-в-греции-от-itaka',
		'(tour/24|tury/otdyh-turcija-itaka)/?$' => 'index.php?product=отдых-в-турции-от-itaka',
		'(tour/25|tury/otdyh-italija-itaka)/?$' => 'index.php?product=отдых-в-италии-от-itaka',
		'(tour/52|tury/otdyh-bolgarija-itaka)/?$' => 'index.php?product=отдых-в-болгарии-от-itaka',
		'(tour/134|tury/pljazhnyj-otdyh-krit)/?$' => 'index.php?product=пляжный-отдых-от-итаки-на-о-крит',
		'(tour/136|tury/pljazhnyj-otdyh-kofu-itaka)/?$' => 'index.php?product=пляжный-отдых-от-итаки-на-о-корфу',
		'(tour/137|tury/pljazhnyj-otdyh-kosta-dorada-itaka)/?$' => 'index.php?product=пляжный-отдых-от-итаки-на-коста-дорада',
		'(tour/138|tury/pljazhnyj-otdyh-zakinf-itaka)/?$' => 'index.php?product=пляжный-отдых-от-итаки-на-о-закинф',
		'(tour/152|tury/kanarskiee-ostrova-itaka)/?$' => 'index.php?product=канарские-острова-от-itaka',
		'(tour/168|tury/pljazhnyj-otdyh-itaka-majorka)/?$' => 'index.php?product=пляжный-отдых-от-итаки-на-майорке',
		'(tour/174|tury/pljazhnyj-otdyh-rodos-itaka)/?$' => 'index.php?product=пляжный-отдых-от-итаки-на-о-родос',

		"tury/ispanija/?$" => "index.php?tax=pa_country&terms=8",
		"tury/grecija/?$" => "index.php?tax=pa_country&terms=11",
		"tury/dominikana/?$" => "index.php?tax=pa_country&terms=17",
		"tury/italija/?$" => "index.php?tax=pa_country&terms=10",
		"tury/turcija/?$" => "index.php?tax=pa_country&terms=9",
		"tury/tailand/?$" => "index.php?tax=pa_country&terms=29",
		"tury/izrail/?$" => "index.php?tax=pa_country&terms=36",
		"tury/bolgarija/?$" => "index.php?tax=pa_country&terms=18",
		"tury/maldivy/?$" => "index.php?tax=pa_country&terms=55",
		"tury/kipr/?$" => "index.php?tax=pa_country&terms=14",
		"tury/marokko/?$" => "index.php?tax=pa_country&terms=12",
		"tury/francija/?$" => "index.php?tax=pa_country&terms=52",
		"tury/polsha/?$" => "index.php?tax=pa_country&terms=1",
		"tury/egipet/?$" => "index.php?tax=pa_country&terms=7",
		"tury/portugalija/?$" => "index.php?tax=pa_country&terms=16",
		"tury/germanija/?$" => "index.php?tax=pa_country&terms=47",
		"tury/kuba/?$" => "index.php?tax=pa_country&terms=45",
		"tury/rossija/?$" => "index.php?tax=pa_country&terms=53",
		"tury/kabo-verde/?$" => "index.php?tax=pa_country&terms=49",
		"tury/ispanija/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=8&paged=$matches[1]',
		"tury/grecija/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=11&paged=$matches[1]',
		"tury/dominikana/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=17&paged=$matches[1]',
		"tury/italija/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=10&paged=$matches[1]',
		"tury/turcija/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=9&paged=$matches[1]',
		"tury/tailand/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=29&paged=$matches[1]',
		"tury/izrail/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=36&paged=$matches[1]',
		"tury/bolgarija/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=18&paged=$matches[1]',
		"tury/maldivy/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=55&paged=$matches[1]',
		"tury/kipr/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=14&paged=$matches[1]',
		"tury/marokko/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=12&paged=$matches[1]',
		"tury/francija/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=52&paged=$matches[1]',
		"tury/polsha/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=1&paged=$matches[1]',
		"tury/egipet/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=7&paged=$matches[1]',
		"tury/portugalija/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=16&paged=$matches[1]',
		"tury/germanija/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=47&paged=$matches[1]',
		"tury/kuba/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=45&paged=$matches[1]',
		"tury/rossija/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=53&paged=$matches[1]',
		"tury/kabo-verde/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=49&paged=$matches[1]',
	], $rules);
}
add_filter("rewrite_rules_array", "tsvet_rewrite_rules_array");
function tsvet_redirect_canonical($redirect_url, $requested_url)
{
	if ($requested_url[strlen($requested_url) - 1] == '?') {
		return;
	}
	return $redirect_url;
}
add_filter("redirect_canonical", "tsvet_redirect_canonical", 10, 2);

function dq_override_post_title($title)
{
	if ($title['title'] == 'Туры' && (strpos($_SERVER['REQUEST_URI'], "/country") !== false
		|| strpos($_SERVER['REQUEST_URI'], "/tour") !== false
		|| strpos($_SERVER['REQUEST_URI'], "/tury") !== false)
		|| strpos($_SERVER['REQUEST_URI'], "/agentskij-otdel") !== false) {
		$title['title'] = woocommerce_page_title(false);
	}
	return $title;
}
add_filter('document_title_parts', 'dq_override_post_title');

function tsvet_query_vars($public_query_vars)
{
	$public_query_vars[] = "tax";
	$public_query_vars[] = "terms";
	return $public_query_vars;
}
add_filter("query_vars", "tsvet_query_vars");

function tsvet_request($query_vars)
{
	if (isset($query_vars['tax'])) {
		$query_vars['taxonomy'] = $query_vars["tax"];
		unset($query_vars["tax"]);
	}

	return $query_vars;
}
add_filter("request", "tsvet_request");

function node_has_class($node, $class)
{
	return (isset($node->attr) && isset($node->attr['class']) && strpos(' ' . $node->attr['class'] . ' ', ' ' . $class . ' ') !== false);
}

function tsvet_remote_get($request_url, $data)
{
	$number_request = 0;
	do {
		if ($number_request > 0) {
			usleep(rand(1, 3) * 100000);
		}
		$request = wp_remote_get($request_url, $data);
	} while (++$number_request < 5 && $request instanceof WP_Error);
	return $request;
}
function cache_post_meta($post_id, $key_meta, $value_meta)
{
	if (!isset($GLOBALS['posts_meta'])) {
		$GLOBALS['posts_meta'] = [];
	}
	if (!isset($GLOBALS['posts_meta'][$post_id])) {
		$GLOBALS['posts_meta'][$post_id] = [];
	}
	$GLOBALS['posts_meta'][$post_id][$key_meta] = $value_meta;
}

/**
 * @param $post_id
 * @param $key_meta
 * @param string|array|integer $default
 * @param bool $single
 *
 * @return string|array|integer
 */
function get_cache_post_meta($post_id, $key_meta, $default = '', $single = true)
{
	if (isset($GLOBALS['posts_meta']) && isset($GLOBALS['posts_meta'][$post_id]) && isset($GLOBALS['posts_meta'][$post_id][$key_meta])) {
		return $GLOBALS['posts_meta'][$post_id][$key_meta];
	}
	if ($result = get_post_meta($post_id, $key_meta, !is_array($default))) {
		return $result;
	}
	/* @var $terms WP_Term[] */
	$terms = wp_get_object_terms($post_id, 'pa_' . $key_meta);
	if ($terms && !($terms instanceof WP_Error) && count($terms) > 0) {
		$terms = array_map(function ($term) {
			return [
				'key' => urldecode($term->slug),
				'value' => urldecode($term->name)
			];
		}, $terms);
		return !is_array($default) ? (empty($terms) ? '' : $terms[0]['value']) : array_custom_map($terms, 'key', 'value');
	}
	return $default;
}

/**
 * @param $html simple_html_dom
 * @param $name
 *
 * @return array
 */
function get_options_select_by_name($html, $name)
{
	return array_custom_map(array_map(function ($option) {
		/* @var $option simple_html_dom_node */
		return [
			'key' => $option->getAttribute('value'),
			'value' => trim($option->innertext)
		];
	}, $html->find("select[name=\"{$name}\"] option")), 'key', 'value');
}

/**
 * @param $html simple_html_dom_node[]
 * @param $attribute
 * @param string $default
 *
 * @return string
 */
function get_attribute_html($html, $attribute, $default = '')
{
	if (count($html) > 0) {
		return $html[0]->getAttribute($attribute);
	}
	return $default;
}
/**
 * @param $html simple_html_dom_node[]
 * @param string $default
 *
 * @return string
 */
function get_innertext_html($html, $default = '')
{
	if (count($html) > 0) {
		return $html[0]->innertext;
	}
	return $default;
}

/**
 * @param $posts WP_Post[]
 *
 * @return array
 */
function tsvet_the_posts($posts)
{
	global $wp_query, $wp_object_cache;
	if (!$wp_query->post) {
		if (isset($wp_query->query_vars['category_name'])
			&& in_array($wp_query->query_vars['category_name'], ['itaka', 'sletat'])) {
			$category_name = $wp_query->query_vars['category_name'];
			$request_url = isset($wp_query->query_vars['product']) ? [
				'itaka' => "https://www.itaka.pl/{$wp_query->query_vars['product']}",
				'sletat' => "https://module.sletat.ru/Main.svc/ActualizePrice?" . preg_replace("/(\d+)\/(\d+)/", "offerId=$1&sourceId=$2", $wp_query->query_vars['product'])
			][$category_name] : "";
			$request_url = trim($request_url, "/");

			$posts = $wp_query->query([
				'post_type' => 'product',
				'name' => 'temp'
			]);
			if (empty($posts)) {
				add_filter('wp_insert_post_empty_content', '__return_false', 1000);
				$post_id = wp_insert_post([
					'post_category' => $category_name,
					'post_type' => 'product',
					'post_status' => 'publish',
					'post_name' => 'temp',
					'post_date_gmt' => date('Y-m-d H:i:s'),
					'post_modified_gmt' => date('Y-m-d H:i:s')
				]);
				remove_filter('wp_insert_post_empty_content', '__return_false', 1000);
				$posts = [get_post($post_id)];
			}

			if ($request_url) {
				$hash_request = md5($request_url);
				$dir = __DIR__ . "/backups_tours/{$category_name}";
				if (!is_dir($dir)) {
					mkdir($dir, '0777', true);
				}
				$file = $dir . "/" . $hash_request . ".json";
				if (false && file_exists($file)) {
					$params = json_decode(file_get_contents($file), true);
					foreach (['post_title', 'post_excerpt', 'post_content'] as $key) {
						if (isset($params[$key])) {
							$posts[0]->$key = $params[$key];
							unset($params[$key]);
						}
					}
				} else {
					$request = tsvet_remote_get($request_url, [
						'source' => $category_name
					]);
					$params = [];
					if (is_array($request) && isset($request['body'])) {
						$params = $category_name == 'itaka' ? get_itaka_tour_params($posts[0], $request['body']) :
							get_sletat_tour_params($posts[0], $request['body']);
						file_put_contents($file, json_encode(array_merge($params, [
							'post_title' => $posts[0]->post_title,
							'post_excerpt' => $posts[0]->post_excerpt,
							'post_content' => $posts[0]->post_content,
						])));
					}
				}

				foreach ($params as $param => $value) {
					cache_post_meta($posts[0]->ID, $param, $value);
				}
			}
		} else if (isset($wp_query->query_vars['taxonomy']) && $wp_query->query_vars['taxonomy'] == 'pa_country') {
			$posts = $wp_query->query([
				'post_type' => 'product',
				'taxonomy' => 'pa_country',
				'term' => $wp_query->query_vars['terms'],
				'paged' => $wp_query->query_vars['paged'],
				'suppress_filters' => true
			]);
		}
		if (!empty($posts)) {
			$wp_query->post = $posts[0];
			$GLOBALS['post'] = $posts[0];
			$wp_object_cache->replace($wp_query->post->ID, $wp_query->post, 'posts');
			return $posts;
		}
	}
	return $posts;
}
add_filter("the_posts", "tsvet_the_posts");

function tsvet_after_main_content()
{
	wp_enqueue_script('lazyload', get_template_directory_uri() . '/js/jquery.lazyload' . SUFFIX . '.js', [], null, true);
	wp_enqueue_script('magnific-popup', get_template_directory_uri() . '/js/jquery.magnific-popup' . SUFFIX . '.js', [], null, true);
	wp_enqueue_script('gallery', get_template_directory_uri() . '/js/gallery' . SUFFIX . '.js', ['flexslider', 'lazyload', 'magnific-popup'], null, true);
}
add_action('woocommerce_after_main_content', 'tsvet_after_main_content');

remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);







function array_get_value($array, $key, $default = null)
{
	if ($key instanceof \Closure) {
		return $key($array, $default);
	}
	if (is_array($key)) {
		$lastKey = array_pop($key);
		foreach ($key as $keyPart) {
			$array = array_get_value($array, $keyPart);
		}
		$key = $lastKey;
	}
	if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array))) {
		return $array[$key];
	}
	if (($pos = strrpos($key, '.')) !== false) {
		$array = array_get_value($array, substr($key, 0, $pos), $default);
		$key = substr($key, $pos + 1);
	}
	if (is_object($array)) {
		// this is expected to fail if the property does not exist, or __get() is not implemented
		// it is not reliably possible to check whether a property is accessible beforehand
		return $array->$key;
	} elseif (is_array($array)) {
		return (isset($array[$key]) || array_key_exists($key, $array)) ? $array[$key] : $default;
	}
	return $default;
}

function array_get_column($array, $name, $keepKeys = true)
{
	$result = [];
	if ($keepKeys) {
		foreach ($array as $k => $element) {
			$result[$k] = array_get_value($element, $name);
		}
	} else {
		foreach ($array as $element) {
			$result[] = array_get_value($element, $name);
		}
	}
	return $result;
}

function array_custom_multisort(&$array, $key, $direction = SORT_ASC, $sortFlag = SORT_REGULAR)
{
	$keys = is_array($key) ? $key : [$key];
	if (empty($keys) || empty($array)) {
		return;
	}
	$n = count($keys);
	if (is_scalar($direction)) {
		$direction = array_fill(0, $n, $direction);
	} elseif (count($direction) !== $n) {
		return;
	}
	if (is_scalar($sortFlag)) {
		$sortFlag = array_fill(0, $n, $sortFlag);
	} elseif (count($sortFlag) !== $n) {
		return;
	}
	$args = [];
	foreach ($keys as $i => $key) {
		$flag = $sortFlag[$i];
		$args[] = array_get_column($array, $key);
		$args[] = $direction[$i];
		$args[] = $flag;
	}
	// This fix is used for cases when main sorting specified by columns has equal values
	// Without it it will lead to Fatal Error: Nesting level too deep - recursive dependency?
	$args[] = range(1, count($array));
	$args[] = SORT_ASC;
	$args[] = SORT_NUMERIC;
	$args[] = &$array;
	call_user_func_array('array_multisort', $args);
}
function array_index($array, $key, $groups = [])
{
	$result = [];
	$groups = (array)$groups;
	foreach ($array as $element) {
		$lastArray = &$result;
		foreach ($groups as $group) {
			$value = array_get_value($element, $group);
			if (!array_key_exists($value, $lastArray)) {
				$lastArray[$value] = [];
			}
			$lastArray = &$lastArray[$value];
		}
		if ($key === null) {
			if (!empty($groups)) {
				$lastArray[] = $element;
			}
		} else {
			$value = array_get_value($element, $key);
			if ($value !== null) {
				if (is_float($value)) {
					$value = (string)$value;
				}
				$lastArray[$value] = $element;
			}
		}
		unset($lastArray);
	}
	return $result;
}

function array_custom_map($array, $from, $to, $group = null)
{
	$result = [];
	foreach ($array as $element) {
		$key = array_get_value($element, $from);
		$value = array_get_value($element, $to);
		if ($group !== null) {
			$result[array_get_value($element, $group)][$key] = $value;
		} else {
			$result[$key] = $value;
		}
	}
	return $result;
}

function sort_alphabet($array)
{
	uasort($array, function ($a, $b) {
		$la = mb_substr($a, 0, 1, 'utf-8');
		$lb = mb_substr($b, 0, 1, 'utf-8');
		if (ord($la) > 122 && ord($lb) > 122) {
			return $a > $b ? 1 : -1;
		}
		if (ord($la) > 122 || ord($lb) > 122) {
			return $a < $b ? 1 : -1;
		}
	});
	return $array;
}


function tsvet_widget_title($title, $instance, $id_base)
{
	return implode('', array_map(function ($line) {
		return "<div>{$line}</div>";
	}, explode('_br_', $title)));
}
add_filter('widget_title', 'tsvet_widget_title', 10, 3);

function tsvet_navigation_markup_template($template, $class)
{
	global $wp_query;
	$total = isset($wp_query->max_num_pages) ? $wp_query->max_num_pages : 1;
	$current = get_query_var('paged') ? intval(get_query_var('paged')) : 1;

	$prev = $total > 1 && $current == 1 ? "<span class=\"prev page-numbers\">Предыдущая</span>" : "";
	$next = $total > 1 && $current == $total ? "<span class=\"next page-numbers\">Следующая</span>" : "";

	return 'pagination' == $class ? '
	<nav class="navigation %1$s" role="navigation">
		<div class="nav-links">' . $prev . '%3$s' . $next . '</div>
	</nav>' : $template;
}
add_filter('navigation_markup_template', 'tsvet_navigation_markup_template', 10, 2);

function get_page_tsvet($params)
{
	$url = wp_parse_url($_POST['url']);
	$page = get_page_by_path($url['path']);
	wp_send_json_success([$page->post_content]);
}
add_action('wp_ajax_get_page', 'get_page_tsvet');
add_action('wp_ajax_nopriv_get_page', 'get_page_tsvet');

function tsvet_woocommerce_product_data_tabs($tabs)
{
	return [
		'general' => $tabs['general'],
		'attribute' => $tabs['attribute']
	];
}
add_filter('woocommerce_product_data_tabs', 'tsvet_woocommerce_product_data_tabs');

function wc_meta_box_product_data_output($post)
{
	ob_start();
	WC_Meta_Box_Product_Data::output($post);
	$output = ob_get_clean();
	$pos_start_removed = strpos($output, '<span class="type_box hidden">');
	$pos_end_removed = strpos($output, '<ul class="product_data_tabs wc-tabs">');
	$output = substr($output, 0, $pos_start_removed) . substr($output, $pos_end_removed);
	echo strtr($output, [
		'options_group show_if_external' => 'options_group show_if_external hidden',
		'options_group pricing show_if_simple show_if_external hidden' => 'options_group pricing show_if_simple show_if_external'
	]);
}
function tsvet_add_meta_boxes()
{
	global $wp_meta_boxes;
	if (isset($wp_meta_boxes['product']['normal']['high']['woocommerce-product-data'])) {
		$wp_meta_boxes['product']['normal']['high']['woocommerce-product-data']['callback'] = 'wc_meta_box_product_data_output';
	}
}
add_action('add_meta_boxes', 'tsvet_add_meta_boxes', 31);

function tsvet_register_taxonomies()
{
//	unregister_taxonomy( 'product_cat' );
	unregister_taxonomy('product_tag');
}
add_action('init', 'tsvet_register_taxonomies', 5);
remove_filter('post_type_link', 'wc_product_post_type_link', 10);

function tsvet_admin_menu()
{
	remove_menu_page('woocommerce');
}
add_action('admin_menu', 'tsvet_admin_menu', 100);

function tsvet_site_transient_update_plugins($value)
{
	if (isset($value->response['woocommerce/woocommerce.php'])) {
		return null;
	}
	return $value;
}
add_filter('site_transient_update_plugins', 'tsvet_site_transient_update_plugins');

function get_sletat_tour_params(&$product, $text_json) { return []; }
function get_itaka_tour_params(&$product, $text_html) { return []; }
function get_departures_sletat() { return []; }
function get_countries_sletat() { return []; }
function get_cities_sletat() { return []; }
function get_departures_itaka() { return []; }
function get_countries_itaka() { return []; }
function get_cities_itaka() { return []; }
function get_departures_tsvet() { return []; }
function get_countries_tsvet() { return []; }
function get_cities_tsvet() { return []; }
function get_geos_for_filters() { return []; }
function get_default_params_tout() { return []; }
function get_params_tout($tour_id) { return []; }

function site_the_content($content)
{
	if (is_product() && strpos($content, '<div class="product-productdescription">') === false) {
		return '<div class="product-productdescription product-productdescription_custom">' . $content . '</div>';
	}
	return $content;
}
add_filter('the_content', 'site_the_content');

function tours_tabs_shortcode($atts, $content)
{
	return tsvet_render_file("template-parts/tours-tabs", null, [
		'content' => do_shortcode($content),
		'active' => $atts['active'] ?? 'tsvet',
	]) ? : '';
}
add_shortcode('tours_tabs', 'tours_tabs_shortcode');

function tsvet_poll_shortcode($atts, $content)
{
	return tsvet_render_file("template-parts/poll", null, [
		'content' => do_shortcode($content)
	]) ? : '';
}
add_shortcode('tsvet_poll', 'tsvet_poll_shortcode');

function tsvet_rest_api_init() {
	register_rest_route( 'tsvet/v1', '/send-request', [
		'methods'  => 'POST',
		'callback' => 'tsvet_send_request',
	] );
}
add_action('rest_api_init', 'tsvet_rest_api_init');

function tsvet_send_request(WP_REST_Request $request) {

	$data = $request->get_param('data');

	$spam_me = $data['additional_info']['receive_newsletter'];
	$email = empty($data['info']['email']) ? null : $data['info']['email'];

	if ($spam_me && $email) {
		tsvet_spam_me($email, $data['info']['phone'], $data['info']['name']);
	}

	$content_table = tsvet_render_file("template-parts/tour-request-table", null, [
		'data' => $data,
	]) ? : '';

	$content = tsvet_render_file("template-parts/tour-request", null, [
		'content_table' => $content_table,
	]) ? : '';

	wp_mail(
		'tsvetbrest@gmail.com',
		'Подбор тура',
		$content,
		['content-type: text/html', 'Bcc: sprawas@gmail.com']
	);

	return 'ok';
}

function tsvet_spam_me($email, $phone, $name) {
	$SPApiClient = new ApiClient(API_USER_ID, API_SECRET, new FileStorage());

	$result = $SPApiClient->addEmails(SP_BOOK_ID, [[
		'email' => $email,
		'variables' => [
			'phone' => $phone,
			'name' => $name,
		],
	]]);
	
	return $result;
}