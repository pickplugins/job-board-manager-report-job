<?php
if ( ! defined('ABSPATH')) exit;  // if direct access






add_action('job_bm_single_job_main', 'job_bm_single_job_main_report_job', 70);

if(!function_exists('job_bm_single_job_main_report_job')){
    function job_bm_single_job_main_report_job(){

        $job_id = get_the_ID();
        $user_id = get_current_user_id();
        $saved_user_ids = get_post_meta($job_id, 'saved_by_user_ids', true);
        $job_bm_report_submit_recaptcha		= get_option('job_bm_report_submit_recaptcha');
        $job_bm_reCAPTCHA_site_key		        = get_option('job_bm_reCAPTCHA_site_key');

        wp_enqueue_script('jquery-ui-accordion');

        $active_id = 999;

        global $current_user;


        ?>
        <h3><?php echo __('Report this job','job-board-manager-saved-jobs'); ?></h3>

        <div class="report-job apply-methods" id="report-job">
            <div class="report-title"> <?php echo sprintf(__('%s Submit report','job-board-manager'),'<i class="fas fa-exclamation-triangle"></i>')?></div>
            <div class="report-form">

                <?php


                if(isset($_POST['hidden_report_job']) && $_POST['hidden_report_job'] == 'Y'){

                    //var_dump($_POST);
                    $active_id = 0;

                    $error = new WP_Error();

                    if(empty($_POST['user_name'])){

                        $error->add( 'user_name', __( 'ERROR: Applicant name is empty.', 'job-board-manager' ) );
                    }

                    if(empty($_POST['user_email'])){

                        $error->add( 'user_email', __( 'ERROR: Email is empty.', 'job-board-manager' ) );
                    }

                    if(!is_email($_POST['user_email'])){

                        $error->add( 'user_email', __( 'ERROR: '.sanitize_email($_POST['user_email']).' is not valid email address.', 'job-board-manager' ) );
                    }



                    if($job_bm_report_submit_recaptcha == 'yes' && empty($_POST['g-recaptcha-response'])){

                        $error->add( 'recaptcha', __( 'ERROR: reCaptcha test failed', 'job-board-manager' ) );
                    }

                    $errors = apply_filters( 'job_bm_report_job_errors', $error, $_POST );


                    if ( !$error->has_errors() ) {

                        $user_name = isset($_POST['user_name']) ? sanitize_text_field($_POST['user_name']) : "";
                        $email = isset($_POST['user_email']) ? sanitize_text_field($_POST['user_email']) : "";
                        $post_content = isset($_POST['message']) ? wp_kses($_POST['message'], array()) : "";
                        $report_type = isset($_POST['report_type']) ? sanitize_text_field($_POST['report_type']) : "";


                        $has_reported = job_bm_has_reported($job_id, $email);

                        //echo '<pre>'.var_export($has_reported, true).'</pre>';


                        if(!$has_reported){

                            $job_reports = get_post_meta($job_id, 'job_reports', true);
                            $job_reports = empty($job_reports) ? array() : $job_reports;

                            $job_reports[] = array(
                                'email'=>$email,
                                'user_name'=>$user_name,
                                'message'=>$post_content,
                                'type'=>$report_type,

                            );


                            update_post_meta($job_id, 'job_reports', $job_reports);





                            do_action('job_bm_report_job_submitted', $job_id, $_POST);

                            ?>
                            <div class="success"><?php echo __('Thanks for report.','job-board-manager'); ?></div>
                            <?php

                        }else{
                            ?>
                            <div class="errors">
                                <div class="job-bm-error"><?php echo __('You already reported before.','job-board-manager'); ?></div>
                            </div>

                            <?php
                        }


                    }
                    else{

                        $error_messages = $error->get_error_messages();

                        ?>
                        <div class="errors">
                            <?php

                            if(!empty($error_messages))
                                foreach ($error_messages as $message){
                                    ?>
                                    <div class="job-bm-error"><?php echo $message; ?></div>
                                    <?php
                                }
                            ?>
                        </div>
                        <?php
                    }

                }


                ?>



                <form method="post" action="#report-job"  class="form-report-job ">
                    <input type="hidden" name="hidden_report_job" value="Y">

                    <?php

                    $job_reports = get_post_meta($job_id, 'job_reports', true);
                    $job_reports = empty($job_reports) ? array() : $job_reports;

                    $found_key = in_array('public.nurhasan@gmail.com', array_column($job_reports, 'email'));

                    //var_dump($found_key);
                    //echo '<pre>'.var_export($job_reports, true).'</pre>';

                    do_action('job_bm_report_job_form');

                    ?>
                    <?php wp_nonce_field( 'job_bm_report_job_nonce','job_bm_report_job_nonce' ); ?>

                    <div class="form-field-wrap">
                        <div class="field-title"></div>
                        <div class="field-input">
                            <input placeholder="" type="submit"  value="<?php echo __('Submit','job-board-manager'); ?>">
                            <p class="field-details"></p>
                        </div>
                    </div>

                </form>

            </div>

        </div>



        <script>
            jQuery( function($) {
                $( ".report-job" ).accordion({
                    active: <?php echo $active_id; ?>,
                    collapsible: true,
                    icons : false,
                    heightStyle : 'content',
                });
            } );
        </script>

        <style type="text/css">
            .report-job .report-title {
                background: #e07171;
                padding: 8px 20px !important;
                display: inline-block !important;
                border-radius: 3px;
                cursor: pointer;
                margin: 10px 0 0 0 !important;
                color: #fff;
                border: none;
            }
            .report-job .report-form{
                padding: 15px 20px;
                margin: 0px 0 0px 0;
                border: 1px solid #ececec;
                background: #ececec96;
            }
        </style>

        <?php

    }
}



