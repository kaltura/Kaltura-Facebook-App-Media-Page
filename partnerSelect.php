<?php
//This script displays the selector for choosing a partner on the account
	$response = $_REQUEST['response'];
?>
<span style='display: block; margin-bottom: 5px;'>Select a Partner ID</span>
<select id='partnerChoice' class='czntags' style='width:250px; margin: 0 auto;' tabindex="1">
	<?php
	for($i = 1; $i < $response[0] + 1; ++$i) {
		echo '<option value="'.$response[$i][0].'">'.$response[$i][0].': '.$response[$i][1].'</option>';
	}
	?>
</select>
<div id='partnerButton'>
	<button id='sumbitPartner' type='button' class='btnLogin' onclick='partnerSubmit()' style='margin-top: 5px;'>Login</button>
</div>