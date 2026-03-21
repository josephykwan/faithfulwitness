<?php
/**
 * Faithful Witness — Roles, Capabilities & Admin UX
 *
 * Covers:
 *  - Content Manager user role (Section 7C)
 *  - Admin dashboard widget customization (Section 7B)
 *  - Rename "Posts" to "Stories" (Section 7C spec)
 *  - Duplicate post functionality (Section 7D)
 *  - Editor title placeholder text (Section 7E)
 *  - Admin menu visibility by role (Section 7A)
 *  - wp-config.php security reminders
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ============================================================
// 7C — CONTENT MANAGER ROLE
// Register on after_switch_theme (and admin_init fallback).
// ============================================================

/**
 * Capabilities granted to the Content Manager.
 * Covers built-in posts, pages, media, and all FW CPTs.
 */
function fw_get_content_manager_caps() {
    $caps = [
        // Core
        'read'                   => true,
        'upload_files'           => true,
        'edit_pages'             => true,
        'read_private_pages'     => true,

        // Standard posts (Stories)
        'edit_posts'                 => true,
        'edit_others_posts'          => true,
        'edit_published_posts'       => true,
        'publish_posts'              => true,
        'delete_posts'               => true,
        'delete_published_posts'     => true,

        // Custom post types — Resources
        'edit_fw_resources'              => true,
        'edit_others_fw_resources'       => true,
        'edit_published_fw_resources'    => true,
        'publish_fw_resources'           => true,
        'delete_fw_resources'            => true,
        'delete_published_fw_resources'  => true,
        'read_private_fw_resources'      => true,

        // Custom post types — Media Hits
        'edit_fw_media_hits'             => true,
        'edit_others_fw_media_hits'      => true,
        'edit_published_fw_media_hits'   => true,
        'publish_fw_media_hits'          => true,
        'delete_fw_media_hits'           => true,
        'delete_published_fw_media_hits' => true,

        // Custom post types — Organizing Groups (Network Partners)
        'edit_fw_organizing_groups'              => true,
        'edit_others_fw_organizing_groups'       => true,
        'edit_published_fw_organizing_groups'    => true,
        'publish_fw_organizing_groups'           => true,
        'delete_fw_organizing_groups'            => true,
        'delete_published_fw_organizing_groups'  => true,

        // Custom post types — Events
        'edit_fw_events'             => true,
        'edit_others_fw_events'      => true,
        'edit_published_fw_events'   => true,
        'publish_fw_events'          => true,
        'delete_fw_events'           => true,
        'delete_published_fw_events' => true,

        // Custom post types — Initiatives
        'edit_fw_initiatives'            => true,
        'edit_others_fw_initiatives'     => true,
        'edit_published_fw_initiatives'  => true,
        'publish_fw_initiatives'         => true,
        'delete_fw_initiatives'          => true,
        'delete_published_fw_initiatives'=> true,

        // DENIED — security-sensitive capabilities
        'install_plugins'    => false,
        'activate_plugins'   => false,
        'edit_plugins'       => false,
        'install_themes'     => false,
        'switch_themes'      => false,
        'edit_theme_options' => false,
        'manage_options'     => false,
        'edit_users'         => false,
        'delete_users'       => false,
        'create_users'       => false,
        'list_users'         => false,
        'edit_files'         => false,
    ];
    return $caps;
}

function fw_register_content_manager_role() {
    // Only register if the role doesn't exist yet.
    if ( ! get_role( 'content_manager' ) ) {
        add_role(
            'content_manager',
            __( 'Content Manager', 'faithfulwitness' ),
            fw_get_content_manager_caps()
        );
    } else {
        // Sync caps if role already exists (handles updates).
        $role = get_role( 'content_manager' );
        foreach ( fw_get_content_manager_caps() as $cap => $grant ) {
            if ( $grant ) {
                $role->add_cap( $cap );
            } else {
                $role->remove_cap( $cap );
            }
        }
    }
}
add_action( 'after_switch_theme', 'fw_register_content_manager_role' );
add_action( 'admin_init',         'fw_register_content_manager_role' );

// ============================================================
// 7B — DASHBOARD: remove default widgets, add FW welcome widget
// ============================================================

