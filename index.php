<?php
//This is the front page for the Page Tab that displays the Kaltura gallery

//Includes the config file that contains all the Facebook App info
require_once('config.php');
//If the user just added the Page Tab to their Facebook Page, take them to the 'Added Apps' screen for their Facebook Page
if(array_key_exists('tabs_added', $_REQUEST)) {
	$id = array_keys($_REQUEST['tabs_added']);
	$id = $id[0];
	//$page = json_decode(file_get_contents('https://graph.facebook.com/'.$id))->link;
	//header('Location: '.$page.'?v=app_'.APP_ID);
	$page = 'https://www.facebook.com/pages/edit/?id='.$id.'&sk=apps';
	header('Location: '.$page);
	die();
}
//Grab the signed_request from Facebook and store the Facebook Page id and the admin status of the user
$signed_request = $_REQUEST["signed_request"];
list($encoded_sig, $payload) = explode('.', $signed_request, 2);
$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
$admin = @$data['page']['admin'];
$pageId = @$data['page']['id'];
//Grabs the database of Facebook pages that have been configured to use the Page Tab
$pages = @file_get_contents(PAGES);
//Checks to see if the database exists
if($pages != '') {
	$pages = unserialize($pages);
	//If the Page Tab has been configured for this Facebook Page, grab the stored information
	if(array_key_exists($pageId, $pages)) {
		$page = $pages[$pageId];
		$id = $page['id'];
		$uiConfId = $page['player'];
		$partnerId = $page['partner'];
	}
	else {
		//If the user has admin capabilities for the Facebook Page, take them to the 'Added Apps' screen
		if($admin == 1) {
			$page = 'https://www.facebook.com/pages/edit/?id='.$pageId.'&sk=apps';
			echo '<script> window.top.location.href = "'.$page.'" </script>';
			die();
		}
		//Otherwise, take them back to the Facebook Page because the Page Tab is not ready for viewing
		echo '<script> window.top.location.href="http://facebook.com/'.$pageId.'"</script>';
		die();
	}
}
else {
	//If the user has admin capabilities for the Facebook Page, take them to the 'Added Apps' screen
	if($admin == 1) {
		$page = 'https://www.facebook.com/pages/edit/?id='.$pageId.'&sk=apps';
		echo '<script> window.top.location.href = "'.$page.'" </script>';
		die();
	}
	//Otherwise, take them back to the Facebook Page because the Page Tab is not ready for viewing
	echo '<script> window.top.location.href="http://facebook.com/'.$pageId.'"</script>';
	die();
}
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Media Page</title>
	<!-- Style Includes -->
	<link href="appStyle.css" rel="stylesheet"/>
	<link href="lib/loadmask/jquery.loadmask.css" rel="stylesheet" type="text/css"/>
	<!-- Script Includes -->
	<script src="http://cdnbakmi.kaltura.com/html5/html5lib/v1.6.12.40/mwEmbedLoader.php"></script>
	<script src="https://cdnbakmi.kaltura.com/html5/html5lib/v1.6.12.40/mwEmbedLoader.php"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
	<script src="lib/loadmask/jquery.loadmask.min.js"></script>
	<!-- Page Scripts -->
	<script>
		var uiConfId = "<?php echo $uiConfId; ?>";
		//Keeps track of the entry being viewed
		var entryId = 0;
		//Keeps track of the page being viewed
		var currentPage = 1;

		//When the document is ready, load the featured videos and the gallery of videos
		$(document).ready(function() {
			loadFeatured();
			showEntries(1, '');
			$('#searchBar').keyup(function(event) {
				if(event.which == 13)
					showEntries();
			});
		});

		//Gets the featured videos and displays them on the page
		function loadFeatured() {
			$.ajax({
				type: "POST",
				url: "getFeatured.php",
				data: {page: <?php echo $pageId; ?>}
			}).done(function(msg) {
				var response = jQuery.parseJSON(msg);
				loadVideo(response[1]);
				$('#featuredVideos').html(response[0]);
				entryId = response[1];
				$('a[id="' + entryId + '"]').children('#play').css('display', 'block');
				//Whenever a thumbnail is clicked, load the video and display the play image
				//over the thumbnail to indicate which video is playing
				$(".thumblink").click(function () {
					loadVideo($(this).attr('id'));
					if(entryId != 0)
						$('a[id="' + entryId + '"]').children('#play').hide();
					entryId = $(this).attr('id');
					$('a[id="' + entryId + '"]').children('#play').css('display', 'block');
					FB.Canvas.scrollTo(0,0);
			    });
			});
		}

		//Loads the video gallery and displays it
		function showEntries(pageNumber, terms) {
			if(terms == "")
				$('#searchBar').val('');
			currentSearch = $('#searchBar').val();
			$('body').mask("Loading...");
			$.ajax({
				type: "POST",
				url: "reloadEntries.php",
				data: {page: <?php echo $pageId; ?>, pagenum: pageNumber, search: $('#searchBar').val()}
			}).done(function(msg) {
				$('body').unmask();
				$('#entries').html(msg);
				$('a[id="' + entryId + '"]').children('#play').css('display', 'block');
				//Whenever a thumbnail is clicked, load the video and display the play image
				//over the thumbnail to indicate which video is playing
				$(".thumblink").click(function () {
					loadVideo($(this).attr('id'));
					if(entryId != 0)
						$('a[id="' + entryId + '"]').children('#play').hide();
					entryId = $(this).attr('id');
					$('a[id="' + entryId + '"]').children('#play').css('display', 'block');
					FB.Canvas.scrollTo(0,0);
			    });
			});
		}

		//Responds to the page number index that is clicked
		function pagerClicked (pageNumber, search) {
			currentPage = pageNumber;
			showEntries(pageNumber, search);
		}

		// Loads the video is a Kaltura Dynamic Player
		function loadVideo(entryId) {
	        if (window.kdp) {
                kWidget.destroy(window.kdp);
                delete(window.kdp);
	        }
	        var uniqid = +new Date();
	        var kdpId = 'kdptarget'+uniqid;
	        $('#player').html('<div id="'+kdpId+'" ></div>');
	        flashvars = {};
	        flashvars.externalInterfaceDisabled = false;
	        flashvars.autoplay = true;
	        flashvars.disableAlerts = true;
	        flashvars.entryId = entryId;
	        flashvars.autoPlay = true;
	        kWidget.embed({
                'targetId': kdpId,
                'wid': '_<?php echo $partnerId; ?>',
                'uiconf_id' : uiConfId,
                'entry_id' : entryId,
                'width': 400,
                'height': 300,
                'flashvars': flashvars,
                'readyCallback': function(playerId) {
                        window.kdp = $('#'+playerId).get(0);
                }
	        });
		}
	</script>
