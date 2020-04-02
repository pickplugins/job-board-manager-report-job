<?php

function job_bm_get_report_types(){

    $report_type[] = __('Problem in applying');
    $report_type[] = __('Fraudulent');
    $report_type[] = __('Invalid Data/ Texual mistakes');
    $report_type[] = __('Offensive / Misleading');
    $report_type[] = __('Other');

    $report_type = apply_filters('job_bm_report_types', $report_type);


    return $report_type;

}


function job_bm_has_reported($job_id, $email){

    $job_reports = get_post_meta($job_id, 'job_reports', true);
    $job_reports = empty($job_reports) ? array() : $job_reports;

    $found_key = in_array($email, array_column($job_reports, 'email'));


    if( $found_key){
        return true;
    }else{
        return false;
    }

}