function fw_customize_dashboard() {
    // Remove all default WP dashboard widgets.
    remove_meta_box( 'dashboard_activity',              'dashboard', 'normal' );
    remove_meta_box( 'dashboard_right_now',             'dashboard', 'normal' );
    remove_meta_box( 'dashboard_recent_comments',       'dashboard', 'normal' );
    remove_meta_box( 'dashboard_incoming_links',        'dashboard', 'normal' );
    remove_meta_box( 'dashboard_plugins',               'dashboard', 'normal' );
    remove_meta_box( 'dashboard_quick_press',           'dashboard', 'side' );
    remove_meta_box( 'dashboard_recent_drafts',         'dashboard', 'side' );
    remove_meta_box( 'dashboard_primary',               'dashboard', 'side' );
    remove_meta_box( 'dashboard_secondary',             'dashboard', 'side' );
    remove_meta_box( 'dashboard_site_health',           'dashboard', 'normal' );
    remove_meta_box( 'health_check_status',             'dashboard', 'normal' );

    // Add the Faithful Witness welcome widget.
    add_meta_box(
        'fw_welcome_widget',
        '<span style="color:#1a3a5c;">&#10022; Faithful Witness — Content Dashboard</span>',
        'fw_render_welcome_widget',
        'dashboard',
        'normal',
        'high'
    );
}
add_action( 'wp_dashboard_setup', 'fw_customize_dashboard' );

function fw_render_welcome_widget() {
    $add_resource = admin_url( 'post-new.php?post_type=fw_resource' );
    $add_event    = admin_url( 'post-new.php?post_type=fw_event' );
    $add_story    = admin_url( 'post-new.php' );
    $add_hit      = admin_url( 'post-new.php?post_type=fw_media_hit' );
    $add_partner  = admin_url( 'post-new.php?post_type=fw_organizing_group' );
    $hp_settings  = admin_url( 'admin.php?page=faithfulwitness-hub' );
    ?>
    <style>
    #fw_welcome_widget .fw-dash-section { margin-bottom: 1.5rem; }
    #fw_welcome_widget .fw-dash-section h3 { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #666; margin: 0 0 .6rem; border-bottom: 1px solid #eee; padding-bottom: .4rem; }
    #fw_welcome_widget .fw-dash-links { display: flex; flex-wrap: wrap; gap: .5rem; }
    #fw_welcome_widget .fw-dash-link { display: inline-block; padding: .4rem .9rem; border: 1.5px solid #1a3a5c; border-radius: 20px; color: #1a3a5c; font-size: 13px; font-weight: 600; text-decoration: none; }
    #fw_welcome_widget .fw-dash-link:hover { background: #1a3a5c; color: #fff; }
    #fw_welcome_widget .fw-dash-link--accent { border-color: #1a7a6b; color: #1a7a6b; }
    #fw_welcome_widget .fw-dash-link--accent:hover { background: #1a7a6b; color: #fff; }
    #fw_welcome_widget .fw-dash-note { font-size: 12px; color: #888; margin-top: .25rem; }
    </style>

    <div id="fw_welcome_widget">
        <div class="fw-dash-section">
            <h3>Add Content</h3>
            <div class="fw-dash-links">
                <a href="<?php echo esc_url( $add_resource ); ?>" class="fw-dash-link">+ Add a Resource</a>
                <a href="<?php echo esc_url( $add_event ); ?>" class="fw-dash-link">+ Add an Event</a>
                <a href="<?php echo esc_url( $add_story ); ?>" class="fw-dash-link">+ Add a Story</a>
                <a href="<?php echo esc_url( $add_hit ); ?>" class="fw-dash-link">+ Add a Media Hit</a>
                <a href="<?php echo esc_url( $add_partner ); ?>" class="fw-dash-link">+ Add a Network Partner</a>
            </div>
        </div>

        <div class="fw-dash-section">
            <h3>Update the Site</h3>
            <div class="fw-dash-links">
                <a href="<?php echo esc_url( $hp_settings ); ?>" class="fw-dash-link fw-dash-link--accent">Homepage Settings</a>
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=fw_organizing_group' ) ); ?>" class="fw-dash-link fw-dash-link--accent">Network Partner Map</a>
            </div>
        </div>

        <?php
        // Show recent items from all CPTs.
        $recent_posts = get_posts( [
            'post_type'      => [ 'fw_resource', 'fw_event', 'post', 'fw_media_hit', 'fw_organizing_group' ],
            'post_status'    => [ 'publish', 'draft', 'pending' ],
            'posts_per_page' => 8,
            'orderby'        => 'modified',
            'order'          => 'DESC',
        ] );
        if ( ! empty( $recent_posts ) ) : ?>
        <div class="fw-dash-section">
            <h3>Recently Updated</h3>
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <thead>
                    <tr style="text-align:left;color:#666;border-bottom:1px solid #eee;">
                        <th style="padding:.35rem .5rem;">Title</th>
                        <th style="padding:.35rem .5rem;">Type</th>
                        <th style="padding:.35rem .5rem;">Status</th>
                        <th style="padding:.35rem .5rem;">Modified</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ( $recent_posts as $rp ) :
                    $type_labels = [
                        'fw_resource'         => 'Resource',
                        'fw_event'            => 'Event',
                        'post'                => 'Story',
                        'fw_media_hit'        => 'Media Hit',
                        'fw_organizing_group' => 'Partner',
                    ];
                    $label = $type_labels[ $rp->post_type ] ?? $rp->post_type;
                    $status_colors = [
                        'publish' => '#1a7a6b',
                        'draft'   => '#e07b00',
                        'pending' => '#c0392b',
                    ];
                    $sc = $status_colors[ $rp->post_status ] ?? '#888';
                    ?>
                    <tr style="border-bottom:1px solid #f0f0f0;">
                        <td style="padding:.35rem .5rem;">
                            <a href="<?php echo esc_url( get_edit_post_link( $rp->ID ) ); ?>" style="color:#1a3a5c;text-decoration:none;font-weight:600;">
                                <?php echo esc_html( wp_trim_words( $rp->post_title, 6, '…' ) ); ?>
                            </a>
                        </td>
                        <td style="padding:.35rem .5rem;color:#888;"><?php echo esc_html( $label ); ?></td>
                        <td style="padding:.35rem .5rem;color:<?php echo esc_attr( $sc ); ?>;font-weight:600;"><?php echo esc_html( ucfirst( $rp->post_status ) ); ?></td>
                        <td style="padding:.35rem .5rem;color:#aaa;"><?php echo esc_html( human_time_diff( strtotime( $rp->post_modified ) ) . ' ago' ); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <div class="fw-dash-section">
            <h3>Need Help?</h3>
            <p class="fw-dash-note">
                Admin Guides are private pages in the CMS:
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=page&post_status=private' ) ); ?>" style="color:#1a3a5c;">
                    View Admin Guides (look for the lock icon)
                </a>
            </p>
        </div>
    </div>
    <?php
}

