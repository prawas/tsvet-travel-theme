<?php wp_nav_menu([
    'theme_location' => 'top',

    'menu_id'    => 'top-menu',
    'menu_class' => 'top-menu site-header__top-menu',

    'container'       => 'nav',
    'container_class' => 'navigation-top',

    'items_class'        => 'top-menu__item',
    'active_items_class' => 'top-menu__item_active',
    'parent_class'       => 'top-menu__parent',

    'link_class'        => 'top-menu__link',
    'active_link_class' => 'top-menu__link_active',
    //'link_before'       => '<span class="top-menu__text-link">',
    //'link_after'        => '</span>',

    'children' => [
        'menu_class' => 'top-menu__submenu',

        'items_class'        => 'top-menu__submenu-item',
        'active_items_class' => 'top-menu__submenu-item_active',

        'link_class'         => 'top-menu__submenu-link',
        'active_link_class'  => 'top-menu__submenu-link_active',
        'link_before'       => '',
        'link_after'        => '',
    ]
]); ?>
