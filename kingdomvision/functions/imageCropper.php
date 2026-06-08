<?php 


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(1);
/**
//  * --- SAFE COPY ON IMAGE EDIT ---
//  * Creates a brand-new image copy when editing (crop/rotate/scale)
//  * without modifying or shrinking the original file.
//  */
// /**
//  * Create a true copy of the image when saving edits (crop/scale/rotate)
//  * without modifying the original attachment or its file.
//  */
// add_filter('wp_save_image_editor_file', function ($saved, $filename, $image, $mime_type, $post_id) {

//     if (empty($post_id) || !is_object($image) || !method_exists($image, 'save')) {
//         return $saved;
//     }

//     $orig_path = get_attached_file($post_id);
//     if (!$orig_path || !file_exists($orig_path)) {
//         return $saved;
//     }

//     // Create a new filename
//     $info = pathinfo($orig_path);
//     $ext  = strtolower($info['extension'] ?? 'jpg');
//     $new_filename = sprintf('%s-copy-%d.%s', $info['filename'], time(), $ext);
//     $new_path = trailingslashit($info['dirname']) . $new_filename;

//     // Save new file
//     $result = $image->save($new_path, $mime_type);
//     if (is_wp_error($result) || !is_array($result) || empty($result['path'])) {
//         error_log('❌ Failed to save new image: ' . print_r($result, true));
//         return $saved;
//     }

//     // Register a new attachment
//     $attachment = [
//         'post_mime_type' => $mime_type,
//         'post_title'     => sanitize_file_name($new_filename),
//         'post_content'   => '',
//         'post_status'    => 'inherit',
//         'post_parent'    => (int) get_post_field('post_parent', $post_id),
//     ];
//     $new_id = wp_insert_attachment($attachment, $new_path);

//     require_once ABSPATH . 'wp-admin/includes/image.php';
//     $meta = wp_generate_attachment_metadata($new_id, $new_path);
//     wp_update_attachment_metadata($new_id, $meta);

//     // Copy alt/caption/description
//     update_post_meta($new_id, '_wp_attachment_image_alt', get_post_meta($post_id, '_wp_attachment_image_alt', true));
//     wp_update_post([
//         'ID' => $new_id,
//         'post_excerpt' => get_post_field('post_excerpt', $post_id),
//         'post_content' => get_post_field('post_content', $post_id),
//     ]);

//     // Store the new attachment ID temporarily
//     update_option('last_cropped_attachment_copy', $new_id);

//     // ✅ Return correct structure for success
//     return [
//         'path'      => $new_path,
//         'file'      => basename($new_path),
//         'width'     => $result['width'] ?? null,
//         'height'    => $result['height'] ?? null,
//         'mime-type' => $mime_type,
//     ];
// }, 10, 5);


// /**
//  * Prevent the core editor from replacing the original image metadata
//  * (this is what actually corrupts the parent image).
//  */
// add_filter('wp_update_attachment_metadata', function ($data, $post_id) {
//     $last_copy_id = get_option('last_cropped_attachment_copy');
//     if ($last_copy_id && (int) $post_id !== (int) $last_copy_id) {
//         // If this update is for the original image, cancel it
//         // Returning unchanged metadata stops WP from overwriting original
//         return wp_get_attachment_metadata($post_id);
//     }
//     return $data;
// }, 10, 2);

