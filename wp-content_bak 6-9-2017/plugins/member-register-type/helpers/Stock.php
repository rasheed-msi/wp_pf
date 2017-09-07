<?php

class Stock {

    public static function system_roles() {

        return array(
            'administrator' => array(
                'name' => 'Administrator',
                'capabilities' => array(
                    'switch_themes',
                    'edit_themes',
                    'activate_plugins',
                    'edit_plugins',
                    'edit_users',
                    'edit_files',
                    'manage_options',
                    'moderate_comments',
                    'manage_categories',
                    'manage_links',
                    'upload_files',
                    'import',
                    'unfiltered_html',
                    'edit_posts',
                    'edit_others_posts',
                    'edit_published_posts',
                    'publish_posts',
                    'edit_pages',
                    'read',
                    'level_10',
                    'level_9',
                    'level_8',
                    'level_7',
                    'level_6',
                    'level_5',
                    'level_4',
                    'level_3',
                    'level_2',
                    'level_1',
                    'level_0',
                    'edit_others_pages',
                    'edit_published_pages',
                    'publish_pages',
                    'delete_pages',
                    'delete_others_pages',
                    'delete_published_pages',
                    'delete_posts',
                    'delete_others_posts',
                    'delete_published_posts',
                    'delete_private_posts',
                    'edit_private_posts',
                    'read_private_posts',
                    'delete_private_pages',
                    'edit_private_pages',
                    'read_private_pages',
                    'delete_users',
                    'create_users',
                    'unfiltered_upload',
                    'edit_dashboard',
                    'update_plugins',
                    'delete_plugins',
                    'install_plugins',
                    'update_themes',
                    'install_themes',
                    'update_core',
                    'list_users',
                    'remove_users',
                    'promote_users',
                    'edit_theme_options',
                    'delete_themes',
                    'export',
                    'groups_access',
                    'groups_admin_groups',
                    'groups_admin_options',
                    'groups_restrict_access',
                    'draft_to_profile',
                    'view_subscriptions_nav',
                ),
            ),
            'adoption_agency' => array(
                'name' => 'Adoption Agency',
                'capabilities' => array(
                    'publish_posts',
                    'edit_posts',
                    'delete_posts',
                    'draft_to_profile',
                    'edit_attachments',
                    'delete_attachments',
                    'publish_attachments',
                    'upload_files',
                    'edit_published_posts',
                    'edit_posts',
                    'delete_published_posts',
                    'delete_posts',
                ),
            ),
            'adoptive_family' => array(
                'name' => 'Adoptive Family',
                'capabilities' => array(
                    'publish_posts',
                    'edit_posts',
                    'delete_posts',
                    'view_subscriptions_nav',
                    'edit_attachments',
                    'delete_attachments',
                    'publish_attachments',
                    'upload_files',
                    'edit_published_posts',
                    'edit_posts',
                    'delete_published_posts',
                    'delete_posts',
                ),
            ),
            'birth_mother' => array(
                'name' => 'Birth Mother',
                'capabilities' => array(
                    'publish_posts',
                    'edit_posts',
                    'delete_posts',
                    'edit_attachments',
                    'delete_attachments',
                    'publish_attachments',
                    'upload_files',
                    'edit_published_posts',
                    'edit_posts',
                    'delete_published_posts',
                    'delete_posts',
                ),
            ),
        );
    }

    /**
     * 
     * Role in name value pare - dashboard
     */
    public static function role_list() {
        $system_roles = self::system_roles();
        $result = array();
        foreach ($system_roles as $key => $value) {
            // remove public from the list
            if ($key == 'public') {
                continue;
            }
            $result[$key] = $value['name'];
        }
        return $result;
    }

    function navs() {
        return [
            'approvals' => [
                'label' => 'Approvals',
                'class' => 'approvals',
            ],
            'matching' => [
                'label' => 'Matching',
                'class' => 'approvals',
            ],
            'families' => [
                'label' => 'Families',
                'class' => 'families',
            ],
            'manage_families' => [
                'label' => 'Manage Families',
                'class' => 'manage_families',
            ],
            'public_profile' => [
                'label' => 'Public Profile',
                'class' => 'public_profile',
            ],
            'blog' => [
                'label' => 'Blog',
                'class' => 'blog',
            ],
        ];
    }

    public static function wordpress_user_roles() {
        return ['administrator', 'editor', 'author', 'contributor', 'subscriber'];
    }

}
