<?php 
print_r($_REQUEST);
$signed_request = $_REQUEST['signed_request'];
list($encoded_sig, $payload) = explode('.', $signed_request, 2);
$sig = base64_url_decode($encoded_sig);
$data = json_decode(base64_url_decode($payload), true);
print_r($data);
?>

<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Media Page</title>
	<!-- Style Includes -->
	<!-- Script Includes -->
	<!-- Page Scripts -->
	<script>

	</script>
</head>
<body>
</body>
</html>