// ============================================================
// RENAME "POSTS" → "STORIES" IN ADMIN SIDEBAR
// ============================================================

function fw_rename_posts_to_stories() {
    global $menu, $submenu;

    // Rename top-level "Posts" entry.
    if ( isset( $menu[5][0] ) ) {
        $menu[5][0] = __( 'Stories', 'faithfulwitness' );
    }

    // Rename submenu items.
    if ( isset( $submenu['edit.php'] ) ) {
        foreach ( $submenu['edit.php'] as &$item ) {
            if ( $item[0] === 'Posts' || $item[0] === 'All Posts' ) {
                $item[0] = __( 'All Stories', 'faithfulwitness' );
            } elseif ( $item[0] === 'Add New Post' || $item[0] === 'Add New' ) {
                $item[0] = __( 'Add New Story', 'faithfulwitness' );
            }
        }
        unset( $item );
    }
}
add_action( 'admin_menu', 'fw_rename_posts_to_stories', 999 );

// ============================================================
// 7A — HIDE ADMIN MENU ITEMS FOR CONTENT MANAGER
// ============================================================

function fw_restrict_admin_menu() {
    if ( current_user_can( 'manage_options' ) ) return; // Admins see everything.

    $hidden_menus = [
        'themes.php',
        'plugins.php',
        'tools.php',
        'options-general.php',
        'options.php',
        'users.php',
        'edit-comments.php',
        // Third-party plugin menus (hidden if installed)
        'wp-optimize',
        'updraftplus',
        'wpcf7',
        'rank-math',
        'wpforms-overview',
    ];

    foreach ( $hidden_menus as $slug ) {
        remove_menu_page( $slug );
    }

    // Also hide Comments from the top-level post edit pages submenu.
    remove_submenu_page( 'options-general.php', 'options-discussion.php' );
}
add_action( 'admin_menu', 'fw_restrict_admin_menu', 999 );

