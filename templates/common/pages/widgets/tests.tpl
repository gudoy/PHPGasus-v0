{*
<div class="box">
	<h2>HTML 5 audio tag</h2>
	<div>
		<audio src="{$smarty.const._URL_AUDIOS}products/samples/audioSample_p119.ogg" controls="true" autobuffer="true">
		</audio>
	</div>
</div>

<div class="box">
	<h2>HTML 5 video tag</h2>
	
</div>
  *}
<div class="box">
	<h2>Flashlite test - JW Player 3.x</h2>
	<div>
		{*
		<div id="container"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.</div>
		<script type="text/javascript" src="{$smarty.const._URL_JAVASCRIPTS}libs/swfobject.js"></script>
		<script type="text/javascript">
			var s1 = new SWFObject("{$smarty.const._URL_FLASHS}mediaplayer.swf","mediaplayer","300","185","8");
			s1.addParam("allowfullscreen","true");
			s1.addVariable("width","300");
			s1.addVariable("height","185");
			s1.addVariable("file","video.flv");
			s1.write("container");
		</script>
		*}
		{*
		<div id="container">
			<embed type="application/x-shockwave-flash" src="http://dev.soundwalk.com/public/media/flash/mediaplayer.swf" style="" id="mediaplayer" name="mediaplayer" quality="high" allowfullscreen="true" flashvars="width=300&amp;height=185&amp;file=audio.mp3" height="185" width="300">
		</div>
		*}

		{*
		<object id="player" name="player" data="http://dev.soundwalk.com/public/media/flash/mediaplayer.swf" width="300" height="185">
			<param name="movie" value="http://dev.soundwalk.com/public/media/flash/mediaplayer.swf" />
			<param name="allowfullscreen" value="true" />
			<param name="allowscriptaccess" value="always" />
			<param name="flashvars" value="width=300&amp;height=185&amp;file=video.flv" />
			<p><a href="http://get.adobe.com/flashplayer">Get Flash</a> to see this player.</p>
		</object>
		*}
		
	    <object type="application/x-shockwave-flash" data="http://dev.soundwalk.com/public/media/flash/dewplayer.swf?mp3={'http://dev.soundwalk.com/public/media/flash/'|urlencode}mp3/moldau.mp3" width="200" height="20" id="dewplayer">
	    	<param name="wmode" value="transparent" />
	    	<param name="movie" value="http://dev.soundwalk.com/public/media/flash/dewplayer.swf?mp3={'http://dev.soundwalk.com/public/media/flash/'|urlencode}mp3/moldau.mp3" />
		</object>
	</div>
</div>