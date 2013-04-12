{if $smarty.const._APP_USE_FACEBOOK_LOGIN}
<div id="fb-root"></div>
<script>
  // Additional JS functions here
  window.fbAsyncInit = function() {
    FB.init({
      appId      : {$smarty.const._FACEBOOK_APP_ID}, // App ID
      channelUrl : '//WWW.YOUR_DOMAIN.COM/channel.html', // Channel File
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });

    // Additional init code here
	FB.getLoginStatus(function(response)
	{
console.log(response);
	  if (response.status === 'connected')
	  {
// TODO: POST to login with facebook oauth token
	    // connected
	  } else if (response.status === 'not_authorized') {
	    // not_authorized
	  } else {
	    // not_logged_in
	  }
	 });
  };

  // Load the SDK Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) { return; }
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));
</script>
{/if}
