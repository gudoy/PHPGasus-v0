var accountLogin =
{
    init: function()
    {
        var self        = this,
           scr          = window.screen,
           $login       = $('#userEmail');
        
        // Get device resolution and orientation to post them throught the login form
        $('#deviceResolution').val(scr.width + 'x' + scr.height);
        $('#deviceOrientation').val(app.orientation || '');
        
        //$('input:first', '#frmLogin').focus();
        
        // Try to get the login from the cookie
        $login.val($.cookie('userLogin') || '');
        
        $(document)
        	// When the form is submited
			.on('submit', '#frmLogin', function(e)
			{
				// Mark is as submited (for css invalid forms proper styling)
				$(this).addClass('submited');

				// Store the login into a cookie
				$.cookie('userLogin', ($login.val() || ''), {expires: 18, path: '/'})
			})
    } 
}