add_action('job_bm_report_job_form','job_bm_report_job_form_field_name');

function job_bm_report_job_form_field_name(){
    global $current_user;
    $current_user_name = isset($current_user->display_name) ? $current_user->display_name : '';

    $user_name = isset($_POST['user_name']) ? sanitize_text_field($_POST['user_name']) : $current_user_name;

    ?>
    <div class="form-field-wrap">
        <div class="field-title"><?php echo __('Your name','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="" type="text" value="<?php echo $user_name; ?>" name="user_name">
            <p class="field-details"><?php echo __('Write your name','job-board-manager'); ?></p>
        </div>
    </div>
    <?php


}


add_action('job_bm_report_job_form','job_bm_report_job_form_field_email');

function job_bm_report_job_form_field_email(){
    global $current_user;
    $current_user_email = isset($current_user->user_email) ? $current_user->user_email : '';

    $email = isset($_POST['user_email']) ? sanitize_email($_POST['user_email']) : $current_user_email;

    ?>
    <div class="form-field-wrap">
        <div class="field-title"><?php echo __('Your email','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="" type="text" value="<?php echo $email; ?>" name="user_email">
            <p class="field-details"><?php echo __('Write your email address','job-board-manager'); ?></p>
        </div>
    </div>
    <?php
}




add_action('job_bm_report_job_form','job_bm_report_job_form_field_type');

function job_bm_report_job_form_field_type(){
    global $current_user;
    $current_user_email = isset($current_user->user_email) ? $current_user->user_email : '';

    $report_type = isset($_POST['report_type']) ? sanitize_text_field($_POST['report_type']) : '';

    $report_types = job_bm_get_report_types();

    ?>
    <div class="form-field-wrap">
        <div class="field-title"><?php echo __('Type','job-board-manager'); ?></div>
        <div class="field-input">

            <select name="report_type">
                <?php

                foreach ($report_types as $_type){
                    ?>
                    <option value="<?php echo $_type; ?>"><?php echo $_type; ?></option>

                    <?php
                }

                ?>
            </select>
            <p class="field-details"><?php echo __('Write your email address','job-board-manager'); ?></p>
        </div>
    </div>
    <?php


}


add_action('job_bm_report_job_form','job_bm_report_job_form_field_message');

function job_bm_report_job_form_field_message(){
    $job_bm_reCAPTCHA_site_key		        = get_option('job_bm_reCAPTCHA_site_key');
    $job_bm_report_submit_recaptcha		= get_option('job_bm_report_submit_recaptcha');

    $message = isset($_POST['message']) ? sanitize_text_field($_POST['message']) : '';

    ?>
    <div class="form-field-wrap">
        <div class="field-title"><?php echo __('Write message','job-board-manager'); ?></div>
        <div class="field-input">
            <textarea placeholder="" type="text" name="message"><?php echo $message; ?></textarea>
            <p class="field-details"><?php echo __('Write your detail message.','job-board-manager'); ?></p>
        </div>
    </div>


    <?php

        if($job_bm_report_submit_recaptcha == 'yes'):
            ?>
            <div class="form-field-wrap">
                <div class="field-title"></div>
                <div class="field-input">
                    <div class="g-recaptcha" data-sitekey="<?php echo $job_bm_reCAPTCHA_site_key; ?>"></div>
                    <script src="https://www.google.com/recaptcha/api.js"></script>
                    <p class="field-details"><?php _e('Please prove you are human.','job-board-manager'); ?></p>

                </div>
            </div>
            <?php
        endif;



}