</head>
<body>
<div id="fb-root"></div>
<script>
	//Initiates the Facebook SDK
	//This particular application only needs it to perform FB.Canvas.scrollTo(0,0)
	//in case the video player is out of frame
	window.fbAsyncInit = function() {
		FB.init({
			appId      : 'YOUR_APP_ID', // App ID
			channelUrl : '//WWW.YOUR_DOMAIN.COM/channel.html', // Channel File
			status     : true, // check login status
			cookie     : true, // enable cookies to allow the server to access the session
			xfbml      : true  // parse XFBML
		});

    // Additional initialization code here
	};

	// Load the SDK Asynchronously
	(function(d){
		var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement('script'); js.id = id; js.async = true;
		js.src = "//connect.facebook.net/en_US/all.js";
		ref.parentNode.insertBefore(js, ref);
	}(document));
</script>
	<div id="page">
		<div id="player"></div>
		<div id="featuredVideos"></div>
		<div id="searchDiv">
			<span id="searchText">Search videos by name or description, or tags: </span><input type="text" id="searchBar" autofocus="autofocus">
			<button id="searchButton" class="searchButtonClass" type="button" onclick="showEntries()">Search</button>
			<button id="showButton" type="button" onclick="showEntries(1, '')">Show All</button>
		</div>
		<div id="entries"></div>
	</div>
</body>
</html>
