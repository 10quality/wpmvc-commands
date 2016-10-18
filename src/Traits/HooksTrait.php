<?php

namespace WPMVC\Commands\Traits;

use Ayuco\Exceptions\NoticeException;

/**
 * Trait used that contains wordpress hooks definitions.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.0.0
 */
trait HooksTrait
{
    /**
     * Predefined list of hooks.
     * @since 1.0.0
     * @var array
     */
    private $hooks = [
        'activated_plugin'                  => [
                                                'params'    => ['plugin', 'network_wide'],
                                            ],
        'activate_blog'                     => [
                                                'params'    => ['id'],
                                            ],
        'activate_plugin'                   => [
                                                'params'    => ['plugin', 'network_wide'],
                                            ],
        'added_existing_user'               => [
                                                'params'    => ['user_id', 'result'],
                                            ],
        'added_option'                      => [
                                                'params'    => ['option', 'value'],
                                            ],
        'added_term_relationship'           => [
                                                'params'    => ['object_id', 'term_id'],
                                            ],
        'added_usermeta'                    => [
                                                'params'    => ['insert_id', 'user_id', 'meta_key', 'meta_value'],
                                            ],
        'additional_capabilities_display'   => [
                                                'params'    => ['enable', 'user'],
                                            ],
        'add_attachment'                    => [
                                                'params'    => ['post_id'],
                                            ],
        'add_category'                      => [
                                                'params'    => ['cat_id'],
                                            ],
        'add_category_form_pre'             => [
                                                'params'    => ['arg'],
                                            ],
        'add_link'                          => [
                                                'params'    => ['link_id'],
                                            ],
        'add_link_category_form_pre'        => [
                                                'params'    => ['arg'],
                                            ],
        'add_menu_classes'                  => [
                                                'params'    => ['menu'],
                                            ],
        'add_meta_boxes'                    => [
                                                'params'    => ['object', 'link'],
                                            ],
        'add_meta_boxes_comment'            => [
                                                'params'    => ['comment'],
                                            ],
        'add_meta_boxes_link'               => [
                                                'params'    => ['link'],
                                            ],
        'add_option'                        => [
                                                'params'    => ['option', 'value'],
                                            ],
        'add_ping'                          => [
                                                'params'    => ['new'],
                                            ],
        'add_signup_meta'                   => [
                                                'params'    => ['meta'],
                                            ],
        'add_site_option'                   => [
                                                'params'    => ['option', 'value'],
                                            ],
        'add_tag_form'                      => [
                                                'params'    => ['taxonomy'],
                                            ],
        'add_tag_form_fields'               => [
                                                'params'    => ['taxonomy'],
                                            ],
        'add_tag_form_pre'                  => [
                                                'params'    => ['taxonomy'],
                                            ],
        'add_term_relationship'             => [
                                                'params'    => ['object_id', 'term_id'],
                                            ],
        'add_user_role'                     => [
                                                'params'    => ['user_id', 'role'],
                                            ],
        'add_user_to_blog'                  => [
                                                'params'    => ['user_id', 'role', 'blog_id'],
                                            ],
        'adminmenu'                         => [
                                                'scope'     => 'on_admin',
                                            ],
        'admin_bar_menu'                    => [
                                                'params'    => ['wp_admin_bar'],
                                            ],
        'admin_body_class'                  => [
                                                'scope'     => 'on_admin',
                                                'params'    => ['class'],
                                            ],
        'admin_color_scheme_picker'         => [
                                                'scope'     => 'on_admin',
                                                'params'    => ['user_id'],
                                            ],
        'admin_comment_types_dropdown'      => [
                                                'scope'     => 'on_admin',
                                                'params'    => ['types'],
                                            ],
        'admin_enqueue_scripts'             => [
                                                'scope'     => 'on_admin',
                                            ],
        'admin_footer'                      => [
                                                'scope'     => 'on_admin',
                                            ],
        'admin_footer-press-this.php'       => [
                                                'scope'     => 'on_admin',
                                            ],
        'admin_footer-widgets.php'          => [
                                                'scope'     => 'on_admin',
                                            ],
        'admin_footer_text'                 => [
                                                'scope'     => 'on_admin',
                                                'params'    => ['html'],
                                            ],
        'admin_head-media-upload-popup'     => [
                                                'scope'     => 'on_admin',
                                            ],
        'admin_head-press-this-php'         => [
                                                'scope'     => 'on_admin',
                                            ],
        'admin_memory_limit'                => [
                                                'scope'     => 'on_admin',
                                                'params'    => ['limit'],
                                            ],
        'admin_menu'                        => [
                                                'scope'     => 'on_admin',
                                            ],
        'admin_notices'                     => [
                                                'scope'     => 'on_admin',
                                            ],
        'admin_page_access_denied'          => [
                                                'scope'     => 'on_admin',
                                            ],
        'admin_post'                        => [
                                                'scope'     => 'on_admin',
                                            ],
        'admin_post_thumbnail_html'         => [
                                                'scope'     => 'on_admin',
                                                'params'    => ['content', 'post_id'],
                                            ],
        'admin_post_thumbnail_size'         => [
                                                'scope'     => 'on_admin',
                                                'params'    => ['size', 'thumbnail_id', 'post'],
                                            ],
        'admin_print_footer_scripts'        => [
                                                'scope'     => 'on_admin',
                                            ],
        'admin_print_scripts'               => [
                                                'scope'     => 'on_admin',
                                            ],
        'admin_print_scripts-media-upload-popup' 
                                            => [
                                                'scope'     => 'on_admin',
                                            ],
        'admin_print_scripts-press-this-php'
                                            => [
                                                'scope'     => 'on_admin',
                                            ],
        'admin_print_scripts-widgets.php'   => [
                                                'scope'     => 'on_admin',
                                            ],
        'admin_print_styles-media-upload-popup'
                                            => [
                                                'scope'     => 'on_admin',
                                            ],
        'admin_print_styles-press-this-php' => [
                                                'scope'     => 'on_admin',
                                            ],
        'admin_print_styles-widgets.php'    => [
                                                'scope'     => 'on_admin',
                                            ],
        'admin_title'                       => [
                                                'scope'     => 'on_admin',
                                                'params'    => ['admin_title', 'title'],
                                            ],
        'admin_url'                         => [
                                                'scope'     => 'on_admin',
                                                'params'    => ['url', 'path', 'blog_id'],
                                            ],
        'admin_xml_ns'                      => [
                                                'scope'     => 'on_admin',
                                            ],
        'after_db_upgrade'                  => [
                                                'scope'     => 'on_admin',
                                            ],
        'after_delete_post'                 => [
                                                'params'    => ['post_id'],
                                            ],
        'after_mu_upgrade'                  => [
                                                'scope'     => 'on_admin',
                                                'params'    => ['response'],
                                            ],
        'after_password_reset'              => [
                                                'params'    => ['user', 'new_password'],
                                            ],
        'after_plugin_row'                  => [
                                                'scope'     => 'on_admin',
                                                'params'    => ['file', 'data', 'status'],
                                            ],
        'after_setup_theme'                 => [
                                                'scope'     => 'on_admin',
                                            ],
        'after_signup_site'                 => [
                                                'params'    => ['domain', 'path', 'title', 'user', 'email', 'key', 'meta'],
                                            ],
        'after_signup_user'                 => [
                                                'params'    => ['user', 'email', 'key', 'meta'],
                                            ],
        'after_switch_theme'                => [
                                                'scope'     => 'on_admin',
                                                'params'    => ['old_name', 'old_theme'],
                                            ],
        'after_theme_row'                   => [
                                                'scope'     => 'on_admin',
                                                'params'    => ['stylesheet', 'theme', 'status'],
                                            ],
        'after_wp_tiny_mce'                 => [
                                                'params'    => ['settings'],
                                            ],
        'ajax_query_attachments_args'       => [
                                                'params'    => ['query'],
                                            ],
        'allowed_redirect_hosts'            => [
                                                'params'    => ['hosts', 'host'],
                                            ],
        'allowed_themes'                    => [
                                                'params'    => ['themes'],
                                            ],
        'allow_dev_auto_core_updates'       => [
                                                'scope'     => 'on_admin',
                                                'params'    => ['allow'],
                                            ],
        'allow_major_auto_core_updates'     => [
                                                'scope'     => 'on_admin',
                                                'params'    => ['allow'],
                                            ],
        'allow_minor_auto_core_updates'     => [
                                                'scope'     => 'on_admin',
                                                'params'    => ['allow'],
                                            ],
        'allow_password_reset'              => [
                                                'params'    => ['allow', 'user_id'],
                                            ],
        'allow_subdirectory_install'        => [
                                                'scope'     => 'on_admin',
                                                'params'    => ['allow'],
                                            ],
        'all_admin_notices'                 => [
                                                'scope'     => 'on_admin',
                                            ],
        'all_plugins'                       => [
                                                'params'    => ['plugins'],
                                            ],
        'all_themes'                        => [
                                                'params'    => ['themes'],
                                            ],
        'archive_blog'                      => [
                                                'params'    => ['blog_id'],
                                            ],
    ];

    /**
     * Retuns the parameters defined for a specific hook.
     * @since 1.0.0
     *
     * @param string $hook Wordpress hook.
     *
     * @return array
     */
    private function getHookParams($hook)
    {
        return isset($this->hooks[$hook]) && isset($this->hooks[$hook]['params'])
            ? $this->hooks[$hook]['params']
            : [];
    }

    /**
     * Retuns the scope defined for a specific hook.
     * @since 1.0.0
     *
     * @param string $hook Wordpress hook.
     *
     * @return string
     */
    private function getHookScope($hook)
    {
        return isset($this->hooks[$hook]) && isset($this->hooks[$hook]['scope'])
            ? $this->hooks[$hook]['scope']
            : 'init';
    }
}