// Prevent non-admins from accessing hidden pages directly.
function fw_restrict_admin_access() {
    if ( current_user_can( 'manage_options' ) ) return;

    $restricted = [ 'themes.php', 'plugins.php', 'tools.php', 'options-general.php', 'users.php' ];
    $page       = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
    $file       = basename( isset( $_SERVER['PHP_SELF'] ) ? sanitize_text_field( wp_unslash( $_SERVER['PHP_SELF'] ) ) : '' );

    if ( in_array( $file, $restricted, true ) && ! in_array( $file, [ 'profile.php', 'admin-ajax.php' ], true ) ) {
        wp_die( esc_html__( 'You do not have permission to access this page.', 'faithfulwitness' ), 403 );
    }
}
add_action( 'admin_init', 'fw_restrict_admin_access' );

// ============================================================
// 7D — DUPLICATE POST FUNCTIONALITY
// ============================================================

/**
 * Add "Duplicate" action link to post list rows.
 */
function fw_duplicate_post_link( $actions, $post ) {
    $allowed_types = [ 'fw_resource', 'fw_event', 'post', 'fw_media_hit', 'fw_organizing_group', 'fw_initiative', 'page' ];

    if ( ! in_array( $post->post_type, $allowed_types, true ) ) {
        return $actions;
    }
    if ( ! current_user_can( 'edit_post', $post->ID ) ) {
        return $actions;
    }

    $nonce = wp_create_nonce( 'fw_duplicate_post_' . $post->ID );
    $url   = admin_url( 'admin.php?action=fw_duplicate_post&post_id=' . $post->ID . '&_wpnonce=' . $nonce );

    $actions['fw_duplicate'] = '<a href="' . esc_url( $url ) . '" title="' . esc_attr__( 'Duplicate this item', 'faithfulwitness' ) . '">' . esc_html__( 'Duplicate', 'faithfulwitness' ) . '</a>';

    return $actions;
}
add_filter( 'post_row_actions', 'fw_duplicate_post_link', 10, 2 );
add_filter( 'page_row_actions', 'fw_duplicate_post_link', 10, 2 );

/**
 * Handle the duplication request.
 */
function fw_handle_duplicate_post() {
    if ( ! isset( $_GET['action'] ) || $_GET['action'] !== 'fw_duplicate_post' ) return;
    if ( ! isset( $_GET['post_id'] ) || ! isset( $_GET['_wpnonce'] ) ) return;

    $post_id = absint( $_GET['post_id'] );

    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'fw_duplicate_post_' . $post_id ) ) {
        wp_die( esc_html__( 'Security check failed.', 'faithfulwitness' ) );
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        wp_die( esc_html__( 'You do not have permission to duplicate this item.', 'faithfulwitness' ) );
    }

    $original = get_post( $post_id );
    if ( ! $original ) wp_die( esc_html__( 'Post not found.', 'faithfulwitness' ) );

    // Create the duplicate.
    $new_id = wp_insert_post( [
        'post_title'    => $original->post_title . ' (Copy)',
        'post_content'  => $original->post_content,
        'post_excerpt'  => $original->post_excerpt,
        'post_type'     => $original->post_type,
        'post_status'   => 'draft',
        'post_author'   => get_current_user_id(),
        'post_parent'   => $original->post_parent,
        'menu_order'    => $original->menu_order,
        'comment_status'=> $original->comment_status,
        'ping_status'   => $original->ping_status,
    ], true );

    if ( is_wp_error( $new_id ) ) {
        wp_die( esc_html__( 'Failed to duplicate post.', 'faithfulwitness' ) );
    }

    // Copy all post meta.
    $meta_keys = get_post_meta( $post_id );
    if ( $meta_keys ) {
        foreach ( $meta_keys as $key => $values ) {
            if ( $key === '_wp_old_slug' ) continue;
            foreach ( $values as $value ) {
                add_post_meta( $new_id, $key, maybe_unserialize( $value ) );
            }
        }
    }

    // Copy taxonomy terms.
    $taxonomies = get_object_taxonomies( $original->post_type );
    foreach ( $taxonomies as $taxonomy ) {
        $terms = wp_get_object_terms( $post_id, $taxonomy, [ 'fields' => 'slugs' ] );
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            wp_set_object_terms( $new_id, $terms, $taxonomy );
        }
    }

    // Also copy page template if applicable.
    $template = get_post_meta( $post_id, '_wp_page_template', true );
    if ( $template ) {
        update_post_meta( $new_id, '_wp_page_template', $template );
    }

    // Redirect to edit the new draft.
    wp_safe_redirect( admin_url( 'post.php?action=edit&post=' . $new_id ) );
    exit;
}
add_action( 'admin_action_fw_duplicate_post', 'fw_handle_duplicate_post' );

