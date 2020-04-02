<?php
if ( ! defined('ABSPATH')) exit;  // if direct access




add_action('job_bm_settings_tabs_content_job_submit', 'job_bm_settings_tabs_job_submit_job_report', 20);

if(!function_exists('job_bm_settings_tabs_job_submit_job_report')) {
    function job_bm_settings_tabs_job_submit_job_report($tab){

        $settings_tabs_field = new settings_tabs_field();

        $job_bm_report_submit_recaptcha = get_option('job_bm_report_submit_recaptcha');



        ?>
        <div class="section">
            <div class="section-title"><?php echo __('Job report settings', 'job-board-manager-locations'); ?></div>
            <p class="description section-description"><?php echo __('Choose option for job report.', 'job-board-manager-locations'); ?></p>

            <?php

            $args = array(
                'id'		=> 'job_bm_report_submit_recaptcha',
                //'parent'		=> '',
                'title'		=> __('reCAPTCHA enable','job-board-manager-locations'),
                'details'	=> __('Enable reCAPTCHA to protect spam on location submit form.','job-board-manager-locations'),
                'type'		=> 'select',
                //'multiple'		=> true,
                'value'		=> $job_bm_report_submit_recaptcha,
                'default'		=> 'yes',
                'args'		=> array( 'yes'=>__('Yes','job-board-manager-locations'), 'no'=>__('No','job-board-manager-locations'),),
            );

            $settings_tabs_field->generate_field($args);





            ?>


        </div>
        <?php


    }
}



add_action('job_bm_settings_save', 'job_bm_settings_save_job_report', 20);

if(!function_exists('job_bm_settings_save_job_report')) {
    function job_bm_settings_save_job_report($tab){


        $job_bm_report_submit_recaptcha = isset($_POST['job_bm_report_submit_recaptcha']) ? sanitize_text_field($_POST['job_bm_report_submit_recaptcha']) : '';
        update_option('job_bm_report_submit_recaptcha', $job_bm_report_submit_recaptcha);



    }
}









