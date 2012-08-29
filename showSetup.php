<?php
//Includes the client library and starts a Kaltura session to access the API
//More informatation about this process can be found at
//http://knowledge.kaltura.com/introduction-kaltura-client-libraries
require_once('lib/php5/KalturaClient.php');
$config = new KalturaConfiguration($_REQUEST['partnerId']);
$config->serviceUrl = 'http://www.kaltura.com/';
$client = new KalturaClient($config);
$client->setKs($_REQUEST['session']);

$client->startMultiRequest();

$filter = new KalturaUiConfFilter();
$filter->orderBy = '-createdAt';
$filter->objTypeEqual = KalturaUiConfObjType::PLAYER;
$filter->tagsMultiLikeOr = 'kdp3';
$pager = new KalturaFilterPager();
$pager->pageSize = 500;
$pager->pageIndex = 1;
$client->uiConf->listAction($filter, $pager);

$filter = new KalturaCategoryFilter();
$filter->orderBy = '-createdAt';
$pager = new KalturaFilterPager();
$pager->pageSize = 500;
$pager->pageIndex = 1;
$client->category->listAction($filter, $pager);

$filter = new KalturaPlaylistFilter();
$filter->orderBy = '-createdAt';
$pager = new KalturaFilterPager();
$pager->pageSize = 500;
$pager->pageIndex = 1;
$client->playlist->listAction($filter, $pager);

$multiRequest = $client->doMultiRequest();

?>
<script>
	//This keeps track of which radio button is checked
	var choice = $(':radio').first();
	//This is the amount of results per page to show in the selector
	var pageSize = 10;
	//This keeps track of all the videos that are selected
	var videos = "";
	
	$(document).ready(function() {
		$(':radio').click(function() {
			$('.videoSelect').select2('val', '');
			$('.thumbs').html('');
			choice = $(this);
			switch (choice.val()) {
				case 'cat':
					$('#playlists').hide();
					$('#categories').slideDown(200);
			      	break;
				case 'list':
					$('#categories').hide();
					$('#playlists').slideDown(200);
					break;
			}
		});
		
		$('.videoSelect').select2({
		    placeholder: "Video name/id",
		    minimumInputLength: 2,
		    allowClear: true,
		    initSelection: function (element, callback) {
		    	$(dataArray).each(function() {
					if (this.id == element.val()) {
						callback(this);
						return;
					}
				})
		    },
		    ajax: {
			    type: "POST",
			    url: "refreshSelector.php",
				data: function (term, page) {
					var content = new Array();
					switch (choice.val()) {
						case 'cat':
							content.push('cat',$('#categoryChoice').val());
					      	break;
						case 'list':
							content.push('list',$('#playlistChoice').val());
							break;
					}
		            return {
		                search: term,
		                content: content,
		                pageSize: pageSize,
		                page: page,
		                ignore: videos,
		                session: "<?php echo $_REQUEST['session']; ?>",
		                partnerId: <?php echo $_REQUEST['partnerId']; ?>
		            };
		        },
		        results: function (data, page) {
			        var result = jQuery.parseJSON(data);
			        var more = (page * pageSize) < result.count;
		            return {results: result.videos, more: more};
		        }
		    }
		});
		
		$('.contentChoice').change(function() {
			$('.videoSelect').select2('val', '');
			$('.thumbs').html('');
		});
		
		$('.videoSelect').on("change", function(e) {
			videos = "";
			var div = '#' + $(this)[0].id + 'Thumb';
			$.ajax({
				type: "POST",
				url: "getThumbnail.php",
				data: {session: kalturaSession, partnerId: partnerId, id: e.val}
			}).done(function(msg) {
				$(div).html(msg);
			});
			for(var i = 0; i < $('.featured').children(':input').length; ++i) {
				var video = $('.featured').children(':input')[i].value;
				if(video !== "") {
					videos += video + ',';
				}
			}
		});

		$('#submitButton').click(function() {
			$('#submitButton').hide();
			$('#submitLoader').show();
			var choices = new Array();
			choices.push($('#playerChoice').val());
			switch (choice.val()) {
				case 'cat':
					choices.push('cat',$('#categoryChoice').val());
			      	break;
				case 'list':
					choices.push('list',$('#playlistChoice').val());
					break;
			}
			choices.push(videos);
			$.ajax({
				type: "POST",
				url: "savePage.php",
				data: {session: kalturaSession, partnerId: partnerId, page: page, options: choices}
			}).done(function(msg) {
				$('#submitLoader').hide();
				$('#submitButton').show();
				if(msg == 'low') {
					alert("You must select 3 videos!");
				}
				else if(msg == 'success') {
					alert('Your changes have been submitted successfully!');
				}
				else {
					alert('Error: ' + msg);
				}
			});
		});
	});
</script>
<div id='playerTitle' class='section'>
	Player
</div>
<div id='playerDiv'>
	<span style='display: block;'>Select a Player</span>
	<select data-placeholder="Choose a Player" id="playerChoice" class="czntags" style="width:400px;" tabindex="1">
		<?php
		foreach($multiRequest[0]->objects as $player)
				echo '<option value="'.$player->id.'">'.$player->id.': '.$player->name.'</option>';
		?>
	</select>
</div>
<div id='content' class='section'>
	Media Content
</div>
<div id='contentDiv'>
	<div id="selectors">
		<div id='radio'>
			<input type='radio' name='type' value='cat' checked> Categories <input type='radio' name='type' value='list'> Playlist
		</div>
		<div id='categories'>
			<span style='display: block;'>Select a Category</span>
			<select data-placeholder="Select a Category" id="categoryChoice" class="czntags contentChoice" style="width:400px;" tabindex="1">
				<?php
				foreach($multiRequest[1]->objects as $category)
						echo '<option value="'.$category->id.'">'.$category->id.': '.$category->name.'</option>';
				?>
			</select>
		</div>
		<div id='playlists'>
			<span style='display: block;'>Select a Playlist</span>
			<select data-placeholder="Select a Playlist" id="playlistChoice" class="czntags contentChoice" style="width:400px;" tabindex="1">
				<?php
				foreach($multiRequest[2]->objects as $playlist)
						echo '<option value="'.$playlist->id.'">'.$playlist->id.': '.$playlist->name.'</option>';
				?>
			</select>
		</div>
	</div>
	<div id="featured">
		<span style='display: block;'>Pick 3 Featured Videos (search based on id, name, description, tags):</span>
		<div id="firstVideo" class="featured">
			<input id="first" type="hidden" class="videoSelect"/>
		</div>
		<div id="secondVideo" class="featured">
			<input id="second" type="hidden" class="videoSelect"/>
		</div>
		<div id="thirdVideo" class="featured">
			<input id="third" type="hidden" class="videoSelect"/>
		</div>
		<div style="clear: both"></div>
	</div>
	<div id="featuredThumbs">
		<div id="firstThumb" class="thumbs"></div>
		<div id="secondThumb" class="thumbs"></div>
		<div id="thirdThumb" class="thumbs"></div>
		<div style="clear: both"></div>
	</div>
	<div id='submitButtonDiv' class='loginField'>
		<input type='submit' class='btnLogin submit' value='Submit' id='submitButton'>
		<img src='lib/loginLoader.gif' id='submitLoader' style='display: none;'>
	</div>
</div>