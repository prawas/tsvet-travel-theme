<?php

require __DIR__ . '/class/class-tsvet-walker-nav-menu.php';

/**
 * Использовать классы, переданные в меню, для элемента меню
 *
 * @param array    $classes Стандатные классы элемента меню
 * @param WP_Post  $item    Menu item data object.
 * @param stdClass $args    An object of wp_nav_menu() arguments.
 *
 * @return array
 */
function tsvet_nav_menu_css_class($classes, $item, $args) {
	$blocks = ['menu', 'page'];
	$elements = ['item', 'parent', 'ancestor'];
	$mods = ['type', 'object', 'home', 'current'];

	$active_class = in_array('current-menu-item', $classes) && isset($args->active_items_class) ?
		(array) $args->active_items_class : [];
	$parent_class = in_array('menu-item-has-children', $classes) && isset($args->parent_class) ?
		(array) $args->parent_class : [];

	$item_class = isset($args->items_class) ? $args->items_class : '';

	foreach($classes as &$class) {
		if($class == '' || $class == 'menu-item-' . $item->ID) {
			$class = '';
			continue;
		}
		$split_class = array_filter(preg_split("/[\-_]/", $class));
		$result_class = ['mod_val' => []];

		foreach($split_class as $split_el) {
			if(in_array($split_el, $blocks)) {
				$result_class['block'] = $split_el;
			} else if(in_array($split_el, $elements)) {
				$result_class['element'] = $split_el;
			} else if(in_array($split_el, $mods)) {
				$result_class['mod'] = $split_el;
			} else {
				$result_class['mod_val'][] = $split_el;
			}
		}

		if(isset($result_class['block'])) {
			$class = $result_class['block']
			         . ( isset( $result_class['element'] ) ? "__{$result_class['element']}" : '' )
			         . ( isset( $result_class['mod'] ) ? "_{$result_class['mod']}" : '' )
			         . ( ! empty( $result_class['mod_val'] ) ? ( "_" . implode( '-', $result_class['mod_val'] ) ) : '' );
		}
		if($item_class != '') {
			$class = str_replace('menu__item', $item_class, $class);
		}
	}

	return array_merge(array_filter($classes), $active_class, $parent_class);
}
add_filter('nav_menu_css_class', 'tsvet_nav_menu_css_class', 10, 3);

/**
 * Убрать id элемента меню
 */
add_filter('nav_menu_item_id', '__return_false');

/**
 * Добавить классы, переданные в меню, ссылке элемента меню
 *
 * @param array $attrs {
 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
 *
 *     @type string $title  Title attribute.
 *     @type string $target Target attribute.
 *     @type string $rel    The rel attribute.
 *     @type string $href   The href attribute.
 * }
 * @param WP_Post  $item  The current menu item.
 * @param stdClass $args  An object of wp_nav_menu() arguments.
 *
 * @return mixed
 */
function tsvet_nav_menu_link_attributes($attrs, $item, $args) {
    if(isset($args->link_class)) {
        $attrs['class'] = implode(' ', (array) $args->link_class);

        if(isset($args->active_link_class) && empty($item->classes) && in_array('current-menu-item', (array) $item->classes)) {
            $attrs['class'] .= ' ' . $args->active_link_class;
        }
    }
    return $attrs;
}
add_filter('nav_menu_link_attributes', 'tsvet_nav_menu_link_attributes', 10, 3);

/**
 * Указываем класс, управляющий навигационным меню
 *
 * @param array $args Array of wp_nav_menu() arguments.
 *
 * @return mixed
 */
function tsvet_nav_menu_args($args) {
    $args['walker'] = new Tsvet_Walker_Nav_Menu();
    return $args;
}
add_filter('wp_nav_menu_args', 'tsvet_nav_menu_args', 1001);


function tsvet_widget_nav_menu_args($nav_menu_args) {
	static $MENU_ID;
	if($MENU_ID == null) {
		$MENU_ID = 0;
	}
	$nav_menu_args['container'] = '';
	$nav_menu_args['menu_id'] = 'menu-' . ++$MENU_ID;

	return $nav_menu_args;
}
add_filter('widget_nav_menu_args', 'tsvet_widget_nav_menu_args');
