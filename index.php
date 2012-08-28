<?php 
print_r($_REQUEST);
$signed_request = $_REQUEST["signed_request"];
list($encoded_sig, $payload) = explode('.', $signed_request, 2); 
$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
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
Hello World
</body>
</html>