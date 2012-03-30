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

        // Do not continue if the device is not a blackberry or if we are not on the login page
        //if ( !isBlackberry || !$('body').hasClass('accountLogin') ){ return this; }
        
        // Try to get the login from the cookie
        $login.val($.cookie('userLogin') || '');
        
        // On form submit, store the login into a cookie
        $('#frmLogin').bind('submit', function()
        {
            $.cookie('userLogin', ($login.val() || ''), {expires: 18, path: '/'})
        });
    }
}

//$(document).ready(function(){ accountLogin.init(); })