<?php

namespace Tadcms\Backend\Helpers;

class HookAction
{
    public function getFilter($name, $default = null)
    {
        return apply_filters($name, $default);
    }
    
    /**
     * TAD CMS: Add a top-level menu page.
     *
     * This function takes a capability which will be used to determine whether
     * or not a page is included in the menu.
     *
     * The function which is hooked in to handle the output of the page must check
     * that the user has the required capability as well.
     *
     * @param string $menuTitle The trans key to be used for the menu.
     * @param string $menuSlug The url name to refer to this menu by. not include admin-cp
     * @param array $args
     * - string $icon Url icon or fa icon fonts
     * - string $parent The parent of menu. Default null
     * - int $position The position in the menu order this item should appear.
     * @return bool.
     */
    public function addMenuPage($menuTitle, $menuSlug, $args = []) {
        $opts = [
            'title' => $menuTitle,
            'key' => $menuSlug,
            'icon' => 'fa fa-list-alt',
            'url' => str_replace('.', '/', $menuSlug),
            'parent' => null,
            'position' => 20,
        ];
        $item = array_merge($opts, $args);
        
        return add_filters('admin_menu', function ($menu) use ($item) {
            if ($item['parent']) {
                $menu[$item['parent']]['children'][$item['key']] = $item;
            }
            else {
                if (isset($menu[$item['key']])) {
                    if (isset($menu[$item['key']]['children'])) {
                        $item['children'] = $menu[$item['key']]['children'];
                    }
                    
                    $menu[$item['key']] = $item;
                }
                else {
                    $menu[$item['key']] = $item;
                }
            }
            
            return $menu;
        });
    }
    
    public function registerTaxonomy($taxonomy, $args = [])
    {
        $opts = [
            'label' => '',
            'description' => '',
            'taxonomy' => $taxonomy,
            'url' => 'taxonomy/' . str_replace('_', '-', $taxonomy),
            'parent' => null,
            'menu_position' => 20,
            'menu_icon' => 'fa fa-list-alt',
            'supports' => ['thumbnail'],
        ];
        $args = array_merge($opts, $args);
        
        add_filters('taxonomies', function ($items) use ($args) {
            $items[$args['taxonomy']] = $args;
            return $items;
        });
        
        return true;
    }

    public function registerPostType($postType, $args = [])
    {
        $opts = [
            'label' => '',
            'post_type' => $postType,
            'description' => '',
            'menu_position' => 20,
            'menu_icon' => 'fa fa-list-alt',
            'supports' => [],
        ];
        $args = array_merge($opts, $args);
    
        add_filters('post_types', function ($items) use ($args) {
            $items[$args['post_type']] = $args;
            return $items;
        });
        
        if (in_array('category', $args['supports'])) {
            $this->registerTaxonomy($postType . '_categories', [
                'label' => 'tadcms::app.categories',
                'parent' => 'post-type.' . $postType,
                'menu_position' => $args['menu_position'] + 1
            ]);
        }
    
        if (in_array('tag', $args['supports'])) {
            $this->registerTaxonomy($postType . '_tags', [
                'label' => 'tadcms::app.tags',
                'parent' => 'post-type.' . $postType,
                'menu_position' => $args['menu_position'] + 2
            ]);
        }
        
        return true;
    }
}