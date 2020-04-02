<?php
if ( ! defined('ABSPATH')) exit;  // if direct access 


add_filter('job_bm_dashboard_tabs','job_bm_dashboard_tabs_reported_jobs');
function job_bm_dashboard_tabs_reported_jobs($tabs){

    $tabs['reported_jobs'] =array(
        'title'=>__('Reported Jobs', 'job-board-manager-company-profile'),
        'priority'=>5,
    );

    return $tabs;

}







add_action('job_bm_dashboard_tabs_content_reported_jobs', 'job_bm_dashboard_tabs_content_reported_jobs');

if(!function_exists('job_bm_dashboard_tabs_content_reported_jobs')){
    function job_bm_dashboard_tabs_content_reported_jobs(){

        wp_enqueue_style('job-bm-my-jobs');
        $userid                     = get_current_user_id();

        $job_bm_job_edit_page_id    = get_option('job_bm_job_edit_page_id');
        $job_bm_job_edit_page_url   = get_permalink($job_bm_job_edit_page_id);
        $job_bm_list_per_page       = !empty($job_bm_list_per_page) ? $job_bm_list_per_page : 10;

        if ( get_query_var('paged') ) {
            $paged = get_query_var('paged');
        } elseif ( get_query_var('page') ) {
            $paged = get_query_var('page');
        } else {
            $paged = 1;
        }

        $meta_query = array();
        $meta_query[] = array(
            'key' => 'job_reports',
            'compare' => 'EXIST',
        );

        $wp_query = new WP_Query(
            array (
                'post_type' => 'job',
                'post_status' => 'any',
                'orderby' => 'date',
                'order' => 'DESC',
                'meta_query' => $meta_query,
                'author' => $userid,
                'posts_per_page' => $job_bm_list_per_page,
                'paged' => $paged,
            )
        );





        ?>
        <div class="nav-head">
            <?php echo __('Reported Jobs', 'job-board-manager-company-profile'); ?>

        </div>
        <div class="job-bm-my-jobs reported-jobs">

            <div class="job-list">

                <?php

                if ( $wp_query->have_posts() ) :
                    while ( $wp_query->have_posts() ) : $wp_query->the_post();

                        $job_id         = get_the_id();

                        do_action('job_bm_my_reported_jobs_loop', $job_id);


                    endwhile;

                    ?>
                    <div class="paginate">
                        <?php
                        $big = 999999999; // need an unlikely integer
                        echo paginate_links( array(
                            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                            'format' => '?paged=%#%',
                            'current' => max( 1, $paged ),
                            'total' => $wp_query->max_num_pages
                        ) );

                        ?>
                    </div>
                    <?php

                    wp_reset_query();

                else:

                    echo sprintf(__('%s No job found posted by you.', 'job-board-manager'), '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>');

                endif;

                ?>
            </div>



        </div>

        <style type="text/css">

            .reported-jobs{}
            .reported-jobs .report-list{}
            .reported-jobs .report-list li{
                list-style: none;
                margin: 0;
                padding: 10px 10px;
                background: #f1f1f1;
                border-bottom: 1px solid #ddd;
            }


            .reported-jobs .report-list li:last-child{
                border-bottom: 0px solid #ddd;
            }

        </style>


        <?php

    }
}




add_action('job_bm_my_reported_jobs_loop','job_bm_my_reported_jobs_loop_wrap_start');

if(!function_exists('job_bm_my_reported_jobs_loop_wrap_start')){
    function job_bm_my_reported_jobs_loop_wrap_start($job_id){


        ?>
        <div class="my-job-card my-job-card-<?php echo $job_id; ?>">



        <?php

    }
}



add_action('job_bm_my_reported_jobs_loop','job_bm_my_reported_jobs_loop_header');

