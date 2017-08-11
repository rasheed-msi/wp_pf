<?php


function mo_oauth_server_page() {

			
	$currenttab = "";
	if(isset($_GET['tab']))
		$currenttab = $_GET['tab'];
	?>
	<?php
		if(mo_oauth_server_is_curl_installed()==0){ ?>
			<p style="color:red;">(Warning: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP CURL extension</a> is not installed or disabled. Please install/enable it before you proceed.)</p>
		<?php
		}
	?>
<div id="tab">
	<h2 class="nav-tab-wrapper">
		<a class="nav-tab <?php if($currenttab == '') echo 'nav-tab-active';?>" href="admin.php?page=mo_oauth_server_settings">OAuth Clients</a> 
		<a class="nav-tab <?php if($currenttab == 'howtosetup') echo 'nav-tab-active';?>" href="admin.php?page=mo_oauth_server_settings&tab=howtosetup">How to Setup?</a> 
	</h2>
</div>
<div id="mo_oauth_settings">
	
	<div class="miniorange_container">
		<table style="width:100%;">
			<tr>
				<td style="vertical-align:top;width:65%;">
		<?php
	if (get_option ( 'verify_customer' ) == 'true') {
		mo_oauth_server_show_verify_password_page();
	} else if (trim ( get_option ( 'mo_oauth_admin_email' ) ) != '' && trim ( get_option ( 'mo_oauth_admin_api_key' ) ) == '' && get_option ( 'new_registration' ) != 'true') {
		mo_oauth_server_show_verify_password_page();
	} else if(get_option('mo_oauth_registration_status') == 'MO_OTP_DELIVERED_SUCCESS' || get_option('mo_oauth_registration_status') == 'MO_OTP_VALIDATION_FAILURE' ){
		mo_oauth_server_show_otp_verification();
	} else if (! mo_oauth_server_is_customer_registered()) {
		delete_option ( 'password_mismatch' );
		mo_oauth_server_show_new_registration_page();
	} else {
		
		if($currenttab == 'howtosetup')
			mo_oauth_server_app_howtosetup();
		else
			mo_oauth_server_apps_config();
	}
	?>
			</td>
					<td style="vertical-align:top;padding-left:1%;">
						<?php echo mo_oauth_server_miniorange_support(); ?>	
					</td>
				</tr>
			</table>
		</div>
		<?php
}
function mo_oauth_server_show_new_registration_page() {
	update_option ( 'new_registration', 'true' );
	$current_user = wp_get_current_user();
	?>
			<!--Register with miniOrange-->
		<form name="f" method="post" action="">
			<input type="hidden" name="option" value="mo_oauth_register_customer" />
			<div class="mo_table_layout">
				<div id="toggle1" class="panel_toggle">
					<h3>Register with miniOrange</h3>
				</div>
				<div id="panel1">
					<!--<p><b>Register with miniOrange</b></p>-->
					<p>Please enter a valid Email ID that you have access to. You will be able to move forward after verifying an OTP that we will be sending to this email.
					</p>
					<table class="mo_settings_table">
						<tr>
							<td><b><font color="#FF0000">*</font>Email:</b></td>
							<td><input class="mo_table_textbox" type="email" name="email"
								required placeholder="person@example.com"
								value="<?php echo get_option('mo_oauth_admin_email');?>" />
							</td>
						</tr>
							<tr>
							<td><b><font color="#FF0000">*</font>Website/Company Name:</b></td>
							<td><input class="mo_table_textbox" type="text" name="company"
							required placeholder="Enter website or company name" 
							value="<?php echo $_SERVER['SERVER_NAME']; ?>"/></td>
						</tr>
						<tr>
							<td><b>&nbsp;&nbsp;First Name:</b></td>
							<td><input class="mo_openid_table_textbox" type="text" name="fname"
							placeholder="Enter first name" value="<?php echo $current_user->user_firstname;?>" /></td>
						</tr>
						<tr>
							<td><b>&nbsp;&nbsp;Last Name:</b></td>
							<td><input class="mo_openid_table_textbox" type="text" name="lname"
							placeholder="Enter last name" value="<?php echo $current_user->user_lastname;?>" /></td>
						</tr>

						<tr>
							<td><b>&nbsp;&nbsp;Phone number :</b></td>
							 <td><input class="mo_table_textbox" type="text" name="phone" pattern="[\+]?([0-9]{1,4})?\s?([0-9]{7,12})?" id="phone" title="Phone with country code eg. +1xxxxxxxxxx" placeholder="Phone with country code eg. +1xxxxxxxxxx" value="<?php echo get_option('mo_oauth_admin_phone');?>" />
							 This is an optional field. We will contact you only if you need support.</td>
							</tr>
						</tr>
						<tr>
							<td></td>
							<td>We will call only if you need support.</td>
						</tr>
						<tr>
							<td><b><font color="#FF0000">*</font>Password:</b></td>
							<td><input class="mo_table_textbox" required type="password"
								name="password" placeholder="Choose your password (Min. length 8)" /></td>
						</tr>
						<tr>
							<td><b><font color="#FF0000">*</font>Confirm Password:</b></td>
							<td><input class="mo_table_textbox" required type="password"
								name="confirmPassword" placeholder="Confirm your password" /></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><br /><input type="submit" name="submit" value="Save" style="width:100px;"
								class="button button-primary button-large" /></td>
						</tr>
					</table>
				</div>
			</div>
		</form>
		<script>
			jQuery("#phone").intlTelInput();
		</script>
		<?php
}
function mo_oauth_server_show_verify_password_page() {
	?>
			<!--Verify password with miniOrange-->
		<form name="f" method="post" action="">
			<input type="hidden" name="option" value="mo_oauth_verify_customer" />
			<div class="mo_table_layout">
				<div id="toggle1" class="panel_toggle">
					<h3>Login with miniOrange</h3>
				</div>
				<div id="panel1">
					</p>
					<table class="mo_settings_table">
						<tr>
							<td><b><font color="#FF0000">*</font>Email:</b></td>
							<td><input class="mo_table_textbox" type="email" name="email"
								required placeholder="person@example.com"
								value="<?php echo get_option('mo_oauth_admin_email');?>" /></td>
						</tr>
						<td><b><font color="#FF0000">*</font>Password:</b></td>
						<td><input class="mo_table_textbox" required type="password"
							name="password" placeholder="Choose your password" /></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type="submit" name="submit"
								class="button button-primary button-large" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a
								target="_blank"
								href="<?php echo get_option('host_name') . "/moas/idp/userforgotpassword"; ?>">Forgot
									your password?</a></td>
						</tr>
					</table>
				</div>
			</div>
		</form>
		<?php
}

