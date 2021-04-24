<?php

namespace Tadcms\Backend\Helpers\Menu;

use Tadcms\Backend\Facades\HookAction;
use Tadcms\Backend\Helpers\MenuCollection;
use Theanh\LaravelHooks\Facades\Events;

/**
 * Class BackendMenu
 *
 * @package    Theanh\Tadcms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/theanhk/tadcms
 * @license    MIT
 */
class BackendMenu
{
    public static function render()
    {
        $items = MenuCollection::make(apply_filters('admin_menu', []));
        return view('tadcms::items.admin_menu', [
            'items' => $items,
        ]);
    }
    
    public static function tadMenuLeft()
    {
        HookAction::addMenuPage(
            'tadcms::app.dashboard',
            'dashboard',
            [
                'icon' => 'fa fa-dashboard',
                'position' => 1
            ]
        );
    
        /*HookAction::addMenuPage(
            'tadcms::app.dashboard',
            'dashboard',
            [
                'icon' => 'fa fa-dashboard',
                'position' => 1,
                'parent' => 'dashboard',
            ]
        );*/
    
        /*HookAction::addMenuPage(
            'tadcms::app.updates',
            'dashboard.update',
            [
                'icon' => 'fa fa-upgrade',
                'position' => 2,
                'parent' => 'dashboard',
            ]
        );*/
    
        HookAction::addMenuPage(
            'tadcms::app.comments',
            'comments',
            [
                'icon' => 'fa fa-comments',
                'position' => 30
            ]
        );
    
        HookAction::addMenuPage(
            'tadcms::app.media',
            'media',
            [
                'icon' => 'fa fa-photo',
                'position' => 5
            ]
        );
    
        HookAction::addMenuPage(
            'tadcms::app.users',
            'users',
            [
                'icon' => 'fa fa-users',
                'position' => 60
            ]
        );
        
        /*add_menu_page(
            'tadcms::app.translations',
            'translations',
            'fa fa-language',
            null,
            100
        );*/

        /*add_menu_page(
            'tadcms::app.permissions',
            'translations',
            'fa fa-language',
            null,
            100
        );*/

        HookAction::addMenuPage(
            'tadcms::app.notification',
            'notification',
            [
                'icon' => 'fa fa-bell',
                'position' => 100
            ]
        );
    }
    
    public static function tadAppearanceMenu()
    {
        HookAction::addMenuPage(
            'tadcms::app.appearance',
            'themes',
            [
                'icon' => 'fa fa-paint-brush',
                'position' => 45
            ]
        );
    
        HookAction::addMenuPage(
            'tadcms::app.themes',
            'themes',
            [
                'icon' => 'fa fa-cogs',
                'parent' => 'themes',
                'position' => 45
            ]
        );
    
        HookAction::addMenuPage(
            'tadcms::app.menus',
            'menus',
            [
                'icon' => 'fa fa-cogs',
                'parent' => 'themes',
                'position' => 46
            ]
        );
    
        HookAction::addMenuPage(
            'tadcms::app.menus',
            'menus',
            [
                'icon' => 'fa fa-cogs',
                'parent' => 'themes',
                'position' => 46
            ]
        );
    }
    
    public static function tadPluginMenu()
    {
        HookAction::addMenuPage(
            'tadcms::app.plugins',
            'plugins',
            [
                'icon' => 'fa fa-plug',
                'position' => 50
            ]
        );
    }
    
    public static function tadSettingMenu()
    {
        HookAction::addMenuPage(
            'tadcms::app.setting',
            'setting',
            [
                'icon' => 'fa fa-cogs',
                'position' => 99
            ]
        );
        
        HookAction::addMenuPage(
            'tadcms::app.general-setting',
            'setting',
            [
                'icon' => 'fa fa-cogs',
                'parent' => 'setting',
                'position' => 1
            ]
        );
        
        HookAction::addMenuPage(
            'tadcms::app.email-template',
            'email-template',
            [
                'icon' => 'fa fa-cogs',
                'parent' => 'setting',
                'position' => 3
            ]
        );
    }
    
    public static function tadPostTypeMenu()
    {
        HookAction::addMenuPage(
            'tadcms::app.posts',
            'posts',
            [
                'icon' => 'fa fa-edit',
                'position' => 15
            ]
        );

        HookAction::addMenuPage(
            'tadcms::app.all-posts',
            'posts',
            [
                'position' => 2,
                'parent' => 'posts',
            ]
        );

        HookAction::addMenuPage(
            'tadcms::app.add-new',
            'posts.create',
            [
                'position' => 3,
                'parent' => 'posts',
            ]
        );

        HookAction::addMenuPage(
            'tadcms::app.categories',
            'categories',
            [
                'icon' => 'fa fa-list-alt',
                'parent' => 'posts',
                'position' => 4
            ]
        );

        HookAction::addMenuPage(
            'tadcms::app.tags',
            'tags',
            [
                'icon' => 'fa fa-list-alt',
                'parent' => 'posts',
                'position' => 5
            ]
        );
    }

    public static function tadPageMenu()
    {
        HookAction::addMenuPage(
            'tadcms::app.pages',
            'pages',
            [
                'icon' => 'fa fa-edit',
                'position' => 15
            ]
        );

        HookAction::addMenuPage(
            'tadcms::app.all-pages',
            'pages',
            [
                'position' => 2,
                'parent' => 'pages',
            ]
        );

        HookAction::addMenuPage(
            'tadcms::app.add-new',
            'pages.create',
            [
                'position' => 3,
                'parent' => 'pages',
            ]
        );

        HookAction::addMenuPage(
            'tadcms::app.tags',
            'page-tags',
            [
                'icon' => 'fa fa-list-alt',
                'parent' => 'pages',
                'position' => 5
            ]
        );
    }
}
