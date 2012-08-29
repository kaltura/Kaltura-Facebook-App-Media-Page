<?php
$page = @$_REQUEST['fb_page_id'];
if($page == '') { 
	echo 'You may only visit this admin console from Facebook'.'</br>';
	die();
}
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Media Page</title>
	<!-- Style Includes -->
	<link href="appStyle.css" media="screen" rel="stylesheet"/>
	<link href="lib/chosen/chosen.css" rel="stylesheet"/>
	<link href="lib/select2/select2.css" rel="stylesheet"/>
	<!-- Script Includes -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
	<script src="lib/chosen/chosen.jquery.js"></script>
	<script src="lib/select2/select2.js"></script>
	<!-- Page Scripts -->
	<script>
		var kalturaSession = "";
		var partnerId = 0;
		var page = <?php echo $page; ?>;

		function validEmail(input) {
			var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
			if(!filter.test(input.value)) {
				input.setCustomValidity("Invalid email");
				return false;
			}
			else {
				input.setCustomValidity('');
				return true;
			}
		}

		function validPassword(input) {
			if(input.value == '') {
				input.setCustomValidity("Please enter a password");
				return false;
			}
			else {
				input.setCustomValidity('');
				return true;
			}
		}

		//Calls getSession.php to actually sign into the Kaltura API and generate a session key
		function loginSubmit() {
			$('#loginButton').hide();
			$('#loginLoader').show();
			$.ajax({
				type: "POST",
				url: "getSession.php",
				data: {email: $('#email').val(), partnerId: 0, password: $('#password').val()}
			}).done(function(msg) {
				$('#loginLoader').hide();
				if(msg == "loginfail") {
					$('#loginButton').show();
					alert("Invalid username/password");
				}
				else {
					$('body').blur();
					response = $.parseJSON(msg);
					if(response[0] == 1) {
						kalturaSession = response[1];
						partnerId = response[2];
						showSetup();
					}
					else {
						partnerLogin(response);
					}
				}
			});
		}

		//This lets the user select a partner to log into
		//This is only displayed if there is more than one partner on an account
		function partnerLogin(response) {
			$('#email').attr("readonly", "readonly");
			$('#password').attr("readonly", "readonly");
			$.ajax({
				type: "POST",
				url: "partnerSelect.php",
				data: {response: response}
			}).done(function(msg) {
				$('#partnerDiv').html(msg);
				$('#email').keyup(function(event) {
					if(event.which == 13)
						partnerSubmit();
				});
				$('#password').keyup(function(event) {
					if(event.which == 13)
						partnerSubmit();
				});
				jQuery('.czntags').chosen({search_contains: true});
			});
		}

		//Submits the partner for login
		function partnerSubmit() {
			$('#sumbitPartner').hide();
			$('#loginLoader').show();
			$.ajax({
				type: "POST",
				url: "getSession.php",
				data: {email: $('#email').val(), password: $('#password').val(), partnerId: $('#partnerChoice').val()}
			}).done(function(msg) {
				$('#loginLoader').show();
				if(msg == "loginfail") {
					alert("Invalid username/password");
					$('#submitPartner').show();
				}
				else if(msg == 'idfail') {
					alert("Invalid Partner ID");
					$('#submitPartner').show();
				}
				else {
					response = $.parseJSON(msg);
					kalturaSession = response[1];
					partnerId = $('#partnerChoice').val();
					showSetup();
				}
			});
		}

		function showSetup() {
			$.ajax({
				type: 'POST',
				url: 'showSetup.php',
				data: {session: kalturaSession, partnerId: partnerId}
			}).done(function(msg) {
				$('#setupDiv').html(msg);
				$('#loginDiv').slideUp();
				jQuery('.czntags').chosen({search_contains: true});
			});
		}
	</script>
</head>
<body>
	<div id="parent" style="height: 100%;">
		<div id='setup'>
			<div id='title'><h1>Kaltura Media Page for Pages</h1></div>
			<div id='loginDiv'>
				<div id='signup' class='section'>
					Kaltura Account Credentials (sign up at: <a href='http://corp.kaltura.com/free-trial' target='_blank'>http://corp.kaltura.com/free-trial</a>)
				</div>
				<div id='login'>
					<form method='post' id='loginForm' action='javascript:loginSubmit();' class='box'>
						<div id='loginFields'>
							<div id='emailDiv' class='loginField'>
								<span>KMC Email: </span><input type='text' id='email' oninput='validEmail(this)' autofocus='autofocus' required>
							</div>
							<div id='passwordDiv' class='loginField'>
								<span style='margin-right: 13px;'>Password: </span><input type='password' id='password' oninput='validPassword(this)' required>
							</div>
							<div id='partnerDiv'></div>
							<div id='buttonDiv' class='loginField'>
								<input type='submit' class='btnLogin' value='Login' id='loginButton'>
								<img src='lib/loginLoader.gif' id='loginLoader' style='display: none;'>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div id='setupDiv'></div>
		</div>
	</div>
</body>
</html>