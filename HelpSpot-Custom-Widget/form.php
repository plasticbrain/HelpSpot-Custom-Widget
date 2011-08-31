<?php
//------------------------------------------------------------------------------
// Using separate php files like this to handle the forms gives us
// the ability to use specific, custom forms for specific departments,
// users, etc... Basically, it gives us even more ability to customize!
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// Variables, arrays, etc
//------------------------------------------------------------------------------
$options = array();
$errors = array();
$form_was_submitted = false;
$success = false;
$form_data = array();
$form_results = '';

//------------------------------------------------------------------------------
// form field values
//------------------------------------------------------------------------------
$first_name = '';
$last_name = '';
$email = '';
$phone = '';
$department = '';
$message = '';
$read_faqs = false;

//------------------------------------------------------------------------------
// Process the form if it was submitted
//------------------------------------------------------------------------------
if( isset($_POST['btn_submit']) ) {
	
	$form_was_submitted = true;
	
	//	
	// validate the form data
	//
	
	// Firstname
	if( !strlen(trim(@$_POST['sFirstName'])) ) {
		$errors['firstname'] = 'Please enter your First Name';
	} else {
		$first_name = $form_data['sFirstName'] = $_POST['sFirstName'];
	}	
	
	// Lastname
	if( !strlen(trim(@$_POST['sLastName'])) ) {
		$errors['lastname'] = 'Please enter your Last Name';
	} else {
		$last_name = $form_data['sLastName'] = $_POST['sLastName'];
	}
	
	// Valid email
	if( !preg_match("/^[+_a-zA-Z0-9-\.]+(\.[_a-zA-Z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $_POST['sEmail']) ) {
		$errors['email'] = 'Please enter a valid Email Address';
	} else {
		$email = $form_data['sEmail'] = $_POST['sEmail'];
	}
	
	// phone number (not required)
	$form_data['sPhone'] = strlen(trim(@$_POST['sPhone'])) ? $_POST['sPhone'] : '';
		
	// Departmant/Category
	if( !strlen(trim(@$_POST['xCategory'])) ) {
		$errors['department'] = 'Please choose a Department';
	} else {
		$department = $form_data['xCategory'] =  $_POST['xCategory'];
	}
	
	// Note / message
	if( !strlen(trim(@$_POST['tNote'])) ) {
		$errors['message'] = 'Please enter a Message';
	} else {
		$message = $form_data['tNote'] = $_POST['tNote'];
	}
	
	// Did the user read the FAQs?
	if( !isset($_POST['read_faq']) ) {
		$errors['read_faq'] = 'Please check the FAQs and Support Center before contacting support';
	} else {
		$read_faqs = true;
	}
	
	// Is there a Portal ID set?	
	$portal = $form_data['xPortal'] = isset($_POST['xPortal']) ? $_POST['xPortal'] : '';
	
	// Finally, include all of our default options
	foreach( $_POST['options'] as $k => $v ) $options[$k] = $v;
	
	
	//
	// No errors? Submit the form using the HelpSpot API
	//
	
	if( empty($errors) ) {
		
		// Format the query string
		$post_data = '';
		foreach( $form_data as $k => $v ) {
			if( $v != '' ) $post_data .= "$k=" . urlencode($v) . '&';
		}
		$post_data = rtrim($post_data, '&');
		
		// Set the URL
		$url = $options['host'].'api/index.php?method=request.create&output=json';
		
		// Fire up cURL to get the page we want
 		$ch = curl_init();
 		curl_setopt($ch, CURLOPT_URL,$url);
 		curl_setopt($ch, CURLOPT_POST, 1);
  	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  	$results = @curl_exec($ch);
		@curl_close($ch);
		//var_dump($results);
			
		// Error?	
  	if( !$results || isset($results->error) ) {
  		$errors[] = 'An error has occurred. Your request could not be completed at this time.';	
  	
  	// No error.. return the data
 		} else {
 			$form_results = json_decode($results);
 			$success = true;
 		}
 	}
    	
//------------------------------------------------------------------------------
// If the form was not submitted (ie: that page was just loaded) then
// we need to load our "options" passed from the jQuery plugin
//------------------------------------------------------------------------------	
} else {
	
	// load the default options
	foreach( $_GET as $k => $v ) $options[$k] = urldecode($v);
	
	// Set the default field values
	$first_name =  $options['default_first_name']  ? $options['default_first_name'] : '';
	$last_name =  $options['default_last_name']  ? $options['default_last_name'] : '';
	$email = $options['default_email'] ? $options['default_email'] : '';
	$phone = $options['default_phone'] ? $options['default_phone'] : '';
	$department = $options['default_department'] ? $options['default_department'] : '';
	$message = '';
	$read_faqs = false;
}

//------------------------------------------------------------------------------
// Get the support categories (for the "departments" dropdown)
//------------------------------------------------------------------------------
if( $options['demo'] == FALSE ) {
	$url = $options['host'] . 'api/index.php?method=request.getCategories&output=json';
	$resp = @file_get_contents($url);
	$cats = $resp ? json_decode($resp) : array();
	//var_dump($cats);
}


//------------------------------------------------------------------------------
// Finally, print out the page
//------------------------------------------------------------------------------
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript" src="form.js"></script>
<link href="widget.css" rel="stylesheet" type="text/css" media="screen" />
<style type="text/css">
html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,font,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,center,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td{margin:0;padding:0;border:0;outline:0;font-size:100%;vertical-align:baseline;background:transparent}body{line-height:1}ol,ul{list-style:none}blockquote,q{quotes:none}blockquote:before,blockquote:after,q:before,q:after{content:'';content:none}:focus{outline:0}ins{text-decoration:none}del{text-decoration:line-through}table{border-collapse:collapse;border-spacing:0}
</style>


<div id="cw_form_container" class="cw_form">
	
	<?php
	//----------------------------------------------------------------------------
	// Was the form successfully submitted?
	//----------------------------------------------------------------------------
	if( $form_was_submitted && $success ): 
	?>	
	
	<h1><?php echo stripslashes($options['success_headline_text']); ?></h1>
	<p><?php echo stripslashes($options['success_body_text']); ?></p>
	
	<p id="view_ticket">
		Your <i>Access Key</i> (used to view this ticket) is: <span class="access_key"><?php echo $form_results->accesskey; ?></span><br /><br />
		You may view this ticket at the following url:<br />
		<a target="_blank" href="<?php echo $options['host'].'index.php?pg=request.check&id=' . $form_results->accesskey; ?>">
			<?php echo $options['host'].'index.php?pg=request.check&id=' . $form_results->accesskey; ?>
		</a>
	</p>
	
	<?php /*
	<p>The following information was submitted:</p>
	<ul>
		<?php foreach( $form_data as $k => $v ): ?>
			<li><b><?php echo $k; ?></b>: <?php echo strip_tags($v); ?></li>
		<?php endforeach; ?>
	</ul>
	*/ ?>
			
	
	
	<?php
	//----------------------------------------------------------------------------
	// Print out the form
	//----------------------------------------------------------------------------
	else:
	?>
	
	<form id="frm_cw_form" action="" method="post">
		
		<!-- First Name -->
		<p style="width: 48%; float:left;" class="<?php if(isset($errors['firstname'])) echo 'error'; ?>">
			<label class="desc" for="cw_name">First Name</label>
			<input id="cw_name" class="text full required" type="text" name="sFirstName" value="<?php echo strip_tags($first_name); ?>" />
		</p>
		
		<!-- Last Name -->
		<p style="width: 48%; float:right;" class="<?php if(isset($errors['lastname'])) echo 'error'; ?>">
			<label class="desc" for="cw_name">Last Name</label>
			<input id="cw_name" class="text full required" type="text" name="sLastName" value="<?php echo strip_tags($last_name); ?>" />
		</p>
		
		<!-- Email Address -->
		<p class="<?php if(isset($errors['email'])) echo 'error'; ?>">
			<label class="desc" for="cw_email">Email Address</label>
			<input id="cw_email" class="text full required" type="text" name="sEmail" value="<?php echo strip_tags($email); ?>" />
		</p>
		
		<!-- Phone Number -->
		<p class="<?php if(isset($errors['phone'])) echo 'error'; ?>">
			<label class="desc" for="cw_phone">Phone Number</label>
			<input id="cw_phone" class="text full" type="text" name="sPhone" value="<?php echo strip_tags($phone); ?>" />
		</p>
		
		<!-- Department/Category -->
		<p class="<?php if(isset($errors['department'])) echo 'error'; ?>">
			<label class="desc" for="cw_email">Please choose a department</label>

			<?php if( $options['demo'] == TRUE ): ?>
				<small>Not available in demo more</small>

			<?php else: ?>
			
			<select name="xCategory" class="text auto">
				<option></option>
				<?php if( !empty($cats) ): ?>
					<?php foreach( $cats->category as $cat  ): ?>
						<option <?php if($department == $cat->xCategory) echo 'selected="selected"'; ?> value="<?php echo $cat->xCategory; ?>"><?php echo $cat->sCategory; ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
			
			<?php endif; ?>
		</p>
		
		<!-- Message -->
		<p class="<?php if(isset($errors['message'])) echo 'error'; ?>">
			<label class="desc" for="cw_message">Message</label>
			<textarea id="cw_message" name="tNote" class="text full" cols="5" rows="7"><?php echo strip_tags($message); ?></textarea>
		</p>
		
		<!-- Confirm readind FAQs -->
		<p class="<?php if(isset($errors['read_faq'])) echo 'error'; ?>">
			<input id="cw_read_faq" <?php if($read_faqs == true) echo 'checked="checked"'; ?> type="checkbox" name="read_faq" value="1" />  
			<label class="label" for="cw_read_faq">
				I have read the <a href="<?php echo $options['host']; ?><?php echo $options['faqs']; ?>" target="_blank">FAQs</a> and checked the <a href="<?php echo $options['host']; ?>" target="_blank">Support Center</a> for an answer to my question
			</label>
		</p>
		
		<!-- Button(s) and hidden form fields -->		
		<p class="buttons">
			<input id="btn_cw_submit" type="submit" class="button" name="btn_submit" value="<?php echo $options['submit_button_text']; ?>" />
			<?php foreach($options as $k => $v): ?>
				<input type="hidden" name="options[<?php echo $k; ?>]" value="<?php echo $v; ?>" />
			<?php endforeach; ?>
			<input type="hidden" name="xPortal" value="<?php echo $options['portal']; ?>" />
		</p>
	
	</form>
	
	<?php endif; ?>
	
</div>
<!-- end custom widget form -->