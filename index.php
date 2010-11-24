<!DOCTYPE html> 
<html>
<head> 
<meta id="meta" name="viewport" content="width=320; user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<title>Status Updates</title> 

<link rel="apple-touch-icon" href="/apple-touch-icon.png" />

<style type="text/css" media="screen"> 
	body { background-color: #fff; font: 16px Helvetica, Arial; color: #333; }
	#wrapper { margin:0 auto; padding: 0; width:304px; }
	h1, h2 { margin-top: 5px; }
	h2 { background: red; color: #FFF; text-align: center; }
	p { margin-bottom: 10px; }
	#spinner { float: right; margin-top: 5px; }
	select { font-size: 16px; margin-bottom: 10px; }
	#new { font-size: 1.1em; width: 98%; }
	#less { display: none; }
	#latest p, #history p { padding: 5px; -webkit-border-radius: 3px; position: relative; }
	#latest img { cursor: pointer; position: absolute; top: -7px; right: -7px; }
	button.fancy {
		background: #bfbfbf;
		background: -moz-linear-gradient(0% 100% 90deg, #bbb, #e5e5e5);
		background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#e5e5e5), to(#bbb));
		border: 1px solid #ccc;
		border-radius: 3px;
		-moz-border-radius: 3px;
		-webkit-border-radius: 3px;
		box-shadow: inset 0px 1px 3px #f5f5f5;
		-moz-box-shadow: inset 0px 1px 3px #f5f5f5;
		-webkit-box-shadow: inset 0px 1px 3px #f5f5f5;
		color: #333;
		font-family: "lucida grande", sans-serif;
		font-size: 12px;
		font-weight: bold;
		line-height: 1;
		padding: 6px 0;
		text-align: center;
		text-shadow: 0 1px 0px #eee;
		width: 50px;
	}
	button.fancy:hover {
		background: #cfcfcf;
		background: -moz-linear-gradient(0% 100% 90deg, #aaa, #d5d5d5);
		background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#d5d5d5), to(#aaa));
		box-shadow: inset 0px 1px 3px #e5e5e5;
		-moz-box-shadow: inset 0px 1px 3px #e5e5e5;
		-webkit-box-shadow: inset 0px 1px 3px #e5e5e5;
	}
	button.fancy:active {
		background: #bfbfbf;
		background: -moz-linear-gradient(0% 100% 90deg, #e5e5e5, #bbb);
		background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#bbb), to(#e5e5e5));
	}
	
	.bubble {
		position:relative;
		padding:15px;
		margin:10px 0 20px;
		color:#333;
		background:#ddd url('heart.png') 95% 40% no-repeat;

		/* css3 */
		-moz-border-radius:10px;
		-webkit-border-radius:10px;
		border-radius:10px;
	}

	.bubble p {font-size:36px; font-weight:bold; margin:0;}

	/* creates the triangle */
	.bubble:after {
		content:"\00a0";
		display:block; /* reduce the damage in FF3.0 */
		position:absolute;
		z-index:99;
		bottom:-30px;
		left:50px;
		width:0;
		height:0;
		border-width:15px 15px;
		border-style: solid;
		border-color: #ddd transparent transparent;
	}
	
	.right { float:right; }
	.clear { clear:both; height:0; }
	.resolved { background: #7d7; }
	.high { background: #f77; }
	.low { background: #ff7; }
</style> 

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript">
	function get_cookie ( cookie_name ){
		var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );
		if ( results )
			return ( unescape ( results[2] ) );
		else
			return null;
	}
	function isEmpty(obj) {
		for(var prop in obj) {
			if(obj.hasOwnProperty(prop))
				return false;
		}
		return true;
	}
	$(function() {
		var tech = get_cookie('your_user'), //check your cookies for a user
			$spinner = $('#spinner');
		
		function getLatest(){
			$spinner.show(); $('#wrapper').css('opacity', .5);
			$('#latest').empty()
			var latest;
			$.getJSON('/latest.json', function(updates){
				for(var x in updates)
					$('<p class="'+updates[x].status+'"><strong>' + x + '</strong> ('+updates[x].tech+'):<br /> ' + updates[x].message + ' #denver <img src="x.png" /></p>').hide().prependTo('#latest').fadeIn('slow');
				
				if(isEmpty(updates))
					$('<p>No active Denver message.</p>').prependTo('#latest');
			});
		}
		
		function expire(which){
			$spinner.show();  $('#wrapper').css('opacity', .5);
			$.post('/update.php', {
				expire: true
				, tech: tech
				, office: which
			}, getLatest);
		}
		
		$('#send').click(function(){
			$spinner.show(); $('#wrapper').css('opacity', .5);
			$.post('/update.php', {
				update: $('#new').val().replace('\n',' ')
				, tech: tech
				, status: $('#status').val()
				, private: $('#private').attr('checked')
			}, getLatest);
			$('#new').val('');
		});
		
		$('#all').click(function(){
			$spinner.show(); $('#wrapper').css('opacity', .5);
			$(this).fadeTo('fast', 0.33, function(){
				$.getJSON('/updates.json', function(updates){
					$('#history').empty();
					for(var x in updates){
						$('<p class="'+updates[x].status+'"><strong>' + x + '</strong> ('+updates[x].tech+'):<br /> ' + updates[x].message + '</p>').hide().appendTo('#history').fadeIn('slow');
					}
					$('#all').fadeTo(0,1).hide();
					$('#less').show();
					$spinner.hide();  $('#wrapper').css('opacity', 1);
				});
			});
		})
		
		$('#status').change(function(){
			$(this).attr('class','');
			$(this).addClass($(this).val());
		}).trigger('change');
		
		$('#less').click(function(){
			$('#all').show();
			$(this).hide();
			$('#history').empty();
		});
		
		$('#latest p img').live('click', function(){
			if($(this).parent('p').index('p:contains(denver)') >= 0)
				expire('denver');
			else if($(this).parent('p').index('p:contains(midwest)') >= 0)
				expire('midwest');
		});

		getLatest();
	});
</script>

</head> 

<body>
	<div id="wrapper">
		<!-- <h2>Workin' on it. Come back in a few.</h2> -->
		<div class="bubble"><p>From us, with</p></div>
		<p>
			Post an update! <small>No special characters, please.</small>
			<img src="http://img.integer.com/spinner.gif" id="spinner" />
		</p>
		<select id="status">
			<option value="high">High</option>
			<option value="low">Low</option>
			<option value="resolved">Resolved</option>
		</select>
		<input type="checkbox" id="private" value="private" />Private
		<textarea type="text" placeholder="New update" id="new"></textarea><br />
		<button class="fancy" id="send">Send</a>
		<div class="clear">&nbsp;</div>
		<div id="latest"></div>
		<button class="fancy" id="all">Show History</a><a class="fancy" id="less">Hide History</a>
		<div id="history"></div>
	</div>
</body> 
</html>