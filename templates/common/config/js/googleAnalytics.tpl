{if !strpos(strtolower($smarty.server.PHP_SELF), 'index.php/admin/') && !strpos(strtolower($smarty.server.PHP_SELF), 'index.php/api/') 
&& $smarty.const._APP_USE_GOOGLE_ANALYTICS && in_array($smarty.const._APP_CONTEXT, array('prod','preprod'))}
<script type="text/javascript">
//<![CDATA[
var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '{$smarty.const._APP_GOOGLE_ANALYTICS_UA}']);
  _gaq.push(['_trackPageview']);
  _gaq.push(['_setDomainName', ".{$smarty.const._DOMAIN}"]);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);
  })();
//]]>
</script>
{/if}