<?php
/**
 * Native WordPress Meta Boxes (no ACF required)
 *
 * Organizing Group fields: city, state_abbr, lat, lng, contact_email,
 *   contact_phone, website_url, instagram_url, facebook_url, twitter_url
 *
 * Resource fields: resource_url, resource_file_id, related_initiative_id
 *
 * Event fields: event_date, event_end_date, event_time, event_end_time,
 *   event_location_name, event_address, event_city, event_state,
 *   event_virtual, event_virtual_link, event_registration_link,
 *   event_scope (local|national), related_organizing_group_id
 *
 * Initiative fields: initiative_status, initiative_cta_label, initiative_cta_url
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ============================================================
// REGISTER META BOXES
// ============================================================
function fw_register_meta_boxes() {

    // --- Organizing Group ---
    add_meta_box(
        'fw_organizing_group_details',
        __( 'Organizing Group Details', 'faithfulwitness' ),
        'fw_render_organizing_group_meta_box',
        'fw_organizing_group',
        'normal',
        'high'
    );

    // --- Resource ---
    add_meta_box(
        'fw_resource_details',
        __( 'Resource Details', 'faithfulwitness' ),
        'fw_render_resource_meta_box',
        'fw_resource',
        'normal',
        'high'
    );

    // --- Event ---
    add_meta_box(
        'fw_event_details',
        __( 'Event Details', 'faithfulwitness' ),
        'fw_render_event_meta_box',
        'fw_event',
        'normal',
        'high'
    );

    // --- Media Hit ---
    add_meta_box(
        'fw_media_hit_details',
        __( 'Media Hit Details', 'faithfulwitness' ),
        'fw_render_media_hit_meta_box',
        'fw_media_hit',
        'normal',
        'high'
    );

    // --- Initiative ---
    add_meta_box(
        'fw_initiative_details',
        __( 'Initiative Details', 'faithfulwitness' ),
        'fw_render_initiative_meta_box',
        'fw_initiative',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'fw_register_meta_boxes' );

// ============================================================
// ORGANIZING GROUP META BOX
// ============================================================
function fw_render_organizing_group_meta_box( $post ) {
    wp_nonce_field( 'fw_organizing_group_meta', 'fw_organizing_group_nonce' );
    $fields = [
        'fw_city'          => [ 'label' => 'City',             'type' => 'text' ],
        'fw_state_abbr'    => [ 'label' => 'State (Abbr.)',    'type' => 'text', 'placeholder' => 'e.g. CA' ],
        'fw_lat'           => [ 'label' => 'Latitude',         'type' => 'text', 'placeholder' => 'e.g. 34.0522' ],
        'fw_lng'           => [ 'label' => 'Longitude',        'type' => 'text', 'placeholder' => 'e.g. -118.2437' ],
        'fw_contact_email' => [ 'label' => 'Contact Email',    'type' => 'email' ],
        'fw_contact_phone' => [ 'label' => 'Contact Phone',    'type' => 'text' ],
        'fw_website_url'   => [ 'label' => 'Website URL',      'type' => 'url' ],
        'fw_instagram_url' => [ 'label' => 'Instagram URL',    'type' => 'url' ],
        'fw_facebook_url'  => [ 'label' => 'Facebook URL',     'type' => 'url' ],
        'fw_twitter_url'   => [ 'label' => 'Twitter / X URL',  'type' => 'url' ],
    ];
    fw_render_fields( $post->ID, $fields );

    // Org type select (for map pin color-coding)
    $org_type = get_post_meta( $post->ID, 'fw_org_type', true ) ?: 'national-partner';
    echo '<p><label style="font-weight:600;display:block;margin-bottom:4px;">Organization Type</label>';
    echo '<select name="fw_org_type" style="width:100%">';
    foreach ( [
        'national-partner' => 'National Partner',
        'local-church'     => 'Local Church',
        'organizing-group' => 'Organizing Group',
    ] as $val => $label ) {
        printf( '<option value="%s"%s>%s</option>', esc_attr( $val ), selected( $org_type, $val, false ), esc_html( $label ) );
    }
    echo '</select></p>';
}

// ============================================================
// MEDIA HIT META BOX
// ============================================================
function fw_render_media_hit_meta_box( $post ) {
    wp_nonce_field( 'fw_media_hit_meta', 'fw_media_hit_nonce' );
    $fields = [
        'fw_media_outlet'          => [ 'label' => 'Outlet Name',       'type' => 'text', 'placeholder' => 'e.g. Christianity Today' ],
        'fw_media_url'             => [ 'label' => 'Article URL',        'type' => 'url' ],
        'fw_media_pub_date'        => [ 'label' => 'Publication Date',   'type' => 'date' ],
        'fw_media_pull_quote'      => [ 'label' => 'Pull Quote / Excerpt','type' => 'text' ],
        'fw_media_outlet_logo_url' => [ 'label' => 'Outlet Logo URL',    'type' => 'url', 'placeholder' => 'https://…' ],
    ];
    fw_render_fields( $post->ID, $fields );
}

// ============================================================
// RESOURCE META BOX
// ============================================================
function fw_render_resource_meta_box( $post ) {
    wp_nonce_field( 'fw_resource_meta', 'fw_resource_nonce' );

    // Featured checkbox
    $featured = get_post_meta( $post->ID, 'fw_resource_featured', true );
    echo '<p><label style="font-weight:600;"><input type="checkbox" name="fw_resource_featured" value="1"' . checked( $featured, '1', false ) . ' style="margin-right:6px;">' . esc_html__( 'Feature this resource at the top of the Resource Library', 'faithfulwitness' ) . '</label></p>';

    $fields = [
        'fw_resource_url'       => [ 'label' => 'External Link (if applicable)', 'type' => 'url' ],
        'fw_resource_file_id'   => [ 'label' => 'Downloadable File (Media ID)', 'type' => 'text', 'placeholder' => 'Attach via Media Library' ],
    ];
    fw_render_fields( $post->ID, $fields );

    // Related initiative select
    $initiatives = get_posts( [ 'post_type' => 'fw_initiative', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC' ] );
    $current = get_post_meta( $post->ID, 'fw_related_initiative_id', true );
    echo '<p><label style="font-weight:600;display:block;margin-bottom:4px;">Related Initiative</label>';
    echo '<select name="fw_related_initiative_id" style="width:100%"><option value="">— None —</option>';
    foreach ( $initiatives as $init ) {
        printf( '<option value="%d"%s>%s</option>', $init->ID, selected( $current, $init->ID, false ), esc_html( $init->post_title ) );
    }
    echo '</select></p>';
}

// ============================================================
// EVENT META BOX
// ============================================================
function fw_render_event_meta_box( $post ) {
    wp_nonce_field( 'fw_event_meta', 'fw_event_nonce' );
    $scope    = get_post_meta( $post->ID, 'fw_event_scope', true ) ?: 'national';
    $virtual  = get_post_meta( $post->ID, 'fw_event_virtual', true );
    $fields = [
        'fw_event_date'              => [ 'label' => 'Start Date',          'type' => 'date' ],
        'fw_event_end_date'          => [ 'label' => 'End Date',            'type' => 'date' ],
        'fw_event_time'              => [ 'label' => 'Start Time',          'type' => 'time' ],
        'fw_event_end_time'          => [ 'label' => 'End Time',            'type' => 'time' ],
        'fw_event_location_name'     => [ 'label' => 'Location Name',       'type' => 'text', 'placeholder' => 'e.g. Community Center' ],
        'fw_event_address'           => [ 'label' => 'Street Address',      'type' => 'text' ],
        'fw_event_city'              => [ 'label' => 'City',                'type' => 'text' ],
        'fw_event_state'             => [ 'label' => 'State (Abbr.)',       'type' => 'text', 'placeholder' => 'e.g. TX' ],
        'fw_event_registration_link' => [ 'label' => 'Registration Link',   'type' => 'url' ],
    ];
    fw_render_fields( $post->ID, $fields );

    // Event scope radio
    echo '<p><label style="font-weight:600;display:block;margin-bottom:4px;">Event Scope</label>';
    echo '<label style="margin-right:16px;"><input type="radio" name="fw_event_scope" value="national"' . checked( $scope, 'national', false ) . '> National</label>';
    echo '<label><input type="radio" name="fw_event_scope" value="local"' . checked( $scope, 'local', false ) . '> Local</label></p>';

    // Virtual toggle
    echo '<p><label style="font-weight:600;display:block;margin-bottom:4px;"><input type="checkbox" name="fw_event_virtual" value="1"' . checked( $virtual, '1', false ) . ' style="margin-right:6px;">Virtual / Online Event</label></p>';
    $virtual_link = get_post_meta( $post->ID, 'fw_event_virtual_link', true );
    echo '<p><label style="font-weight:600;display:block;margin-bottom:4px;">Virtual Event Link</label>';
    echo '<input type="url" name="fw_event_virtual_link" value="' . esc_attr( $virtual_link ) . '" style="width:100%" placeholder="https://zoom.us/..."></p>';

    // Related organizing group
    $groups  = get_posts( [ 'post_type' => 'fw_organizing_group', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC' ] );
    $current = get_post_meta( $post->ID, 'fw_related_organizing_group_id', true );
    echo '<p><label style="font-weight:600;display:block;margin-bottom:4px;">Related Local Organizing Group</label>';
    echo '<select name="fw_related_organizing_group_id" style="width:100%"><option value="">— None —</option>';
    foreach ( $groups as $group ) {
        printf( '<option value="%d"%s>%s</option>', $group->ID, selected( $current, $group->ID, false ), esc_html( $group->post_title ) );
    }
    echo '</select></p>';
}

// ============================================================
// INITIATIVE META BOX
// ============================================================
function fw_render_initiative_meta_box( $post ) {
    wp_nonce_field( 'fw_initiative_meta', 'fw_initiative_nonce' );
    $status = get_post_meta( $post->ID, 'fw_initiative_status', true ) ?: 'active';
    echo '<p><label style="font-weight:600;display:block;margin-bottom:4px;">Status</label>';
    echo '<select name="fw_initiative_status" style="width:100%">';
    foreach ( [ 'active' => 'Active', 'ongoing' => 'Ongoing', 'completed' => 'Completed', 'paused' => 'Paused' ] as $val => $label ) {
        printf( '<option value="%s"%s>%s</option>', $val, selected( $status, $val, false ), $label );
    }
    echo '</select></p>';

    $fields = [
        'fw_initiative_cta_label' => [ 'label' => 'CTA Button Label', 'type' => 'text', 'placeholder' => 'e.g. Take Action' ],
        'fw_initiative_cta_url'   => [ 'label' => 'CTA Button URL',   'type' => 'url' ],
    ];
    fw_render_fields( $post->ID, $fields );
}

// ============================================================
// HELPER: render a group of fields
// ============================================================
function fw_render_fields( $post_id, $fields ) {
    foreach ( $fields as $key => $config ) {
        $value = get_post_meta( $post_id, $key, true );
        $type  = $config['type'] ?? 'text';
        $ph    = $config['placeholder'] ?? '';
        echo '<p>';
        printf( '<label for="%s" style="font-weight:600;display:block;margin-bottom:4px;">%s</label>', esc_attr( $key ), esc_html( $config['label'] ) );
        printf( '<input type="%s" id="%s" name="%s" value="%s" placeholder="%s" style="width:100%%">',
            esc_attr( $type ), esc_attr( $key ), esc_attr( $key ), esc_attr( $value ), esc_attr( $ph ) );
        echo '</p>';
    }
}

// ============================================================
// SAVE META
// ============================================================
function fw_save_meta_boxes( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    // Media Hit
    if ( isset( $_POST['fw_media_hit_nonce'] ) &&
         wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fw_media_hit_nonce'] ) ), 'fw_media_hit_meta' ) ) {
        $hit_fields = [ 'fw_media_outlet', 'fw_media_url', 'fw_media_pub_date', 'fw_media_pull_quote', 'fw_media_outlet_logo_url' ];
        foreach ( $hit_fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
            }
        }
    }

    // Organizing Group
    if ( isset( $_POST['fw_organizing_group_nonce'] ) &&
         wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fw_organizing_group_nonce'] ) ), 'fw_organizing_group_meta' ) ) {
        $og_fields = [ 'fw_city', 'fw_state_abbr', 'fw_lat', 'fw_lng', 'fw_contact_email',
                       'fw_contact_phone', 'fw_website_url', 'fw_instagram_url', 'fw_facebook_url', 'fw_twitter_url', 'fw_org_type' ];
        foreach ( $og_fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
            }
        }
    }

    // Resource
    if ( isset( $_POST['fw_resource_nonce'] ) &&
         wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fw_resource_nonce'] ) ), 'fw_resource_meta' ) ) {
        $resource_fields = [ 'fw_resource_url', 'fw_resource_file_id', 'fw_related_initiative_id' ];
        foreach ( $resource_fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
            }
        }
        update_post_meta( $post_id, 'fw_resource_featured', isset( $_POST['fw_resource_featured'] ) ? '1' : '0' );
    }

    // Event
    if ( isset( $_POST['fw_event_nonce'] ) &&
         wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fw_event_nonce'] ) ), 'fw_event_meta' ) ) {
        $event_fields = [ 'fw_event_date', 'fw_event_end_date', 'fw_event_time', 'fw_event_end_time',
                          'fw_event_location_name', 'fw_event_address', 'fw_event_city', 'fw_event_state',
                          'fw_event_registration_link', 'fw_event_scope', 'fw_event_virtual_link',
                          'fw_related_organizing_group_id' ];
        foreach ( $event_fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
            }
        }
        update_post_meta( $post_id, 'fw_event_virtual', isset( $_POST['fw_event_virtual'] ) ? '1' : '0' );
    }

    // Initiative
    if ( isset( $_POST['fw_initiative_nonce'] ) &&
         wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fw_initiative_nonce'] ) ), 'fw_initiative_meta' ) ) {
        $init_fields = [ 'fw_initiative_status', 'fw_initiative_cta_label', 'fw_initiative_cta_url' ];
        foreach ( $init_fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
            }
        }
    }
}
add_action( 'save_post', 'fw_save_meta_boxes' );
