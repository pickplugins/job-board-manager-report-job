<?php
/*
Plugin Name: Job Board Manager - Report Job
Plugin URI: http://pickplugins.com
Description: Allow visitors to share job with social network site.
Version: 1.0.2
Author: pickplugins
Author URI: http://pickplugins.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


class JobBoardManagerReportJob{
	
	public function __construct(){

        define('job_bm_report_plugin_url', plugins_url('/', __FILE__)  );
        define('job_bm_report_plugin_dir', plugin_dir_path( __FILE__ ) );

        define('job_bm_report_plugin_name', 'Job Board Manager - Report Job' );

        // Class

        // Function's
        require_once( job_bm_report_plugin_dir . 'includes/functions-single-job.php');

        require_once( job_bm_report_plugin_dir . 'includes/functions.php');
        require_once( job_bm_report_plugin_dir . 'includes/functions-emails.php');
        require_once( job_bm_report_plugin_dir . 'includes/functions-dashboard.php');
        require_once( job_bm_report_plugin_dir . 'includes/functions-notification-email.php');
        require_once( job_bm_report_plugin_dir . 'includes/functions-settings.php');




        add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );
        add_action( 'wp_enqueue_scripts', array( $this, 'job_bm_report_front_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'job_bm_report_admin_scripts' ) );
	
	}
	
	public function job_bm_report_install(){
		
		do_action( 'job_bm_report_action_install' );
		}		
		
	public function job_bm_report_uninstall(){
		
		do_action( 'job_bm_report_action_uninstall' );
		}		
		
	public function job_bm_report_deactivation(){
		
		do_action( 'job_bm_report_action_deactivation' );
		}
		
	public function job_bm_report_front_scripts(){
		
		wp_enqueue_script('jquery');

		//wp_enqueue_script('job_bm_report_js', plugins_url( '/js/scripts.js' , __FILE__ ) , array( 'jquery' ));
		//wp_localize_script( 'job_bm_report_js', 'job_bm_report_ajax', array( 'job_bm_report_ajaxurl' => admin_url( 'admin-ajax.php')));

		//wp_enqueue_style('job_bm_report_style', job_bm_report_plugin_url.'css/style.css');


		
		}

	public function job_bm_report_admin_scripts(){
		
//		wp_enqueue_script('jquery');
//		wp_enqueue_script('jquery-ui-core');
//		wp_enqueue_script('jquery-ui-autocomplete');

		//wp_enqueue_script('job_bm_report_admin_js', plugins_url( '/admin/js/scripts.js' , __FILE__ ) , array( 'jquery' ));
		//wp_localize_script( 'job_bm_report_admin_js', 'job_bm_report_ajax', array( 'job_bm_report_ajaxurl' => admin_url( 'admin-ajax.php')));
		//wp_enqueue_style('job_bm_report_admin_style', job_bm_report_plugin_url.'admin/css/style.css');



		}
	
	}

new JobBoardManagerReportJob();