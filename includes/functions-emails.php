<?php
if ( ! defined('ABSPATH')) exit;  // if direct access


add_filter('job_bm_email_templates_data','job_bm_email_templates_data_report_job');

function job_bm_email_templates_data_report_job($templates_data){

    $templates_data_html = array();
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');


    include( job_bm_report_plugin_dir.'templates/emails-templates/report_submitted.php');


    $templates_data['report_submitted'] = array(

            'name'=>__('Submit report', 'job-board-manager'),
            'description'=>__('Notification email for when report submitted.', 'job-board-manager'),
            'subject'=>__('Report on job - {site_url}', 'job-board-manager'),
            'html'=>$templates_data_html['report_submitted'],
            'email_to'=>$admin_email,
            'email_from'=>$admin_email,
            'email_from_name'=> $site_name,
            'enable'=> 'yes',
    );


    return $templates_data;

}


add_filter('job_bm_emails_templates_param','job_bm_emails_templates_param_report_job');

function job_bm_emails_templates_param_report_job($parameters){


    $parameters['report_submitted'] = array(

        'parameters'=> array(
            '{site_url}'=>'Website Home URL',
            '{site_description}'=>'Website tagline',
            '{site_logo_url}'=>'Logo url',

            '{job_id}'  => 'Job ID',
            '{job_title}'  => 'Job Title',
            '{job_url}'  => 'Job post URL',
            '{job_edit_url}'  => 'Job admin post edit URL',
            '{job_author_id}'  => 'Job post author ID',
            '{job_author_name}'  => 'Job post author name',

            '{current_user_id}'  => 'Logged-in user ID',
            '{current_user_name}'  => 'Logged-in user display name',
            '{current_user_avatar}'  =>'Logged-in user avatar',
        ),
    );


    return $parameters;

}