if(!function_exists('job_bm_my_reported_jobs_loop_header')){
    function job_bm_my_reported_jobs_loop_header($job_id){

        $class_job_bm_applications = new class_job_bm_applications();

        $job_bm_job_edit_page_id    = get_option('job_bm_job_edit_page_id');
        $job_bm_job_edit_page_url   = get_permalink($job_bm_job_edit_page_id);

        $featured       = get_post_meta($job_id, 'job_bm_featured',true);
        $featured_class = ($featured == 'yes') ? 'featured' : '';
        $application_count = $class_job_bm_applications->application_count_by_job_id($job_id);
        $application_hired_count = job_bm_job_application_hired_count($job_id);

        ?>
        <div class="card-top">
            <div class="card-action">
                <span class="job-id" title="<?php echo __('Job id.', 'job-board-manager'); ?>">#<?php echo $job_id; ?></span>
                <a class="job-edit" title="<?php echo __('Job edit.', 'job-board-manager'); ?>" href="<?php echo $job_bm_job_edit_page_url; ?>?job_id=<?php echo $job_id; ?>" target="_blank"><i class="fas fa-pencil-alt"></i></a>
                <span class="job-delete delete-job" job-id="<?php echo $job_id; ?>" title="<?php echo __('Job trash.', 'job-board-manager'); ?>"><i class="far fa-trash-alt"></i></span>
                <span class="job-featured <?php echo $featured_class; ?>" title="<?php echo ($featured=='yes') ?  __('Featured job.', 'job-board-manager') : __('Not featured', 'job-board-manager'); ?>"><i class="fas fa-star"></i></span>
                <span class="job-application" title="<?php echo __('Total application.', 'job-board-manager'); ?>"><i class="fas fa-user-clock"></i> <?php echo $application_count; ?></span>
                <span class="job-hired" title="<?php echo __('Total hired.', 'job-board-manager'); ?>"><i class="fas fa-user-tie"></i> <?php echo $application_hired_count; ?></span>

            </div>

        </div>



        <?php

    }
}



add_action('job_bm_my_reported_jobs_loop','job_bm_my_reported_jobs_loop_body');

if(!function_exists('job_bm_my_reported_jobs_loop_body')){
    function job_bm_my_reported_jobs_loop_body($job_id){

        $class_job_bm_functions = new class_job_bm_functions();
        $class_job_bm_applications = new class_job_bm_applications();

        $job_type_filters = $class_job_bm_functions->job_type_list();
        $job_level_filters = $class_job_bm_functions->job_level_list();
        $job_status_filters = $class_job_bm_functions->job_status_list();
        $application_count = $class_job_bm_applications->application_count_by_job_id($job_id);


        $job_bm_job_login_page_id    = get_option('job_bm_job_login_page_id');
        $job_bm_job_login_page_url   = get_permalink($job_bm_job_login_page_id);

        $job_title = get_the_title($job_id);
        $post_date      = get_the_date('Y-m-d');
        $date_format                        = get_option( 'date_format' );

        $expiry_date    = get_post_meta($job_id, 'job_bm_expire_date',true);
        $publish_status = get_post_status($job_id);
        $job_status     = get_post_meta($job_id, 'job_bm_job_status',true);
        $featured       = get_post_meta($job_id, 'job_bm_featured',true);
        $job_type       = get_post_meta($job_id, 'job_bm_job_type',true);
        $job_label = get_post_meta($job_id, 'job_bm_job_level',true);

        $get_post_stati = get_post_statuses();

        //var_dump($get_post_stati);

        $job_reports = array();

        ?>
        <div class="card-body">

            <a title="<?php echo __('Job link.', 'job-board-manager'); ?>" class="job-link meta" href="<?php echo get_permalink($job_id); ?>"><i class="fas fa-external-link-square-alt"></i> <?php echo $job_title; ?></a>

            <b><?php echo __('User feedbacks','job-board-manager'); ?></b>
            <ul class="report-list">
                <?php

                $job_reports = get_post_meta($job_id, 'job_reports', true);
                $job_reports = empty($job_reports) ? array() : $job_reports;


                foreach ($job_reports as $report){
                    ?>
                    <li class="">
                        <div class="user-name"><?php echo __('Name:'); ?> <?php echo $report['user_name']?></div>
                        <div class="type"><?php echo __('Type:'); ?> <?php echo $report['type']?></div>

                        <div class="message"><?php echo __('Message:'); ?> <?php echo $report['message']?></div>

                    </li>
                    <?php
                }

                ?>

            </ul>

            <?php











            ?>


        </div>



        <?php

    }
}



















add_action('job_bm_my_reported_jobs_loop','job_bm_my_reported_jobs_loop_wrap_end');

if(!function_exists('job_bm_my_reported_jobs_loop_wrap_end')){
    function job_bm_my_reported_jobs_loop_wrap_end(){


        ?>

        </div>

        <?php

    }
}



		