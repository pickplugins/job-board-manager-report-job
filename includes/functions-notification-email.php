<?php
if ( ! defined('ABSPATH')) exit;  // if direct access


// Email notifications



/*
 * Send notification mail on trash job.
 * args:
 * $job_id => trashed post ID
 *
 *
 * */

function job_bm_report_job_submitted_send_email($job_id, $post_data){

    global $current_user;
    $admin_email = get_option('admin_email');

    $site_name = get_bloginfo('name');
    $site_description = get_bloginfo('description');
    $site_url = get_bloginfo('url');
    $job_bm_logo_url = get_option('job_bm_logo_url');
    $job_bm_logo_url = wp_get_attachment_url($job_bm_logo_url);
    $job_bm_from_email = get_option('job_bm_from_email', $admin_email);



    $email_data = array();
    $class_job_bm_emails = new class_job_bm_emails();
    $job_bm_email_templates_data_default = $class_job_bm_emails->job_bm_email_templates_data();
    $job_bm_email_templates_data = get_option('job_bm_email_templates_data', $job_bm_email_templates_data_default);


    $enable = isset($job_bm_email_templates_data['report_submitted']['enable']) ? $job_bm_email_templates_data['report_submitted']['enable'] : 'no';

    if($enable == 'yes'):

        $email_to = isset($job_bm_email_templates_data['report_submitted']['email_to']) ? $job_bm_email_templates_data['report_submitted']['email_to'] : '';
        $email_from_name = isset($job_bm_email_templates_data['report_submitted']['email_from_name']) ? $job_bm_email_templates_data['report_submitted']['email_from_name'] : $site_name;
        $email_from = isset($job_bm_email_templates_data['report_submitted']['email_from']) ? $job_bm_email_templates_data['report_submitted']['email_from'] : $job_bm_from_email;
        $email_subject = isset($job_bm_email_templates_data['report_submitted']['subject']) ? $job_bm_email_templates_data['report_submitted']['subject'] : '';
        $email_html = isset($job_bm_email_templates_data['report_submitted']['html']) ? $job_bm_email_templates_data['report_submitted']['html'] : '';

        $job_data = get_post($job_id);
        $job_author_data = get_user_by('ID', $job_data->post_author);
        $job_author_email = get_post_meta($job_id, 'job_bm_contact_email', true);

        $user_email = isset($post_data['user_email']) ? sanitize_email($post_data['user_email']) : '';

        $user_avatar = get_avatar( $current_user->ID, 60 );

        $user_avatar = ($current_user->ID != 0) ? get_avatar( $current_user->ID, 60 ) : get_avatar( $user_email, 60 );
        $user_name = isset($post_data['user_name']) ? sanitize_text_field($post_data['user_name']) : __('Anonymous','job-board-manager-report-job');
        $user_name = isset($current_user->display_name) ? $current_user->display_name : $user_name;

        $message = isset($post_data['message']) ? sanitize_text_field($post_data['message']) : '';
        $report_type = isset($post_data['report_type']) ? sanitize_text_field($post_data['report_type']) : '';


        $vars = array(
            '{site_name}'=> $site_name,
            '{site_description}' => $site_description,
            '{site_url}' => $site_url,
            '{site_logo_url}' => $job_bm_logo_url,

            '{job_id}'  => $job_id,
            '{job_title}'  => $job_data->post_title,
            '{job_content}'  => $job_data->post_content,
            '{job_url}'  => get_permalink($job_id),
            '{job_edit_url}'  => get_admin_url().'post.php?post='.$job_id.'&action=edit',
            '{job_author_id}'  => $job_data->author_id,
            '{job_author_name}'  => $job_author_data->display_name,
            '{job_author_avatar}'  => get_avatar( $job_data->author_id, 60 ),

            '{current_user_id}'  =>  $current_user->ID,
            '{current_user_name}'  => $user_name,
            '{current_user_avatar}'  => $user_avatar,

            '{message}'  => $message,
            '{report_type}'  => $report_type,

        );


        $email_data['email_to'] =  $job_author_email;
        $email_data['email_bcc'] =  $email_to;
        $email_data['email_from'] = $email_from ;
        $email_data['email_from_name'] = $email_from_name;
        $email_data['subject'] = strtr($email_subject, $vars);
        $email_data['html'] = strtr($email_html, $vars);
        $email_data['attachments'] = array();


        $status = $class_job_bm_emails->job_bm_send_email($email_data);

    endif;


}

add_action('job_bm_report_job_submitted', 'job_bm_report_job_submitted_send_email', 99, 2);












