jQuery(document).ready(function($)
	{	
		$(document).on('click', '.saved-job-list .remove-saved', function()
			{
				var saved_id = $(this).attr('saved_id');			
				
				$.ajax(
						{
					type: 'POST',
					context: this,
					url:job_bm_sj_ajax.job_bm_sj_ajaxurl,
					data: {"action": "job_bm_sj_ajax_delete_save_job", "saved_id":saved_id,},
					success: function(data)
							{	
							
								$(this).parent().parent().fadeOut('slow');
								location.reload();
							}
						});
				})
		
		$(document).on('click', '.job-single .report-job-popup .report-job-close', function()
			{
				$('.report-job-popup').fadeOut();
			})	
			
		$(document).on('click', '.job-single .report-job-popup .report-job-form-success .rpf-close', function()
			{
				$('.report-job-popup').fadeOut();
				//location.reload();
			})	
		
		$(document).on('click', '.job-single .report-job-popup .report-job-form-reported .rpf-close', function()
			{
				$('.report-job-popup').fadeOut();
				//location.reload();
			})	
				
		$(document).on('click', '.job-single .report-job', function()
			{
				$('.report-job-popup').fadeIn();
			})
		
		$(document).on('click', '.job-single .job-report-submit', function()
			{	
				var report_job_id 			= $(this).attr('job-report-id');
				var report_job_note 		= $('.job-report-note').val();	
				var report_job_user_email 	= $('input[name=report_job_user_email]').attr('value');				
				var report_job_type 		= $('#report_job_type option:selected').val();
				
				if ( report_job_type != 0 ) 
				{
					$.ajax(
						{
					type: 'POST',
					context: this,
					url:job_bm_report_ajax.job_bm_report_ajaxurl,
					data: {
						"action": "job_bm_report_ajax_submit_report", 
						"report_job_id":report_job_id,
						"report_job_note":report_job_note,
						"report_job_user_email":report_job_user_email,
						"report_job_type":report_job_type,
					},
					success: function(data)
							{	
								$('.job-report-title, .report-job-close, .report-job-form-block,.report-job-block-textarea, .job-report-submit,.report-job-form-header').css("display","none");
								$('.report-job-form-success').css("display","block");
								$(this).html(data);
								//$('.report-job-popup').fadeOut();
							}
						});
				}
				else $('select').first().focus();
			})
			
			
			
			//============================================================
			
			$(document).on('change', '.report-list-select .report-job-id', function()
			{
				var report_job_id = $(this).val();			
				//alert(report_job_id);
				$.ajax(
						{
					type: 'POST',
					context: this,
					url:job_bm_report_ajax.job_bm_report_ajaxurl,
					data: {"action": "job_bm_ajax_report_list", "report_job_id":report_job_id,},
					success: function(data)
							{	
								$('.report-list').html(data);
							}
						});
				
				})	
		
		
		
		
		
		
		
		$(document).on('click', '.resume-finder', function()
			{
				$('.resume-finder-popup').fadeIn();
				
				
				})		
		
		
		$(document).on('click', '.resume-finder-popup .close', function()
			{
				$('.resume-finder-popup').fadeOut();
				
				
				})
		
		
		
		
		
		
		$(document).on('click', '.resume-finder-popup .submit-application', function()
			{
				var resume_id = $('.resume-id').val();
				var application_text = $('.application-text').val();
				var apply_method = $(this).attr('apply_method');
				var job_id = $(this).attr('job_id');											
				
				$(this).html('Submitting...');
				
				$.ajax(
						{
					type: 'POST',
					context: this,
					url:job_bm_am_ajax.job_bm_am_ajaxurl,
					data: {"action": "job_bm_am_ajax_submit_application", "resume_id":resume_id,"job_id":job_id,"application_text":application_text,"apply_method":apply_method,},
					success: function(data)
							{	
						
								$(this).html(data);

							}
						});
				
			})
	});
	
	$("holding-message").hover(function(){
    $(this).css("background-color", "yellow");
    }, function(){
    $(this).css("background-color", "pink");
});