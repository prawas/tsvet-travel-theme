<?php
if(class_exists('Tsvet_Walker_Nav_Menu'))
    return null;

/**
 * Класс для управления выводом навигационного меню
 *
 * Class Tsvet_Walker_Nav_Menu
 */
class Tsvet_Walker_Nav_Menu extends Walker_Nav_Menu
{
    /**
     * Starts the list before the elements are added.
     *
     * @since 3.0.0
     *
     * @see Walker::start_lvl()
     *
     * @param string   $output Passed by reference. Used to append additional content.
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function start_lvl(&$output, $depth = 0, $args = []) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);
        $output .= "{$n}{$indent}<ul class=\"" . implode(' ', (array) $args->menu_class) . "\">{$n}";
    }

    /**
     * Traverse elements to create list from elements.
     *
     * Display one element if the element doesn't have any children otherwise,
     * display the element and its children. Will only traverse up to the max
     * depth and no ignore elements under that depth. It is possible to set the
     * max depth to include all depths, see walk() method.
     *
     * This method should not be called directly, use the walk() method instead.
     *
     * @since 2.5.0
     *
     * @param object $element           Data object.
     * @param array  $children_elements List of elements to continue traversing.
     * @param int    $max_depth         Max depth to traverse.
     * @param int    $depth             Depth of current element.
     * @param array  $args              An array of arguments.
     * @param string $output            Passed by reference. Used to append additional content.
     */
    public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output) {
        if (!$element) {
            return;
        }

        $id_field = $this->db_fields['id'];
        $id       = $element->$id_field;

        //display this element
        $args_children = $args;
        $this->has_children = ! empty($children_elements[ $id ]);
        if (isset($args[0])) {
            if($is_array_args = is_array($args[0])) {
                $args[0]['has_children'] = $this->has_children; // Back-compat.
            }

            $args_children[0] = $args_parent = (array) $args[0];
            if(isset($args_parent['children'])) {
                unset($args_children[0]['children']);
                $args_children[0] = array_merge($args_children[0], $args_parent['children']);

                if(!$is_array_args) {
                    $args_children[0] = (object) $args_children[0];
                }
            }
        }

        $cb_args = array_merge([&$output, $element, $depth], $args);
        call_user_func_array([$this, 'start_el'], $cb_args);

        // descend only when the depth is right and there are childrens for this element
        if (($max_depth == 0 || $max_depth > $depth + 1) && isset($children_elements[$id])) {
            //start the child delimiter
            $cb_args = array_merge([&$output, $depth], $args_children);
            call_user_func_array([$this, 'start_lvl'], $cb_args);

            foreach ($children_elements[ $id ] as $child){
                $this->display_element($child, $children_elements, $max_depth, $depth + 1, $args_children, $output);
            }

            //end the child delimiter
            $cb_args = array_merge([&$output, $depth], $args);
            call_user_func_array([$this, 'end_lvl'], $cb_args);

            unset($children_elements[ $id ]);
        }

        //end this element
        $cb_args = array_merge([&$output, $element, $depth], $args);
        call_user_func_array([$this, 'end_el'], $cb_args);
    }
}