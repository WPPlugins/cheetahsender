<?php
	// include wp cause it's a standalone page
	include('../fn/fn.php');
	require('../../../../wp-blog-header.php');
	require_once('../../../../wp-config.php');
	require_once('../../../../wp-includes/wp-db.php');
	// Load the options
	global $wpdb, $wpms_options, $phpmailer;	
	define('DOMAIN_PLUGIN', 'cheetahsender');
	// WP_MAIL est en mode SMTP
	// Make sure the PHPMailer class has been instantiated 
	if ( !is_object( $phpmailer ) || !is_a( $phpmailer, 'PHPMailer' ) ) 
	{
		require_once '../../../../wp-includes/class-phpmailer.php';
		require_once '../../../../wp-includes/class-smtp.php';
		$phpmailer = new PHPMailer();		
	}





	
	// on met à jour les infos 
	update_option('mail_from',$_POST['mail_from']);
	update_option('mail_from_name',$_POST['mail_from_name']);
	update_option('mailer',$_POST['mailer']);
	update_option('mail_set_return_path',$_POST['mail_set_return_path']);
	update_option('smtp_host',$_POST['smtp_host']);
	update_option('smtp_port',$_POST['smtp_port']);
	update_option('smtp_ssl',$_POST['smtp_ssl']);
	update_option('smtp_auth',$_POST['smtp_auth']);
	update_option('smtp_user',$_POST['smtp_user']);
	update_option('smtp_pass',$_POST['smtp_pass']);	



	// Add filters to replace the mail from name and emailaddress
	add_filter('wp_mail_from','wp_mail_smtp_mail_from');
	add_filter('wp_mail_from_name','wp_mail_smtp_mail_from_name');

		
	// on envoie une email et si c'est ok on met à jour
	$to = get_option('admin_email','');
	$subject = 'CheetahSender for WordPress : ' . $to;
	$message = __('This is a test email generated by the CheetahSender Plugin', DOMAIN_PLUGIN);		
	// Set SMTPDebug to level 2
	$phpmailer->SMTPDebug = 2;		
	// Start output buffering to grab smtp debugging output
	ob_start();
	// Send the test mail
	$result = wp_mail($to,$subject,$message);		
	// Grab the smtp debugging output
	$smtp_debug = ob_get_clean();				
	if(strpos( $smtp_debug,'SERVER:250 2.6.0 message received') && strpos( $smtp_debug,'SERVER:354 send message') ){
		// update plugin options	
		update_option('mail_from',$_POST['mail_from']);
		update_option('mail_from_name',$_POST['mail_from_name']);
		update_option('mailer',$_POST['mailer']);
		update_option('mail_set_return_path',$_POST['mail_set_return_path']);
		update_option('smtp_host',$_POST['smtp_host']);
		update_option('smtp_port',$_POST['smtp_port']);
		update_option('smtp_ssl',$_POST['smtp_ssl']);
		update_option('smtp_auth',$_POST['smtp_auth']);
		update_option('smtp_user',$_POST['smtp_user']);
		update_option('smtp_pass',$_POST['smtp_pass']);	
				
		// Add filters to replace the mail from name and emailaddress
		add_filter('wp_mail_from','wp_mail_smtp_mail_from');
		add_filter('wp_mail_from_name','wp_mail_smtp_mail_from_name');
		
		echo 0;				
	}else
	{
		update_option('mail_from','');
		update_option('mail_from_name','');
		update_option('mailer','mail');
		update_option('mail_set_return_path',1);
		update_option('smtp_host','localhost');
		update_option('smtp_port','25');
		update_option('smtp_ssl','none');
		update_option('smtp_auth',false);
		update_option('smtp_user','');
		update_option('smtp_pass','');	
				
		// Add filters to replace the mail from name and emailaddress
		add_filter('wp_mail_from','wp_mail_smtp_mail_from');
		add_filter('wp_mail_from_name','wp_mail_smtp_mail_from_name');

		echo -1;
	}
	// Destroy $phpmailer so it doesn't cause issues later
	unset($phpmailer);

	
?>