// ============================================================
// 7E — EDITOR TITLE PLACEHOLDER TEXT
// ============================================================

function fw_editor_title_placeholder( $placeholder, $post ) {
    $map = [
        'fw_resource'         => __( 'Resource Title — e.g. Know Your Rights Guide', 'faithfulwitness' ),
        'fw_event'            => __( 'Event Name — e.g. KYR Training Chicago', 'faithfulwitness' ),
        'post'                => __( 'Story Title — e.g. What We Saw at the Courthouse', 'faithfulwitness' ),
        'fw_media_hit'        => __( 'Outlet Name: Article Headline', 'faithfulwitness' ),
        'fw_organizing_group' => __( 'Organization Name', 'faithfulwitness' ),
        'fw_initiative'       => __( 'Initiative / Campaign Name', 'faithfulwitness' ),
    ];

    return $map[ $post->post_type ] ?? $placeholder;
}
add_filter( 'enter_title_here', 'fw_editor_title_placeholder', 10, 2 );

// ============================================================
// ADMIN BAR — remove unnecessary items for non-admins
// ============================================================

function fw_clean_admin_bar( $wp_admin_bar ) {
    if ( current_user_can( 'manage_options' ) ) return;

    $wp_admin_bar->remove_node( 'wp-logo' );
    $wp_admin_bar->remove_node( 'customize' );
    $wp_admin_bar->remove_node( 'themes' );
    $wp_admin_bar->remove_node( 'updates' );
}
add_action( 'admin_bar_menu', 'fw_clean_admin_bar', 999 );

// ============================================================
// SECURITY REMINDER — wp-config.php constants
// Shows a dismissible notice to admins about recommended config.
// ============================================================

function fw_wpconfig_security_notice() {
    if ( ! current_user_can( 'manage_options' ) ) return;
    if ( get_option( 'fw_wpconfig_notice_dismissed' ) ) return;

    $defined_file_edit = defined( 'DISALLOW_FILE_EDIT' ) && DISALLOW_FILE_EDIT;
    $defined_revisions = defined( 'WP_POST_REVISIONS' );

    if ( $defined_file_edit && $defined_revisions ) {
        return; // Both already set — no notice needed.
    }

    $items = [];
    if ( ! $defined_file_edit ) {
        $items[] = '<code>define(\'DISALLOW_FILE_EDIT\', true);</code> — prevents file editing from the dashboard';
    }
    if ( ! $defined_revisions ) {
        $items[] = '<code>define(\'WP_POST_REVISIONS\', 10);</code> — limits stored revisions to the last 10';
    }

    ?>
    <div class="notice notice-info is-dismissible" id="fw-wpconfig-notice">
        <p><strong>Faithful Witness — Recommended wp-config.php additions:</strong></p>
        <p>Add the following lines to your <code>wp-config.php</code> before the <em>"That's all, stop editing!"</em> line:</p>
        <ul style="margin-left:1.5rem;list-style:disc;">
            <?php foreach ( $items as $item ) echo '<li>' . $item . '</li>'; // phpcs:ignore ?>
        </ul>
        <p><button type="button" class="notice-dismiss" onclick="fwDismissConfigNotice(this)"><span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'faithfulwitness' ); ?></span></button></p>
    </div>
    <script>
    function fwDismissConfigNotice(btn) {
        btn.closest('.notice').style.display = 'none';
        fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=fw_dismiss_wpconfig_notice&nonce=<?php echo esc_js( wp_create_nonce( 'fw_dismiss_wpconfig' ) ); ?>'
        });
    }
    </script>
    <?php
}
add_action( 'admin_notices', 'fw_wpconfig_security_notice' );

function fw_ajax_dismiss_wpconfig_notice() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'fw_dismiss_wpconfig' ) ) {
        wp_send_json_error();
    }
    update_option( 'fw_wpconfig_notice_dismissed', true );
    wp_send_json_success();
}
add_action( 'wp_ajax_fw_dismiss_wpconfig_notice', 'fw_ajax_dismiss_wpconfig_notice' );