function mo_oauth_server_sign_in_settings(){
	?>
	<div class="mo_table_layout">
		<h2>Sign in options</h2>
		
		<h4>Option 1: Use a Widget</h4>
		<ol>
			<li>Go to Appearances > Widgets.</li>
			<li>Select <b>"miniOrange OAuth"</b>. Drag and drop to your favourite location and save.</li>
		</ol>
		
		<h4>Option 2: Use a Shortcode</h4>
		<ul>
			<li>Place shortcode <b>[mo_oauth_login]</b> in wordpress pages or posts.</li>
		</ul>
	</div>
	<?php
}

function mo_oauth_server_app_howtosetup(){
	?>
	<style>
		.tableborder {border-collapse: collapse;width: 100%;border-color:#eee;}
		.tableborder th, .tableborder td {text-align: left;padding: 8px;border-color:#eee;}
		.tableborder tr:nth-child(even){background-color: #f2f2f2}
	</style>
	<div class="mo_table_layout">
	<h2>Endpoint Urls</h2>
	<p>You can configure below endpoints in your OAuth client.<p>
	<hr>
	<table class="tableborder">
	<tr><td><b>Authorize Endpoint </b> : </td><td><?php echo site_url();?>/wp-content/plugins/miniorange-oauth-20-server/web/moserver/authorize</td></tr>
	<tr><td><b>Access Token Endpoint </b> : </td><td><?php echo site_url();?>/wp-content/plugins/miniorange-oauth-20-server/web/moserver/token</td></tr>
	<tr><td><b>Get User Info Endpoint </b> : </td><td><?php echo site_url();?>/wp-content/plugins/miniorange-oauth-20-server/web/moserver/resource</td></tr>
	<tr><td><b>Scope </b> : </td><td>Keep Empty / Blank</td></tr>
	</table>
	</div>
	
	
	<?php
}

function mo_oauth_server_apps_config() {
	?>
	<style>
		.tableborder {border-collapse: collapse;width: 100%;border-color:#eee;}
		.tableborder th, .tableborder td {text-align: left;padding: 8px;border-color:#eee;}
		.tableborder tr:nth-child(even){background-color: #f2f2f2}
	</style>
	<div class="mo_table_layout">
	<?php
	
		if(isset($_GET['action']) && $_GET['action']=='delete'){
			if(isset($_GET['client']))
				mo_oauth_server_delete_app($_GET['client']);
		} else if(isset($_GET['action']) && $_GET['action']=='instructions'){
			mo_oauth_server_instructions();
		}
		
		if(isset($_GET['action']) && $_GET['action']=='add'){
			mo_oauth_server_add_app();
		} 
		else if(isset($_GET['action']) && $_GET['action']=='update'){
			if(isset($_GET['client']))
				mo_oauth_server_update_app($_GET['client']);
		}
		else 
		{	
	
			$dbfile = __DIR__.'/data/oauth.sqlite';
			$db = new PDO(sprintf('sqlite:%s', $dbfile));
			$stmt = $db->prepare('SELECT * from oauth_clients');
			$stmt->execute();
			$clientlist = $stmt->fetchAll();
			
			echo "<br><a href='admin.php?page=mo_oauth_server_settings&action=add'><button style='float:right'>Add Client</button></a>";
			echo "<h3>Clients List</h3>";
			echo "<table class='tableborder'>";
			echo "<tr><th><b>Client Name</b></th><th>Client ID</th><th>Client Secret</th></tr>";
			foreach($clientlist as $key => $client){
				echo "<tr><td>".$client['client_name']."
				<br>
				<a href='admin.php?page=mo_oauth_server_settings&action=update&client=".$client['client_name']."'>Update</a> | <a href='admin.php?page=mo_oauth_server_settings&action=delete&client=".$client['client_name']."'>Delete</a>
				</td><td>".$client['client_id']."</td><td>".$client['client_secret']."</td></tr>";
			}
			echo "</table>";
			echo "<br><br>";
	
		}  ?>
		</div>
<?php
	
}

function mo_oauth_server_add_app(){
	
	?>

		<div id="toggle2" class="panel_toggle">
			<h3>Add Client</h3>
			
		</div>
		<form id="form-common" name="form-common" method="post" action="admin.php?page=mo_oauth_server_settings">
		<input type="hidden" name="option" value="mo_oauth_add_client" /> 
		<table class="mo_settings_table">
			<tr>
				<td><strong><font color="#FF0000">*</font>Client Name :</strong></td>
				<td><input class="mo_table_textbox" type="text" id="mo_oauth_custom_client_name" name="mo_oauth_custom_client_name" value=""></td>
			</tr>
			<tr>
				<td><strong><font color="#FF0000">*</font>Authorized Redirect URI :</strong></td>
				<td><input class="mo_table_textbox" required="" type="text" name="mo_oauth_client_redirect_url" value=""></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="submit" value="Save Client"
					class="button button-primary button-large" /></td>
			</tr>
			</table>
		</form>
		
		<?php
}

function mo_oauth_server_update_app($client_name){
	
	$dbfile = __DIR__.'/data/oauth.sqlite';
	$db = new PDO(sprintf('sqlite:%s', $dbfile));
	$stmt = $db->prepare('SELECT * from oauth_clients');
	$stmt->execute();
	$clientlist = $stmt->fetchAll();
	
	$currentclient =false;
	foreach($clientlist as $key => $client){
		if($client_name == $client['client_name']){
			$currentclient = $client;
			break;
		}
	}
	
	if(!$currentclient)
		return;
	
	
	?>
		
		<div id="toggle2" class="panel_toggle">
			<h3>Update Application</h3>
		</div>
		<form id="form-common" name="form-common" method="post" action="admin.php?page=mo_oauth_server_settings">
		<input type="hidden" name="option" value="mo_oauth_update_client" /> 
		<table class="mo_settings_table">
			<tr>
				<td><strong><font color="#FF0000">*</font>Client Name :</strong></td>
				<td><?php echo $currentclient['client_name'];?><input class="mo_table_textbox" type="hidden" id="mo_oauth_custom_client_name" name="mo_oauth_custom_client_name" value="<?php echo $currentclient['client_name'];?>"></td>
			</tr>
			<tr>
				<td><strong><font color="#FF0000">*</font>Authorized Redirect URI :</strong></td>
				<td><input class="mo_table_textbox" required="" type="text" name="mo_oauth_client_redirect_url" value="<?php echo $currentclient['redirect_uri'];?>"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="submit" value="Update Client"
					class="button button-primary button-large" /></td>
			</tr>
			</table>
		</form>
		
		
	<?php	
}

function mo_oauth_server_delete_app($appname){
	$dbfile = __DIR__.'/data/oauth.sqlite';
	$db = new PDO(sprintf('sqlite:%s', $dbfile));
	$db->exec('DELETE FROM oauth_clients  WHERE client_name = "'.$appname.'"');
}

function mo_oauth_server_instructions(){
	echo '<br><strong>Instructions to configure custom OAuth Server:</strong><ol><li>Enter your Client ID and Client Secret above.</li><li>Click on the Save settings button.</li><li>Provide <b>'.site_url().'/oauthcallback</b> for your OAuth server Redirect URI.</li><li>Go to Appearance->Widgets. Among the available widgets you will find miniOrange OAuth, drag it to the widget area where you want it to appear.</li><li>Now logout and go to your site. You will see a login link where you placed that widget.</li></ol>';
}



function mo_oauth_server_miniorange_support(){
?>
	<div class="mo_support_layout">
		<!--<h3>Support</h3>
		<div >
			<p>Your general questions can be asked in the plugin <a href="https://wordpress.org/support/plugin/miniorange-login-with-eve-online-google-facebook" target="_blank">support forum</a>.</p>
		</div>
		<div style="text-align:center;">
			<h4>OR</h4>
		</div>-->
		<div>
			<h3>Contact Us</h3>
			<form method="post" action="">
				<input type="hidden" name="option" value="mo_oauth_contact_us_query_option" />
				<table class="mo_settings_table">
					<tr>
						<td><b><font color="#FF0000">*</font>Email:</b></td>
						<td><input type="email" class="mo_table_textbox" required name="mo_oauth_contact_us_email" value="<?php echo get_option("mo_oauth_admin_email"); ?>"></td>
					</tr>
					<tr>
						<td><b>Phone:</b></td>
						<td><input type="tel" id="contact_us_phone" pattern="[\+]\d{11,14}|[\+]\d{1,4}[\s]\d{9,10}" class="mo_table_textbox" name="mo_oauth_contact_us_phone" value="<?php echo get_option('mo_oauth_admin_phone');?>"></td>
					</tr>
					<tr>
						<td><b><font color="#FF0000">*</font>Query:</b></td>
						<td><textarea class="mo_table_textbox" onkeypress="mo_oauth_valid_query(this)" onkeyup="mo_oauth_valid_query(this)" onblur="mo_oauth_valid_query(this)" required name="mo_oauth_contact_us_query" rows="4" style="resize: vertical;"></textarea></td>
					</tr>
				</table>
				<div style="text-align:center;">
					<input type="submit" name="submit" style="margin:15px; width:100px;" class="button button-primary button-large" />
				</div>
			</form>
		</div>
	</div>
	<script>
		jQuery("#contact_us_phone").intlTelInput();
		function mo_oauth_valid_query(f) {
			!(/^[a-zA-Z?,.\(\)\/@ 0-9]*$/).test(f.value) ? f.value = f.value.replace(
					/[^a-zA-Z?,.\(\)\/@ 0-9]/, '') : null;
		}
	</script>
<?php
}

function mo_oauth_server_show_otp_verification(){
	?>
		<!-- Enter otp -->
		<form name="f" method="post" id="otp_form" action="">
			<input type="hidden" name="option" value="mo_oauth_validate_otp" />
				<div class="mo_table_layout">
					<div id="panel5">
						<table class="mo_settings_table">
							<h3>Verify Your Email</h3>
							<tr>
								<td><b><font color="#FF0000">*</font>Enter OTP:</b></td>
								<td><input class="mo_table_textbox" autofocus="true" type="text" name="mo_oauth_otp_token" required placeholder="Enter OTP" style="width:61%;" pattern="[0-9]{6,8}"/>
								 &nbsp;&nbsp;<a style="cursor:pointer;" onclick="document.getElementById('mo_oauth_resend_otp_form').submit();">Resend OTP</a></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><br /><input type="submit" name="submit" value="Validate OTP" class="button button-primary button-large" />
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="button" name="back-button" id="mo_oauth_back_button" onclick="document.getElementById('mo_oauth_change_email_form').submit();" value="Back" class="button button-primary button-large" />
								</td>
							</tr>
						</table>
					</div>
				</div>
		</form>
		<form name="f" id="mo_oauth_resend_otp_form" method="post" action="">
			<input type="hidden" name="option" value="mo_oauth_resend_otp"/>
		</form>	
		<form id="mo_oauth_change_email_form" method="post" action="">
			<input type="hidden" name="option" value="mo_oauth_change_email" />
		</form>
<?php
}
?>