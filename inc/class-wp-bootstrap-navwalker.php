<?php
/**
 * WP Bootstrap Navwalker
 *
 * @package Hot_News
 */

// Check if Class Exists.
if (!class_exists('WP_Bootstrap_Navwalker')) :
    /**
     * WP_Bootstrap_Navwalker class.
     */
    class WP_Bootstrap_Navwalker extends Walker_Nav_Menu {

        /**
         * Starts the list before the elements are added.
         *
         * @since WP 3.0.0
         *
         * @see Walker_Nav_Menu::start_lvl()
         *
         * @param string   $output Used to append additional content (passed by reference).
         * @param int      $depth  Depth of menu item. Used for padding.
         * @param stdClass $args   An object of wp_nav_menu() arguments.
         */
        public function start_lvl(&$output, $depth = 0, $args = null) {
            if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $indent = str_repeat($t, $depth);
            $output .= "{$n}{$indent}<div class=\"dropdown-menu\">{$n}";
        }

        /**
         * Ends the list of after the elements are added.
         *
         * @since WP 3.0.0
         *
         * @see Walker_Nav_Menu::end_lvl()
         *
         * @param string   $output Used to append additional content (passed by reference).
         * @param int      $depth  Depth of menu item. Used for padding.
         * @param stdClass $args   An object of wp_nav_menu() arguments.
         */
        public function end_lvl(&$output, $depth = 0, $args = null) {
            if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $indent = str_repeat($t, $depth);
            $output .= "{$n}{$indent}</div>{$n}";
        }

        /**
         * Starts the element output.
         *
         * @since WP 3.0.0
         * @since WP 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
         *
         * @see Walker_Nav_Menu::start_el()
         *
         * @param string   $output Used to append additional content (passed by reference).
         * @param WP_Post  $item   Menu item data object.
         * @param int      $depth  Depth of menu item. Used for padding.
         * @param stdClass $args   An object of wp_nav_menu() arguments.
         * @param int      $id     Current item ID.
         */
        public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
            if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $indent = ($depth) ? str_repeat($t, $depth) : '';

            $classes = empty($item->classes) ? array() : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;

            /**
             * Filters the arguments for a single nav menu item.
             *
             * @since WP 4.4.0
             *
             * @param stdClass $args  An object of wp_nav_menu() arguments.
             * @param WP_Post  $item  Menu item data object.
             * @param int      $depth Depth of menu item. Used for padding.
             */
            $args = apply_filters('nav_menu_item_args', $args, $item, $depth);

            /**
             * Filters the CSS class(es) applied to a menu item's list item element.
             *
             * @since WP 3.0.0
             * @since WP 4.1.0 The `$depth` parameter was added.
             *
             * @param array    $classes The CSS classes that are applied to the menu item's `<li>` element.
             * @param WP_Post  $item    The current menu item.
             * @param stdClass $args    An object of wp_nav_menu() arguments.
             * @param int      $depth   Depth of menu item. Used for padding.
             */
            $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));

            // New menu item classes.
            $li_class = '';
            $a_class = '';

            if (0 === $depth) {
                $li_class = 'nav-item';
                $a_class = 'nav-link';
            }

            if (in_array('menu-item-has-children', $classes, true)) {
                $li_class .= ' dropdown';
                $a_class .= ' dropdown-toggle';
            }

            if (in_array('current-menu-item', $classes, true)) {
                $a_class .= ' active';
            }

            $class_names = $class_names ? ' class="' . esc_attr($class_names) . ' ' . $li_class . '"' : ' class="' . $li_class . '"';

            /**
             * Filters the ID applied to a menu item's list item element.
             *
             * @since WP 3.0.1
             * @since WP 4.1.0 The `$depth` parameter was added.
             *
             * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
             * @param WP_Post  $item    The current menu item.
             * @param stdClass $args    An object of wp_nav_menu() arguments.
             * @param int      $depth   Depth of menu item. Used for padding.
             */
            $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
            $id = $id ? ' id="' . esc_attr($id) . '"' : '';

            if (0 === $depth) {
                $output .= $indent . '<div' . $id . $class_names . '>' . $n;
            }

            $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
            $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
            $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
            $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

            // Add Bootstrap dropdown attributes.
            if (in_array('menu-item-has-children', $classes, true) && 0 === $depth) {
                $attributes .= ' data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"';
            }

            $item_output = isset($args->before) ? $args->before : '';

            if (0 === $depth) {
                $item_output .= '<a class="' . $a_class . '"' . $attributes . '>';
            } else {
                $item_output .= '<a class="dropdown-item"' . $attributes . '>';
            }

            $item_output .= isset($args->link_before) ? $args->link_before : '';
            $item_output .= apply_filters('the_title', $item->title, $item->ID);
            $item_output .= isset($args->link_after) ? $args->link_after : '';
            $item_output .= '</a>';
            $item_output .= isset($args->after) ? $args->after : '';

            /**
             * Filters a menu item's starting output.
             *
             * The menu item's starting output only includes `$args->before`, the opening `<a>`,
             * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
             * no filter for modifying the opening and closing `<li>` for a menu item.
             *
             * @since WP 3.0.0
             *
             * @param string   $item_output The menu item's starting HTML output.
             * @param WP_Post  $item        Menu item data object.
             * @param int      $depth       Depth of menu item. Used for padding.
             * @param stdClass $args        An object of wp_nav_menu() arguments.
             */
            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
        }

        /**
         * Ends the element output, if needed.
         *
         * @since WP 3.0.0
         *
         * @see Walker_Nav_Menu::end_el()
         *
         * @param string   $output Used to append additional content (passed by reference).
         * @param WP_Post  $item   Page data object. Not used.
         * @param int      $depth  Depth of page. Not Used.
         * @param stdClass $args   An object of wp_nav_menu() arguments.
         */
        public function end_el(&$output, $item, $depth = 0, $args = null) {
            if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }

            if (0 === $depth) {
                $output .= "</div>{$n}";
            }
        }
    }
endif;
