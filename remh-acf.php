<?php
/**
 * Plugin Name: REMH ACF
 * Plugin URI: https://www.osregroup.com
 * Description: This is a custom plugin to handle specific functionalities between ACF, WS Forms, and Use Your Drive.
 * Version: 1.0
 * Author: Matthew Carpenter
 * Author URI: https://www.osregroup.com
 * License: Copyright © 2023 Matthew Carpenter. All rights reserved.
 */

// Test Environment C:\xampp\htdocs\REMH\wp-content\plugins\remh-acf
// C:\xampp\htdocs\REMH\wp-content\plugins\remh-acf-v2 for new segmented code and file



// Register custom REST API route
add_action('rest_api_init', function () {
    register_rest_route('remh-acf/v1', '/update-acf-field/', array(
        'methods' => 'POST',
        'callback' => 'update_acf_field_via_api',
        'permission_callback' => function() {
            return current_user_can('edit_posts');
        }
    ));
});

function update_acf_field_via_api($request) {

// =========================
// Random String Reference Number Generator
// =========================
function generate_random_reference_number() {
    $characters = '123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < 8; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}



// =========================
// Extract Forn ID form WS Shortcode
// =========================
function extract_form_id_from_shortcode($content) {
    preg_match('/\[ws_form id="(\d+)"\]/', $content, $matches);
    return isset($matches[1]) ? $matches[1] : null;
}

// Get the post content
$content = get_the_content();

// Extract form ID from the content
$form_id = extract_form_id_from_shortcode($content);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form ID matches
    if ($_POST['form_id'] == $form_id) {
        $reference_number = generate_random_reference_number();

        // Here, you can add the logic to store the reference number or send it via email or any other action you want to perform

        // No redirection, simply continue with the rest of your logic or end the script
    }
}



// =========================
// Random String Reference Number Association
// =========================

function handle_acf_fields($form_id, $post_id) {
    $random_string = generate_random_reference_number($conn);

    // Determine the correct ACF field based on the form ID
    $acf_field = '';
    switch ($form_id) {
        case '17':
            $acf_field = 'external_maintenance_request_ref_no';
            break;
        case '18':
            $acf_field = 'common_area_maintenance_request_ref_no';
            break;
        case '19':
            $acf_field = 'rooms_maintenance_request_ref_no';
            break;
        case '16':
            $acf_field = 'external_damages_ref_no';
            break;
        case '15':
            $acf_field = 'common_area_damages_ref_no';
            break;            
        case '11':
            $acf_field = 'room_damages_ref_no';
            break;            
    }

    if ($acf_field) {
        update_field($acf_field, $random_string, $post_id);
    }

    return $random_string;  // Return the generated random string
}



// =========================
// EXTERNAL MAINTENANCE REQUEST
// =========================

/**
 * Generates a Use Your Drive shortcode for external maintenance requests.
 * Linked to WS Form #17
 */

function generate_useyourdrive_shortcode_external_maintenance() {
    $external_maintenance_value = get_field('external_maintenance'); // Get the ACF field value from the 'Google Shared Drive' field group for the specific Google Drive directory, this data being the last part of the URL string
    if ($external_maintenance_value) {
        $shortcode_output = do_shortcode('[useyourdrive mode="upload" dir="' . $external_maintenance_value . '" account="105226709689929618359" viewrole="administrator|editor|author|contributor|subscriber|guest" upload="1" editdescription="1" editdescriptionrole="all" upload_folder="0" upload_filename="%yyyy-mm-dd% %queue_index% - %acf_post_property_code_upload% - %acf_post_external_maintenance_request_ref_no%%file_extension%" downloadrole="none" upload_auto_start="0" uploadext="jpg|png"]');
        // Wrap the output in a container with the class and data attribute
        return '<div class="useyourdrive-container" data-maintenance-type="external_maintenance">' . $shortcode_output . '</div>';
    }
    return 'Error: Unable to generate Use Your Drive shortcode for external maintenance.';
}


// =========================
// COMMON AREA MAINTENANCE REQUEST
// =========================

/**
 * Generates a Use Your Drive shortcode for common area maintenance requests.
 * Linked to WS Form #18
 */

function generate_useyourdrive_shortcode_common_maintenance() {
    // Update the get_field function call to use 'common_area_maintenance'
    $common_maintenance_value = get_field('common_area_maintenance'); // Get the ACF field value from the 'Google Shared Drive' field group for the specific Google Drive directory, this data being the last part of the URL string

    if ($common_maintenance_value) {
        $shortcode_output = do_shortcode('[useyourdrive mode="upload" dir="' . $common_maintenance_value . '" account="105226709689929618359" viewrole="administrator|editor|author|contributor|subscriber|guest" upload="1" editdescription="1" editdescriptionrole="all" upload_folder="0" upload_filename="%yyyy-mm-dd% %queue_index% - %acf_post_property_code_upload% - %acf_post_common_area_maintenance_request_ref_no%%file_extension%" downloadrole="none" upload_auto_start="0" uploadext="jpg|png"]');
        // Wrap the output in a container with the class and data attribute
        return '<div class="useyourdrive-container" data-maintenance-type="common_maintenance">' . $shortcode_output . '</div>';
    }
    return 'Error: Unable to generate Use Your Drive shortcode for common area maintenance.';
}


// =========================
// ROOM MAINTENANCE REQUEST
// =========================

/**
 * Generates a Use Your Drive shortcode for room maintenance requests.
 * Linked to WS Form #19
 */

function generate_useyourdrive_shortcode_room_maintenance() {
    $room_maintenance_value = get_field('room_maintenance'); // Get the ACF field value from the 'Google Shared Drive' field group for the specific Google Drive directory, this data being the last part of the URL string
    if ($room_maintenance_value) {
        $shortcode_output = do_shortcode('[useyourdrive mode="upload" dir="' . $room_maintenance_value . '" account="105226709689929618359" viewrole="administrator|editor|author|contributor|subscriber|guest" upload="1" editdescription="1" editdescriptionrole="all" upload_folder="0" upload_filename="%yyyy-mm-dd% %queue_index% - %acf_post_property_code_upload% - %acf_post_rooms_maintenance_request_ref_no%%file_extension%" downloadrole="none" upload_auto_start="0" uploadext="jpg|png"]');
        // Wrap the output in a container with the class and data attribute
        return '<div class="useyourdrive-container" data-maintenance-type="room_maintenance">' . $shortcode_output . '</div>';
    }
    return 'Error: Unable to generate Use Your Drive shortcode for room maintenance.';
}


// =========================
// EXTERNAL DAMAGES REPORT
// =========================

/**
 * Generates a Use Your Drive shortcode for external damages.
 * Linked to WS Form #16
 */

function generate_useyourdrive_shortcode_damages_external() {
    $damages_external_value = get_field('damages_external'); // Get the ACF field value from the 'Google Shared Drive' field group for the specific Google Drive directory, this data being the last part of the URL string
    if ($damages_external_value) {
        $shortcode_output = do_shortcode('[useyourdrive mode="upload" dir="' . $damages_external_value . '" account="105226709689929618359" viewrole="administrator|editor|author|contributor|subscriber|guest" upload="1" editdescription="1" editdescriptionrole="all" upload_folder="0" upload_filename="%yyyy-mm-dd% %queue_index% - %acf_post_property_code_upload% - %acf_post_external_damages_ref_no%%file_extension%" downloadrole="none" upload_auto_start="0" uploadext="jpg|png"]');
        // Wrap the output in a container with the class and data attribute
        return '<div class="useyourdrive-container" data-damage-type="damages_external">' . $shortcode_output . '</div>';
    }
    return 'Error: Unable to generate Use Your Drive shortcode for external damages.';
}


// =========================
// COMMON AREA DAMAGES REPORT
// =========================

/**
 * Generates a Use Your Drive shortcode for common area damages.
 * Linked to WS Form #15
 */

function generate_useyourdrive_shortcode_damages_common() {
    $damages_common_value = get_field('damages_common'); // Get the ACF field value from the 'Google Shared Drive' field group for the specific Google Drive directory, this data being the last part of the URL string
    if ($damages_common_value) {
        $shortcode_output = do_shortcode('[useyourdrive mode="upload" dir="' . $damages_common_value . '" account="105226709689929618359" viewrole="administrator|editor|author|contributor|subscriber|guest" upload="1" editdescription="1" editdescriptionrole="all" upload_folder="0" upload_filename="%yyyy-mm-dd% %queue_index% - %acf_post_property_code_upload% - %acf_post_common_area_damages_ref_no%%file_extension%" downloadrole="none" upload_auto_start="0" uploadext="jpg|png"]');
        // Wrap the output in a container with the class and data attribute
        return '<div class="useyourdrive-container" data-damage-type="damages_common">' . $shortcode_output . '</div>';
    }
    return 'Error: Unable to generate Use Your Drive shortcode for common damages.';
}


// =========================
// ROOMS DAMAGES REPORT
// =========================

/**
 * Generates a Use Your Drive shortcode for room damages.
 * Linked to WS Form #11
 */
function generate_useyourdrive_shortcode_damages_rooms() {
    $damages_rooms_value = get_field('damages_rooms'); // Get the ACF field value from the 'Google Shared Drive' field group for the specific Google Drive directory, this data being the last part of the URL string
    if ($damages_rooms_value) {
        $shortcode_output = do_shortcode('[useyourdrive mode="upload" dir="' . $damages_rooms_value . '" account="105226709689929618359" viewrole="administrator|editor|author|contributor|subscriber|guest" upload="1" editdescription="1" editdescriptionrole="all" upload_folder="0" upload_filename="%yyyy-mm-dd% %queue_index% - %acf_post_property_code_upload% - %acf_post_room_damages_ref_no%%file_extension%" downloadrole="none" upload_auto_start="0" uploadext="jpg|png"]');
        // Wrap the output in a container with the class and data attribute
        return '<div class="useyourdrive-container" data-damage-type="damages_rooms">' . $shortcode_output . '</div>';
    }
    return 'Error: Unable to generate Use Your Drive shortcode for rooms damages.';
}



// =========================
// Update ACF Field via AJAX
// =========================

function update_acf_field() {
    // Check if the value and form_id are set
    if (isset($_POST['value']) && isset($_POST['form_id'])) {
        $new_value = sanitize_text_field($_POST['value']);
        $form_id = sanitize_text_field($_POST['form_id']);  // Retrieve the form ID

        $random_string = handle_acf_fields($form_id, 'your_post_id');  // Use the form ID to update the ACF field and get the random string

        if ($random_string) {
            wp_send_json_success($random_string);  // Return the random string on success
        } else {
            wp_send_json_error('Failed to update field.');
        }
    } else {
        wp_send_json_error('Value or Form ID not set.');
    }
}
add_action('wp_ajax_update_acf_field', 'update_acf_field'); // If user is logged in
add_action('wp_ajax_nopriv_update_acf_field', 'update_acf_field'); // If user is not logged in



// =========================
// Register Shortcodes
// =========================

// This takes the Use Your Drive instructions from above and renders the plugin ahortcode to then load the plugin on the front end
// If the error presents "Unable to generate Use Your Drive shortcode for [specific instance] check that the ACF filed name is correct for the 'get_field' call

function register_my_shortcodes() {
    add_shortcode('generate_useyourdrive_shortcode_external_maintenance', 'generate_useyourdrive_shortcode_external_maintenance');
    add_shortcode('generate_useyourdrive_shortcode_common_maintenance', 'generate_useyourdrive_shortcode_common_maintenance');
    add_shortcode('generate_useyourdrive_shortcode_room_maintenance', 'generate_useyourdrive_shortcode_room_maintenance');
    add_shortcode('generate_useyourdrive_shortcode_damages_external', 'generate_useyourdrive_shortcode_damages_external');
    add_shortcode('generate_useyourdrive_shortcode_damages_common', 'generate_useyourdrive_shortcode_damages_common');
    add_shortcode('generate_useyourdrive_shortcode_damages_rooms', 'generate_useyourdrive_shortcode_damages_rooms');
}


 

add_action('init', 'register_my_shortcodes');


// =========================
// AJAX Handlers
// =========================

function handle_execute_useyourdrive_shortcode() {
    // Check nonce
    if (!check_ajax_referer('your_nonce_action', 'nonce', false)) {
        error_log('Nonce verification failed.');
        wp_die();
    }

// Maintenance Request Forms
    if (isset($_POST['maintenance_type'])) {
        $maintenance_type = sanitize_text_field($_POST['maintenance_type']);
        $shortcode_result = '';

        switch ($maintenance_type) {
            case 'external':
                $shortcode_result = generate_useyourdrive_shortcode_external_maintenance();
                break;
            case 'common':
                $shortcode_result = generate_useyourdrive_shortcode_common_maintenance();
                break;
            case 'rooms':
                $shortcode_result = generate_useyourdrive_shortcode_room_maintenance();
                break;
            default:
                echo 'Error: Invalid maintenance type.';
                wp_die();
        }

        echo $shortcode_result;
        wp_die();
    }

    // Damage Report Forms
    if (isset($_POST['damage_type'])) {
        $damage_type = sanitize_text_field($_POST['damage_type']);
        $shortcode_result = '';

        switch ($damage_type) {
            case 'external':
                $shortcode_result = generate_useyourdrive_shortcode_damages_external();
                break;
            case 'common':
                $shortcode_result = generate_useyourdrive_shortcode_damages_common();
                break;
            case 'rooms':
                $shortcode_result = generate_useyourdrive_shortcode_damages_rooms();
                break;
            default:
                echo 'Error: Invalid damage type.';
                wp_die();
        }

        echo $shortcode_result;
        wp_die();
    }
}


// =========================
// Nonce Handler
// =========================


function enqueue_rehm_acf() {
    // Adjust the path to point to where your JS file is located
    wp_enqueue_script('remh-acf-script', get_template_directory_uri() . '/js/rehm-acf.js', array('jquery'), '1.0.0', true);

    // Localize the script with nonce data
    $script_data_array = array(
        'ajax_nonce' => wp_create_nonce('your_nonce_action')
    );
    wp_localize_script('remh-acf-script', 'remh_acf_data', $script_data_array);
}
add_action('wp_enqueue_scripts', 'enqueue_rehm_acf');

add_action('wp_ajax_execute_useyourdrive_shortcode', 'handle_execute_useyourdrive_shortcode');
add_action('wp_ajax_nopriv_execute_useyourdrive_shortcode', 'handle_execute_useyourdrive_shortcode');


// =========================
// SANTISATION & ESCAPING OUT DATA
// =========================

// Sanitize the data before saving it
if (isset($_POST['cf_api_key'])) {
    update_option('cf_api_key', sanitize_text_field($_POST['cf_api_key']));
}

function get_cf_api_key_shortcode() {
    return esc_html(get_option('cf_api_key'));
}
add_shortcode('cf_api_key', 'get_cf_api_key_shortcode');

// When outputting any other data, use esc_html()
echo esc_html($some_data);

//*****************************
//* CODE OMITTED TEMPORARILY  *
//*****************************


// =========================
// ERROR HANDLING
// =========================

/**
 * Check for ACF Function Existence
 */
//if(function_exists('get_field')) {
    // use the function
//} else {
//    error_log('ACF plugin is not active.');
//}

/**
 * Check for Successful Database Updates
 */
// $updated = update_field($acf_field, $random_string, $post_id);
//if(!$updated) {
//    error_log("Failed to update ACF field: $acf_field for post ID: $post_id");
//}

/**
 * Handle Empty Shortcode Outputs
 */
//if(empty($shortcode_output)) {
//    error_log("Failed to generate shortcode for field: $acf_field");
//}

// =========================
// ERROR HANDLING for ACF and DATABASE UPDATES
// =========================

/**
 * Specific Error Checks
 */
// $update_result = update_field('field_name', 'value', $post_id);
//if ($update_result === false) {
    // Log or display a generic error message
//    error_log("Failed to update ACF field for post ID: " . $post_id);
//} elseif ($update_result === null) {
    // Handle cases where the value is the same as before (no update needed)
//    error_log("ACF field value is unchanged for post ID: " . $post_id);
//}

/**
 * Database Update Checks
 */
// $update_result = $wpdb->update('table_name', $data, $where);
//if ($update_result === false) {
    // Log the last database error for debugging
//    error_log("Database update failed: " . $wpdb->last_error);
//} elseif ($update_result == 0) {
//    // Handle cases where no rows were updated
//    error_log("No rows were updated in the database.");
//}
