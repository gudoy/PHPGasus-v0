
    

  

<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
        <title>jquery.transition.js at master from lrbabe/jquery.transition.js - GitHub</title>
    <link rel="search" type="application/opensearchdescription+xml" href="/opensearch.xml" title="GitHub" />
    <link rel="fluid-icon" href="https://github.com/fluidicon.png" title="GitHub" />

    <link href="https://d3nwyuy0nl342s.cloudfront.net/e324fead64e046c62f66e88535a26d5d66aacf6f/stylesheets/bundle_common.css" media="screen" rel="stylesheet" type="text/css" />
<link href="https://d3nwyuy0nl342s.cloudfront.net/e324fead64e046c62f66e88535a26d5d66aacf6f/stylesheets/bundle_github.css" media="screen" rel="stylesheet" type="text/css" />
    

    <script type="text/javascript">
      if (typeof console == "undefined" || typeof console.log == "undefined")
        console = { log: function() {} }
    </script>
    <script type="text/javascript" charset="utf-8">
      var GitHub = {
        assetHost: 'https://d3nwyuy0nl342s.cloudfront.net'
      }
      var github_user = null
      
    </script>
    <script src="https://d3nwyuy0nl342s.cloudfront.net/e324fead64e046c62f66e88535a26d5d66aacf6f/javascripts/jquery/jquery-1.4.2.min.js" type="text/javascript"></script>
    <script src="https://d3nwyuy0nl342s.cloudfront.net/e324fead64e046c62f66e88535a26d5d66aacf6f/javascripts/bundle_common.js" type="text/javascript"></script>
<script src="https://d3nwyuy0nl342s.cloudfront.net/e324fead64e046c62f66e88535a26d5d66aacf6f/javascripts/bundle_github.js" type="text/javascript"></script>


    
    <script type="text/javascript" charset="utf-8">
      GitHub.spy({
        repo: "lrbabe/jquery.transition.js"
      })
    </script>

    
  <link href="https://github.com/lrbabe/jquery.transition.js/commits/master.atom" rel="alternate" title="Recent Commits to jquery.transition.js:master" type="application/atom+xml" />

        <meta name="description" content="a patched animate mechanism for jQuery using CSS3 transtion power" />
    <script type="text/javascript">
      GitHub.nameWithOwner = GitHub.nameWithOwner || "lrbabe/jquery.transition.js";
      GitHub.currentRef = 'master';
      GitHub.commitSHA = "009e33c73c7cee6f3b1aa5b2f08fc5391b8357b7";
      GitHub.currentPath = 'jquery.transition.js';
      GitHub.masterBranch = "master";

      
    </script>
  

        <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-3769691-2']);
      _gaq.push(['_setDomainName', 'none']);
      _gaq.push(['_trackPageview']);
      (function() {
        var ga = document.createElement('script');
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        ga.setAttribute('async', 'true');
        document.documentElement.firstChild.appendChild(ga);
      })();
    </script>

    
  </head>

  

  <body class="logged_out page-blob">
    

    

    

    
      <div id="site_alert">
        <form action="/translate?to=%2Flrbabe%2Fjquery.transition.js%2Fblob%2Fmaster%2Fjquery.transition.js" method="post"><div style="margin:0;padding:0"><input name="authenticity_token" type="hidden" value="f48685d6b856fb0d6f71b994bff692fa0b8cc92a" /></div>        <p>
          Would you rather see this site in French? (Préféreriez-vous voir ce site en Français?) &nbsp;
          <button class="minibutton" name="code" value="fr"><span>Yes (Oui)</span></button> &nbsp;
          <button class="minibutton" name="code" value="en"><span>No (Non)</span></button>
        </p>
        </form>      </div>
    

    

    <div class="subnavd" id="main">
      <div id="header" class="true">
        
          <a class="logo boring" href="https://github.com">
            <img alt="github" class="default" src="https://d3nwyuy0nl342s.cloudfront.net/images/modules/header/logov3.png" />
            <!--[if (gt IE 8)|!(IE)]><!-->
            <img alt="github" class="hover" src="https://d3nwyuy0nl342s.cloudfront.net/images/modules/header/logov3-hover.png" />
            <!--<![endif]-->
          </a>
        
        
        <div class="topsearch">
  
    <ul class="nav logged_out">
      <li class="pricing"><a href="/plans">Pricing and Signup</a></li>
      <li class="explore"><a href="/explore">Explore GitHub</a></li>
      <li class="features"><a href="/features">Features</a></li>
      <li class="blog"><a href="/blog">Blog</a></li>
      <li class="login"><a href="/login?return_to=https://github.com/lrbabe/jquery.transition.js/blob/master/jquery.transition.js">Login</a></li>
    </ul>
  
</div>

      </div>

      
      
        
    <div class="site">
      <div class="pagehead repohead vis-public    instapaper_ignore readability-menu">

      

      <div class="title-actions-bar">
        <h1>
          <a href="/lrbabe">lrbabe</a> / <strong><a href="https://github.com/lrbabe/jquery.transition.js">jquery.transition.js</a></strong>
          
          
        </h1>

        
    <ul class="actions">
      

      
        <li class="for-owner" style="display:none"><a href="https://github.com/lrbabe/jquery.transition.js/admin" class="minibutton btn-admin "><span><span class="icon"></span>Admin</span></a></li>
        <li>
          <a href="/lrbabe/jquery.transition.js/toggle_watch" class="minibutton btn-watch " id="watch_button" onclick="var f = document.createElement('form'); f.style.display = 'none'; this.parentNode.appendChild(f); f.method = 'POST'; f.action = this.href;var s = document.createElement('input'); s.setAttribute('type', 'hidden'); s.setAttribute('name', 'authenticity_token'); s.setAttribute('value', 'f48685d6b856fb0d6f71b994bff692fa0b8cc92a'); f.appendChild(s);f.submit();return false;" style="display:none"><span><span class="icon"></span>Watch</span></a>
          <a href="/lrbabe/jquery.transition.js/toggle_watch" class="minibutton btn-watch " id="unwatch_button" onclick="var f = document.createElement('form'); f.style.display = 'none'; this.parentNode.appendChild(f); f.method = 'POST'; f.action = this.href;var s = document.createElement('input'); s.setAttribute('type', 'hidden'); s.setAttribute('name', 'authenticity_token'); s.setAttribute('value', 'f48685d6b856fb0d6f71b994bff692fa0b8cc92a'); f.appendChild(s);f.submit();return false;" style="display:none"><span><span class="icon"></span>Unwatch</span></a>
        </li>
        
          
            <li class="for-notforked" style="display:none"><a href="/lrbabe/jquery.transition.js/fork" class="minibutton btn-fork " id="fork_button" onclick="var f = document.createElement('form'); f.style.display = 'none'; this.parentNode.appendChild(f); f.method = 'POST'; f.action = this.href;var s = document.createElement('input'); s.setAttribute('type', 'hidden'); s.setAttribute('name', 'authenticity_token'); s.setAttribute('value', 'f48685d6b856fb0d6f71b994bff692fa0b8cc92a'); f.appendChild(s);f.submit();return false;"><span><span class="icon"></span>Fork</span></a></li>
            <li class="for-hasfork" style="display:none"><a href="#" class="minibutton btn-fork " id="your_fork_button"><span><span class="icon"></span>Your Fork</span></a></li>
          

          
        
      
      
      <li class="repostats">
        <ul class="repo-stats">
          <li class="watchers"><a href="/lrbabe/jquery.transition.js/watchers" title="Watchers" class="tooltipped downwards">29</a></li>
          <li class="forks"><a href="/lrbabe/jquery.transition.js/network" title="Forks" class="tooltipped downwards">1</a></li>
        </ul>
      </li>
    </ul>

      </div>

        
  <ul class="tabs">
    <li><a href="https://github.com/lrbabe/jquery.transition.js" class="selected" highlight="repo_source">Source</a></li>
    <li><a href="https://github.com/lrbabe/jquery.transition.js/commits/master" highlight="repo_commits">Commits</a></li>
    <li><a href="/lrbabe/jquery.transition.js/network" highlight="repo_network">Network</a></li>
    <li><a href="/lrbabe/jquery.transition.js/pulls" highlight="repo_pulls">Pull Requests (0)</a></li>

    

    
      
      <li><a href="/lrbabe/jquery.transition.js/issues" highlight="issues">Issues (0)</a></li>
    

            
    <li><a href="/lrbabe/jquery.transition.js/graphs" highlight="repo_graphs">Graphs</a></li>

    <li class="contextswitch nochoices">
      <span class="toggle leftwards" >
        <em>Branch:</em>
        <code>master</code>
      </span>
    </li>
  </ul>

  <div style="display:none" id="pl-description"><p><em class="placeholder">click here to add a description</em></p></div>
  <div style="display:none" id="pl-homepage"><p><em class="placeholder">click here to add a homepage</em></p></div>

  <div class="subnav-bar">
  
  <ul>
    <li>
      
      <a href="/lrbabe/jquery.transition.js/branches" class="dropdown">Switch Branches (2)</a>
      <ul>
        
          
          
            <li><a href="/lrbabe/jquery.transition.js/blob/gh-pages/jquery.transition.js" action="show">gh-pages</a></li>
          
        
          
            <li><strong>master &#x2713;</strong></li>
            
      </ul>
    </li>
    <li>
      <a href="#" class="dropdown defunct">Switch Tags (0)</a>
      
    </li>
    <li>
    
    <a href="/lrbabe/jquery.transition.js/branches" class="manage">Branch List</a>
    
    </li>
  </ul>
</div>

  
  
  
  
  
  



        
    <div id="repo_details" class="metabox clearfix">
      <div id="repo_details_loader" class="metabox-loader" style="display:none">Sending Request&hellip;</div>

        <a href="/lrbabe/jquery.transition.js/downloads" class="download-source" id="download_button" title="Download source, tagged packages and binaries."><span class="icon"></span>Downloads</a>

      <div id="repository_desc_wrapper">
      <div id="repository_description" rel="repository_description_edit">
        
          <p>a patched animate mechanism for jQuery using CSS3 transtion power
            <span id="read_more" style="display:none">&mdash; <a href="#readme">Read more</a></span>
          </p>
        
      </div>

      <div id="repository_description_edit" style="display:none;" class="inline-edit">
        <form action="/lrbabe/jquery.transition.js/admin/update" method="post"><div style="margin:0;padding:0"><input name="authenticity_token" type="hidden" value="f48685d6b856fb0d6f71b994bff692fa0b8cc92a" /></div>
          <input type="hidden" name="field" value="repository_description">
          <input type="text" class="textfield" name="value" value="a patched animate mechanism for jQuery using CSS3 transtion power">
          <div class="form-actions">
            <button class="minibutton"><span>Save</span></button> &nbsp; <a href="#" class="cancel">Cancel</a>
          </div>
        </form>
      </div>

      
      <div class="repository-homepage" id="repository_homepage" rel="repository_homepage_edit">
        <p><a href="http://" rel="nofollow"></a></p>
      </div>

      <div id="repository_homepage_edit" style="display:none;" class="inline-edit">
        <form action="/lrbabe/jquery.transition.js/admin/update" method="post"><div style="margin:0;padding:0"><input name="authenticity_token" type="hidden" value="f48685d6b856fb0d6f71b994bff692fa0b8cc92a" /></div>
          <input type="hidden" name="field" value="repository_homepage">
          <input type="text" class="textfield" name="value" value="">
          <div class="form-actions">
            <button class="minibutton"><span>Save</span></button> &nbsp; <a href="#" class="cancel">Cancel</a>
          </div>
        </form>
      </div>
      </div>
      <div class="rule "></div>
            <div id="url_box" class="url-box">
        <ul class="clone-urls">
          
            
            <li id="http_clone_url"><a href="https://github.com/lrbabe/jquery.transition.js.git" data-permissions="Read-Only">HTTP</a></li>
            <li id="public_clone_url"><a href="git://github.com/lrbabe/jquery.transition.js.git" data-permissions="Read-Only">Git Read-Only</a></li>
          
          
        </ul>
        <input type="text" spellcheck="false" id="url_field" class="url-field" />
              <span style="display:none" id="url_box_clippy"></span>
      <span id="clippy_tooltip_url_box_clippy" class="clippy-tooltip tooltipped" title="copy to clipboard">
      <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
              width="14"
              height="14"
              class="clippy"
              id="clippy" >
      <param name="movie" value="https://d3nwyuy0nl342s.cloudfront.net/flash/clippy.swf?v5"/>
      <param name="allowScriptAccess" value="always" />
      <param name="quality" value="high" />
      <param name="scale" value="noscale" />
      <param NAME="FlashVars" value="id=url_box_clippy&amp;copied=&amp;copyto=">
      <param name="bgcolor" value="#FFFFFF">
      <param name="wmode" value="opaque">
      <embed src="https://d3nwyuy0nl342s.cloudfront.net/flash/clippy.swf?v5"
             width="14"
             height="14"
             name="clippy"
             quality="high"
             allowScriptAccess="always"
             type="application/x-shockwave-flash"
             pluginspage="http://www.macromedia.com/go/getflashplayer"
             FlashVars="id=url_box_clippy&amp;copied=&amp;copyto="
             bgcolor="#FFFFFF"
             wmode="opaque"
      />
      </object>
      </span>

        <p id="url_description">This URL has <strong>Read+Write</strong> access</p>
      </div>
    </div>

    <div class="frame frame-center tree-finder" style="display:none">
      <div class="breadcrumb">
        <b><a href="/lrbabe/jquery.transition.js">jquery.transition.js</a></b> /
        <input class="tree-finder-input" type="text" name="query" autocomplete="off" spellcheck="false">
      </div>

      
        <div class="octotip">
          <p>
            <a href="/lrbabe/jquery.transition.js/dismiss-tree-finder-help" class="dismiss js-dismiss-tree-list-help" title="Hide this notice forever">Dismiss</a>
            <strong>Octotip:</strong> You've activated the <em>file finder</em> by pressing <span class="kbd">t</span>
            Start typing to filter the file list. Use <span class="kbd badmono">↑</span> and <span class="kbd badmono">↓</span> to navigate,
            <span class="kbd">enter</span> to view files.
          </p>
        </div>
      

      <table class="tree-browser" cellpadding="0" cellspacing="0">
        <tr class="js-header"><th>&nbsp;</th><th>name</th></tr>
        <tr class="js-no-results no-results" style="display: none">
          <th colspan="2">No matching files</th>
        </tr>
        <tbody class="js-results-list">
        </tbody>
      </table>
    </div>

    <div id="jump-to-line" style="display:none">
      <h2>Jump to Line</h2>
      <form>
        <input class="textfield" type="text">
        <div class="full-button">
          <button type="submit" class="classy">
            <span>Go</span>
          </button>
        </div>
      </form>
    </div>


        

      </div><!-- /.pagehead -->

      

  





<script type="text/javascript">
  GitHub.downloadRepo = '/lrbabe/jquery.transition.js/archives/master'
  GitHub.revType = "master"

  GitHub.controllerName = "blob"
  GitHub.actionName     = "show"
  GitHub.currentAction  = "blob#show"

  

  
</script>






<div class="flash-messages"></div>


  <div id="commit">
    <div class="group">
        
  <div class="envelope commit">
    <div class="human">
      
        <div class="message"><pre><a href="/lrbabe/jquery.transition.js/commit/009e33c73c7cee6f3b1aa5b2f08fc5391b8357b7">improvements to the README</a> </pre></div>
      

      <div class="actor">
        <div class="gravatar">
          
          <img src="https://secure.gravatar.com/avatar/bc7a651b46f32049177137347698cdc0?s=140&d=https://d3nwyuy0nl342s.cloudfront.net%2Fimages%2Fgravatars%2Fgravatar-140.png" alt="" width="30" height="30"  />
        </div>
        <div class="name">louisremi <span>(author)</span></div>
        <div class="date">
          <abbr class="relatize" title="2011-04-03 03:49:44">Sun Apr 03 03:49:44 -0700 2011</abbr>
        </div>
      </div>

      

    </div>
    <div class="machine">
      <span>c</span>ommit&nbsp;&nbsp;<a href="/lrbabe/jquery.transition.js/commit/009e33c73c7cee6f3b1aa5b2f08fc5391b8357b7" hotkey="c">009e33c73c7cee6f3b1a</a><br />
      <span>t</span>ree&nbsp;&nbsp;&nbsp;&nbsp;<a href="/lrbabe/jquery.transition.js/tree/009e33c73c7cee6f3b1aa5b2f08fc5391b8357b7" hotkey="t">8fa75a6e624a270a717a</a><br />
      
        <span>p</span>arent&nbsp;
        
        <a href="/lrbabe/jquery.transition.js/tree/eb8419a1681b09052d75b99f5baf96b02e7bb7fa" hotkey="p">eb8419a1681b09052d75</a>
      

    </div>
  </div>

    </div>
  </div>



  <div id="slider">

  

    <div class="breadcrumb" data-path="jquery.transition.js/">
      <b><a href="/lrbabe/jquery.transition.js/tree/009e33c73c7cee6f3b1aa5b2f08fc5391b8357b7">jquery.transition.js</a></b> / jquery.transition.js       <span style="display:none" id="clippy_4998">jquery.transition.js</span>
      
      <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
              width="110"
              height="14"
              class="clippy"
              id="clippy" >
      <param name="movie" value="https://d3nwyuy0nl342s.cloudfront.net/flash/clippy.swf?v5"/>
      <param name="allowScriptAccess" value="always" />
      <param name="quality" value="high" />
      <param name="scale" value="noscale" />
      <param NAME="FlashVars" value="id=clippy_4998&amp;copied=copied!&amp;copyto=copy to clipboard">
      <param name="bgcolor" value="#FFFFFF">
      <param name="wmode" value="opaque">
      <embed src="https://d3nwyuy0nl342s.cloudfront.net/flash/clippy.swf?v5"
             width="110"
             height="14"
             name="clippy"
             quality="high"
             allowScriptAccess="always"
             type="application/x-shockwave-flash"
             pluginspage="http://www.macromedia.com/go/getflashplayer"
             FlashVars="id=clippy_4998&amp;copied=copied!&amp;copyto=copy to clipboard"
             bgcolor="#FFFFFF"
             wmode="opaque"
      />
      </object>
      

    </div>

    <div class="frames">
      <div class="frame frame-center" data-path="jquery.transition.js/">
        
          <ul class="big-actions">
            
            <li><a class="file-edit-link minibutton" href="/lrbabe/jquery.transition.js/file-edit/__current_ref__/jquery.transition.js"><span>Edit this file</span></a></li>
          </ul>
        

        <div id="files">
          <div class="file">
            <div class="meta">
              <div class="info">
                <span class="icon"><img alt="Txt" height="16" src="https://d3nwyuy0nl342s.cloudfront.net/images/icons/txt.png" width="16" /></span>
                <span class="mode" title="File Mode">100644</span>
                
                  <span>703 lines (579 sloc)</span>
                
                <span>18.693 kb</span>
              </div>
              <ul class="actions">
                <li><a href="/lrbabe/jquery.transition.js/raw/master/jquery.transition.js" id="raw-url">raw</a></li>
                
                  <li><a href="/lrbabe/jquery.transition.js/blame/master/jquery.transition.js">blame</a></li>
                
                <li><a href="/lrbabe/jquery.transition.js/commits/master/jquery.transition.js">history</a></li>
              </ul>
            </div>
            
  <div class="data type-javascript">
    
      <table cellpadding="0" cellspacing="0">
        <tr>
          <td>
            <pre class="line_numbers"><span id="L1" rel="#L1">1</span>
<span id="L2" rel="#L2">2</span>
<span id="L3" rel="#L3">3</span>
<span id="L4" rel="#L4">4</span>
<span id="L5" rel="#L5">5</span>
<span id="L6" rel="#L6">6</span>
<span id="L7" rel="#L7">7</span>
<span id="L8" rel="#L8">8</span>
<span id="L9" rel="#L9">9</span>
<span id="L10" rel="#L10">10</span>
<span id="L11" rel="#L11">11</span>
<span id="L12" rel="#L12">12</span>
<span id="L13" rel="#L13">13</span>
<span id="L14" rel="#L14">14</span>
<span id="L15" rel="#L15">15</span>
<span id="L16" rel="#L16">16</span>
<span id="L17" rel="#L17">17</span>
<span id="L18" rel="#L18">18</span>
<span id="L19" rel="#L19">19</span>
<span id="L20" rel="#L20">20</span>
<span id="L21" rel="#L21">21</span>
<span id="L22" rel="#L22">22</span>
<span id="L23" rel="#L23">23</span>
<span id="L24" rel="#L24">24</span>
<span id="L25" rel="#L25">25</span>
<span id="L26" rel="#L26">26</span>
<span id="L27" rel="#L27">27</span>
<span id="L28" rel="#L28">28</span>
<span id="L29" rel="#L29">29</span>
<span id="L30" rel="#L30">30</span>
<span id="L31" rel="#L31">31</span>
<span id="L32" rel="#L32">32</span>
<span id="L33" rel="#L33">33</span>
<span id="L34" rel="#L34">34</span>
<span id="L35" rel="#L35">35</span>
<span id="L36" rel="#L36">36</span>
<span id="L37" rel="#L37">37</span>
<span id="L38" rel="#L38">38</span>
<span id="L39" rel="#L39">39</span>
<span id="L40" rel="#L40">40</span>
<span id="L41" rel="#L41">41</span>
<span id="L42" rel="#L42">42</span>
<span id="L43" rel="#L43">43</span>
<span id="L44" rel="#L44">44</span>
<span id="L45" rel="#L45">45</span>
<span id="L46" rel="#L46">46</span>
<span id="L47" rel="#L47">47</span>
<span id="L48" rel="#L48">48</span>
<span id="L49" rel="#L49">49</span>
<span id="L50" rel="#L50">50</span>
<span id="L51" rel="#L51">51</span>
<span id="L52" rel="#L52">52</span>
<span id="L53" rel="#L53">53</span>
<span id="L54" rel="#L54">54</span>
<span id="L55" rel="#L55">55</span>
<span id="L56" rel="#L56">56</span>
<span id="L57" rel="#L57">57</span>
<span id="L58" rel="#L58">58</span>
<span id="L59" rel="#L59">59</span>
<span id="L60" rel="#L60">60</span>
<span id="L61" rel="#L61">61</span>
<span id="L62" rel="#L62">62</span>
<span id="L63" rel="#L63">63</span>
<span id="L64" rel="#L64">64</span>
<span id="L65" rel="#L65">65</span>
<span id="L66" rel="#L66">66</span>
<span id="L67" rel="#L67">67</span>
<span id="L68" rel="#L68">68</span>
<span id="L69" rel="#L69">69</span>
<span id="L70" rel="#L70">70</span>
<span id="L71" rel="#L71">71</span>
<span id="L72" rel="#L72">72</span>
<span id="L73" rel="#L73">73</span>
<span id="L74" rel="#L74">74</span>
<span id="L75" rel="#L75">75</span>
<span id="L76" rel="#L76">76</span>
<span id="L77" rel="#L77">77</span>
<span id="L78" rel="#L78">78</span>
<span id="L79" rel="#L79">79</span>
<span id="L80" rel="#L80">80</span>
<span id="L81" rel="#L81">81</span>
<span id="L82" rel="#L82">82</span>
<span id="L83" rel="#L83">83</span>
<span id="L84" rel="#L84">84</span>
<span id="L85" rel="#L85">85</span>
<span id="L86" rel="#L86">86</span>
<span id="L87" rel="#L87">87</span>
<span id="L88" rel="#L88">88</span>
<span id="L89" rel="#L89">89</span>
<span id="L90" rel="#L90">90</span>
<span id="L91" rel="#L91">91</span>
<span id="L92" rel="#L92">92</span>
<span id="L93" rel="#L93">93</span>
<span id="L94" rel="#L94">94</span>
<span id="L95" rel="#L95">95</span>
<span id="L96" rel="#L96">96</span>
<span id="L97" rel="#L97">97</span>
<span id="L98" rel="#L98">98</span>
<span id="L99" rel="#L99">99</span>
<span id="L100" rel="#L100">100</span>
<span id="L101" rel="#L101">101</span>
<span id="L102" rel="#L102">102</span>
<span id="L103" rel="#L103">103</span>
<span id="L104" rel="#L104">104</span>
<span id="L105" rel="#L105">105</span>
<span id="L106" rel="#L106">106</span>
<span id="L107" rel="#L107">107</span>
<span id="L108" rel="#L108">108</span>
<span id="L109" rel="#L109">109</span>
<span id="L110" rel="#L110">110</span>
<span id="L111" rel="#L111">111</span>
<span id="L112" rel="#L112">112</span>
<span id="L113" rel="#L113">113</span>
<span id="L114" rel="#L114">114</span>
<span id="L115" rel="#L115">115</span>
<span id="L116" rel="#L116">116</span>
<span id="L117" rel="#L117">117</span>
<span id="L118" rel="#L118">118</span>
<span id="L119" rel="#L119">119</span>
<span id="L120" rel="#L120">120</span>
<span id="L121" rel="#L121">121</span>
<span id="L122" rel="#L122">122</span>
<span id="L123" rel="#L123">123</span>
<span id="L124" rel="#L124">124</span>
<span id="L125" rel="#L125">125</span>
<span id="L126" rel="#L126">126</span>
<span id="L127" rel="#L127">127</span>
<span id="L128" rel="#L128">128</span>
<span id="L129" rel="#L129">129</span>
<span id="L130" rel="#L130">130</span>
<span id="L131" rel="#L131">131</span>
<span id="L132" rel="#L132">132</span>
<span id="L133" rel="#L133">133</span>
<span id="L134" rel="#L134">134</span>
<span id="L135" rel="#L135">135</span>
<span id="L136" rel="#L136">136</span>
<span id="L137" rel="#L137">137</span>
<span id="L138" rel="#L138">138</span>
<span id="L139" rel="#L139">139</span>
<span id="L140" rel="#L140">140</span>
<span id="L141" rel="#L141">141</span>
<span id="L142" rel="#L142">142</span>
<span id="L143" rel="#L143">143</span>
<span id="L144" rel="#L144">144</span>
<span id="L145" rel="#L145">145</span>
<span id="L146" rel="#L146">146</span>
<span id="L147" rel="#L147">147</span>
<span id="L148" rel="#L148">148</span>
<span id="L149" rel="#L149">149</span>
<span id="L150" rel="#L150">150</span>
<span id="L151" rel="#L151">151</span>
<span id="L152" rel="#L152">152</span>
<span id="L153" rel="#L153">153</span>
<span id="L154" rel="#L154">154</span>
<span id="L155" rel="#L155">155</span>
<span id="L156" rel="#L156">156</span>
<span id="L157" rel="#L157">157</span>
<span id="L158" rel="#L158">158</span>
<span id="L159" rel="#L159">159</span>
<span id="L160" rel="#L160">160</span>
<span id="L161" rel="#L161">161</span>
<span id="L162" rel="#L162">162</span>
<span id="L163" rel="#L163">163</span>
<span id="L164" rel="#L164">164</span>
<span id="L165" rel="#L165">165</span>
<span id="L166" rel="#L166">166</span>
<span id="L167" rel="#L167">167</span>
<span id="L168" rel="#L168">168</span>
<span id="L169" rel="#L169">169</span>
<span id="L170" rel="#L170">170</span>
<span id="L171" rel="#L171">171</span>
<span id="L172" rel="#L172">172</span>
<span id="L173" rel="#L173">173</span>
<span id="L174" rel="#L174">174</span>
<span id="L175" rel="#L175">175</span>
<span id="L176" rel="#L176">176</span>
<span id="L177" rel="#L177">177</span>
<span id="L178" rel="#L178">178</span>
<span id="L179" rel="#L179">179</span>
<span id="L180" rel="#L180">180</span>
<span id="L181" rel="#L181">181</span>
<span id="L182" rel="#L182">182</span>
<span id="L183" rel="#L183">183</span>
<span id="L184" rel="#L184">184</span>
<span id="L185" rel="#L185">185</span>
<span id="L186" rel="#L186">186</span>
<span id="L187" rel="#L187">187</span>
<span id="L188" rel="#L188">188</span>
<span id="L189" rel="#L189">189</span>
<span id="L190" rel="#L190">190</span>
<span id="L191" rel="#L191">191</span>
<span id="L192" rel="#L192">192</span>
<span id="L193" rel="#L193">193</span>
<span id="L194" rel="#L194">194</span>
<span id="L195" rel="#L195">195</span>
<span id="L196" rel="#L196">196</span>
<span id="L197" rel="#L197">197</span>
<span id="L198" rel="#L198">198</span>
<span id="L199" rel="#L199">199</span>
<span id="L200" rel="#L200">200</span>
<span id="L201" rel="#L201">201</span>
<span id="L202" rel="#L202">202</span>
<span id="L203" rel="#L203">203</span>
<span id="L204" rel="#L204">204</span>
<span id="L205" rel="#L205">205</span>
<span id="L206" rel="#L206">206</span>
<span id="L207" rel="#L207">207</span>
<span id="L208" rel="#L208">208</span>
<span id="L209" rel="#L209">209</span>
<span id="L210" rel="#L210">210</span>
<span id="L211" rel="#L211">211</span>
<span id="L212" rel="#L212">212</span>
<span id="L213" rel="#L213">213</span>
<span id="L214" rel="#L214">214</span>
<span id="L215" rel="#L215">215</span>
<span id="L216" rel="#L216">216</span>
<span id="L217" rel="#L217">217</span>
<span id="L218" rel="#L218">218</span>
<span id="L219" rel="#L219">219</span>
<span id="L220" rel="#L220">220</span>
<span id="L221" rel="#L221">221</span>
<span id="L222" rel="#L222">222</span>
<span id="L223" rel="#L223">223</span>
<span id="L224" rel="#L224">224</span>
<span id="L225" rel="#L225">225</span>
<span id="L226" rel="#L226">226</span>
<span id="L227" rel="#L227">227</span>
<span id="L228" rel="#L228">228</span>
<span id="L229" rel="#L229">229</span>
<span id="L230" rel="#L230">230</span>
<span id="L231" rel="#L231">231</span>
<span id="L232" rel="#L232">232</span>
<span id="L233" rel="#L233">233</span>
<span id="L234" rel="#L234">234</span>
<span id="L235" rel="#L235">235</span>
<span id="L236" rel="#L236">236</span>
<span id="L237" rel="#L237">237</span>
<span id="L238" rel="#L238">238</span>
<span id="L239" rel="#L239">239</span>
<span id="L240" rel="#L240">240</span>
<span id="L241" rel="#L241">241</span>
<span id="L242" rel="#L242">242</span>
<span id="L243" rel="#L243">243</span>
<span id="L244" rel="#L244">244</span>
<span id="L245" rel="#L245">245</span>
<span id="L246" rel="#L246">246</span>
<span id="L247" rel="#L247">247</span>
<span id="L248" rel="#L248">248</span>
<span id="L249" rel="#L249">249</span>
<span id="L250" rel="#L250">250</span>
<span id="L251" rel="#L251">251</span>
<span id="L252" rel="#L252">252</span>
<span id="L253" rel="#L253">253</span>
<span id="L254" rel="#L254">254</span>
<span id="L255" rel="#L255">255</span>
<span id="L256" rel="#L256">256</span>
<span id="L257" rel="#L257">257</span>
<span id="L258" rel="#L258">258</span>
<span id="L259" rel="#L259">259</span>
<span id="L260" rel="#L260">260</span>
<span id="L261" rel="#L261">261</span>
<span id="L262" rel="#L262">262</span>
<span id="L263" rel="#L263">263</span>
<span id="L264" rel="#L264">264</span>
<span id="L265" rel="#L265">265</span>
<span id="L266" rel="#L266">266</span>
<span id="L267" rel="#L267">267</span>
<span id="L268" rel="#L268">268</span>
<span id="L269" rel="#L269">269</span>
<span id="L270" rel="#L270">270</span>
<span id="L271" rel="#L271">271</span>
<span id="L272" rel="#L272">272</span>
<span id="L273" rel="#L273">273</span>
<span id="L274" rel="#L274">274</span>
<span id="L275" rel="#L275">275</span>
<span id="L276" rel="#L276">276</span>
<span id="L277" rel="#L277">277</span>
<span id="L278" rel="#L278">278</span>
<span id="L279" rel="#L279">279</span>
<span id="L280" rel="#L280">280</span>
<span id="L281" rel="#L281">281</span>
<span id="L282" rel="#L282">282</span>
<span id="L283" rel="#L283">283</span>
<span id="L284" rel="#L284">284</span>
<span id="L285" rel="#L285">285</span>
<span id="L286" rel="#L286">286</span>
<span id="L287" rel="#L287">287</span>
<span id="L288" rel="#L288">288</span>
<span id="L289" rel="#L289">289</span>
<span id="L290" rel="#L290">290</span>
<span id="L291" rel="#L291">291</span>
<span id="L292" rel="#L292">292</span>
<span id="L293" rel="#L293">293</span>
<span id="L294" rel="#L294">294</span>
<span id="L295" rel="#L295">295</span>
<span id="L296" rel="#L296">296</span>
<span id="L297" rel="#L297">297</span>
<span id="L298" rel="#L298">298</span>
<span id="L299" rel="#L299">299</span>
<span id="L300" rel="#L300">300</span>
<span id="L301" rel="#L301">301</span>
<span id="L302" rel="#L302">302</span>
<span id="L303" rel="#L303">303</span>
<span id="L304" rel="#L304">304</span>
<span id="L305" rel="#L305">305</span>
<span id="L306" rel="#L306">306</span>
<span id="L307" rel="#L307">307</span>
<span id="L308" rel="#L308">308</span>
<span id="L309" rel="#L309">309</span>
<span id="L310" rel="#L310">310</span>
<span id="L311" rel="#L311">311</span>
<span id="L312" rel="#L312">312</span>
<span id="L313" rel="#L313">313</span>
<span id="L314" rel="#L314">314</span>
<span id="L315" rel="#L315">315</span>
<span id="L316" rel="#L316">316</span>
<span id="L317" rel="#L317">317</span>
<span id="L318" rel="#L318">318</span>
<span id="L319" rel="#L319">319</span>
<span id="L320" rel="#L320">320</span>
<span id="L321" rel="#L321">321</span>
<span id="L322" rel="#L322">322</span>
<span id="L323" rel="#L323">323</span>
<span id="L324" rel="#L324">324</span>
<span id="L325" rel="#L325">325</span>
<span id="L326" rel="#L326">326</span>
<span id="L327" rel="#L327">327</span>
<span id="L328" rel="#L328">328</span>
<span id="L329" rel="#L329">329</span>
<span id="L330" rel="#L330">330</span>
<span id="L331" rel="#L331">331</span>
<span id="L332" rel="#L332">332</span>
<span id="L333" rel="#L333">333</span>
<span id="L334" rel="#L334">334</span>
<span id="L335" rel="#L335">335</span>
<span id="L336" rel="#L336">336</span>
<span id="L337" rel="#L337">337</span>
<span id="L338" rel="#L338">338</span>
<span id="L339" rel="#L339">339</span>
<span id="L340" rel="#L340">340</span>
<span id="L341" rel="#L341">341</span>
<span id="L342" rel="#L342">342</span>
<span id="L343" rel="#L343">343</span>
<span id="L344" rel="#L344">344</span>
<span id="L345" rel="#L345">345</span>
<span id="L346" rel="#L346">346</span>
<span id="L347" rel="#L347">347</span>
<span id="L348" rel="#L348">348</span>
<span id="L349" rel="#L349">349</span>
<span id="L350" rel="#L350">350</span>
<span id="L351" rel="#L351">351</span>
<span id="L352" rel="#L352">352</span>
<span id="L353" rel="#L353">353</span>
<span id="L354" rel="#L354">354</span>
<span id="L355" rel="#L355">355</span>
<span id="L356" rel="#L356">356</span>
<span id="L357" rel="#L357">357</span>
<span id="L358" rel="#L358">358</span>
<span id="L359" rel="#L359">359</span>
<span id="L360" rel="#L360">360</span>
<span id="L361" rel="#L361">361</span>
<span id="L362" rel="#L362">362</span>
<span id="L363" rel="#L363">363</span>
<span id="L364" rel="#L364">364</span>
<span id="L365" rel="#L365">365</span>
<span id="L366" rel="#L366">366</span>
<span id="L367" rel="#L367">367</span>
<span id="L368" rel="#L368">368</span>
<span id="L369" rel="#L369">369</span>
<span id="L370" rel="#L370">370</span>
<span id="L371" rel="#L371">371</span>
<span id="L372" rel="#L372">372</span>
<span id="L373" rel="#L373">373</span>
<span id="L374" rel="#L374">374</span>
<span id="L375" rel="#L375">375</span>
<span id="L376" rel="#L376">376</span>
<span id="L377" rel="#L377">377</span>
<span id="L378" rel="#L378">378</span>
<span id="L379" rel="#L379">379</span>
<span id="L380" rel="#L380">380</span>
<span id="L381" rel="#L381">381</span>
<span id="L382" rel="#L382">382</span>
<span id="L383" rel="#L383">383</span>
<span id="L384" rel="#L384">384</span>
<span id="L385" rel="#L385">385</span>
<span id="L386" rel="#L386">386</span>
<span id="L387" rel="#L387">387</span>
<span id="L388" rel="#L388">388</span>
<span id="L389" rel="#L389">389</span>
<span id="L390" rel="#L390">390</span>
<span id="L391" rel="#L391">391</span>
<span id="L392" rel="#L392">392</span>
<span id="L393" rel="#L393">393</span>
<span id="L394" rel="#L394">394</span>
<span id="L395" rel="#L395">395</span>
<span id="L396" rel="#L396">396</span>
<span id="L397" rel="#L397">397</span>
<span id="L398" rel="#L398">398</span>
<span id="L399" rel="#L399">399</span>
<span id="L400" rel="#L400">400</span>
<span id="L401" rel="#L401">401</span>
<span id="L402" rel="#L402">402</span>
<span id="L403" rel="#L403">403</span>
<span id="L404" rel="#L404">404</span>
<span id="L405" rel="#L405">405</span>
<span id="L406" rel="#L406">406</span>
<span id="L407" rel="#L407">407</span>
<span id="L408" rel="#L408">408</span>
<span id="L409" rel="#L409">409</span>
<span id="L410" rel="#L410">410</span>
<span id="L411" rel="#L411">411</span>
<span id="L412" rel="#L412">412</span>
<span id="L413" rel="#L413">413</span>
<span id="L414" rel="#L414">414</span>
<span id="L415" rel="#L415">415</span>
<span id="L416" rel="#L416">416</span>
<span id="L417" rel="#L417">417</span>
<span id="L418" rel="#L418">418</span>
<span id="L419" rel="#L419">419</span>
<span id="L420" rel="#L420">420</span>
<span id="L421" rel="#L421">421</span>
<span id="L422" rel="#L422">422</span>
<span id="L423" rel="#L423">423</span>
<span id="L424" rel="#L424">424</span>
<span id="L425" rel="#L425">425</span>
<span id="L426" rel="#L426">426</span>
<span id="L427" rel="#L427">427</span>
<span id="L428" rel="#L428">428</span>
<span id="L429" rel="#L429">429</span>
<span id="L430" rel="#L430">430</span>
<span id="L431" rel="#L431">431</span>
<span id="L432" rel="#L432">432</span>
<span id="L433" rel="#L433">433</span>
<span id="L434" rel="#L434">434</span>
<span id="L435" rel="#L435">435</span>
<span id="L436" rel="#L436">436</span>
<span id="L437" rel="#L437">437</span>
<span id="L438" rel="#L438">438</span>
<span id="L439" rel="#L439">439</span>
<span id="L440" rel="#L440">440</span>
<span id="L441" rel="#L441">441</span>
<span id="L442" rel="#L442">442</span>
<span id="L443" rel="#L443">443</span>
<span id="L444" rel="#L444">444</span>
<span id="L445" rel="#L445">445</span>
<span id="L446" rel="#L446">446</span>
<span id="L447" rel="#L447">447</span>
<span id="L448" rel="#L448">448</span>
<span id="L449" rel="#L449">449</span>
<span id="L450" rel="#L450">450</span>
<span id="L451" rel="#L451">451</span>
<span id="L452" rel="#L452">452</span>
<span id="L453" rel="#L453">453</span>
<span id="L454" rel="#L454">454</span>
<span id="L455" rel="#L455">455</span>
<span id="L456" rel="#L456">456</span>
<span id="L457" rel="#L457">457</span>
<span id="L458" rel="#L458">458</span>
<span id="L459" rel="#L459">459</span>
<span id="L460" rel="#L460">460</span>
<span id="L461" rel="#L461">461</span>
<span id="L462" rel="#L462">462</span>
<span id="L463" rel="#L463">463</span>
<span id="L464" rel="#L464">464</span>
<span id="L465" rel="#L465">465</span>
<span id="L466" rel="#L466">466</span>
<span id="L467" rel="#L467">467</span>
<span id="L468" rel="#L468">468</span>
<span id="L469" rel="#L469">469</span>
<span id="L470" rel="#L470">470</span>
<span id="L471" rel="#L471">471</span>
<span id="L472" rel="#L472">472</span>
<span id="L473" rel="#L473">473</span>
<span id="L474" rel="#L474">474</span>
<span id="L475" rel="#L475">475</span>
<span id="L476" rel="#L476">476</span>
<span id="L477" rel="#L477">477</span>
<span id="L478" rel="#L478">478</span>
<span id="L479" rel="#L479">479</span>
<span id="L480" rel="#L480">480</span>
<span id="L481" rel="#L481">481</span>
<span id="L482" rel="#L482">482</span>
<span id="L483" rel="#L483">483</span>
<span id="L484" rel="#L484">484</span>
<span id="L485" rel="#L485">485</span>
<span id="L486" rel="#L486">486</span>
<span id="L487" rel="#L487">487</span>
<span id="L488" rel="#L488">488</span>
<span id="L489" rel="#L489">489</span>
<span id="L490" rel="#L490">490</span>
<span id="L491" rel="#L491">491</span>
<span id="L492" rel="#L492">492</span>
<span id="L493" rel="#L493">493</span>
<span id="L494" rel="#L494">494</span>
<span id="L495" rel="#L495">495</span>
<span id="L496" rel="#L496">496</span>
<span id="L497" rel="#L497">497</span>
<span id="L498" rel="#L498">498</span>
<span id="L499" rel="#L499">499</span>
<span id="L500" rel="#L500">500</span>
<span id="L501" rel="#L501">501</span>
<span id="L502" rel="#L502">502</span>
<span id="L503" rel="#L503">503</span>
<span id="L504" rel="#L504">504</span>
<span id="L505" rel="#L505">505</span>
<span id="L506" rel="#L506">506</span>
<span id="L507" rel="#L507">507</span>
<span id="L508" rel="#L508">508</span>
<span id="L509" rel="#L509">509</span>
<span id="L510" rel="#L510">510</span>
<span id="L511" rel="#L511">511</span>
<span id="L512" rel="#L512">512</span>
<span id="L513" rel="#L513">513</span>
<span id="L514" rel="#L514">514</span>
<span id="L515" rel="#L515">515</span>
<span id="L516" rel="#L516">516</span>
<span id="L517" rel="#L517">517</span>
<span id="L518" rel="#L518">518</span>
<span id="L519" rel="#L519">519</span>
<span id="L520" rel="#L520">520</span>
<span id="L521" rel="#L521">521</span>
<span id="L522" rel="#L522">522</span>
<span id="L523" rel="#L523">523</span>
<span id="L524" rel="#L524">524</span>
<span id="L525" rel="#L525">525</span>
<span id="L526" rel="#L526">526</span>
<span id="L527" rel="#L527">527</span>
<span id="L528" rel="#L528">528</span>
<span id="L529" rel="#L529">529</span>
<span id="L530" rel="#L530">530</span>
<span id="L531" rel="#L531">531</span>
<span id="L532" rel="#L532">532</span>
<span id="L533" rel="#L533">533</span>
<span id="L534" rel="#L534">534</span>
<span id="L535" rel="#L535">535</span>
<span id="L536" rel="#L536">536</span>
<span id="L537" rel="#L537">537</span>
<span id="L538" rel="#L538">538</span>
<span id="L539" rel="#L539">539</span>
<span id="L540" rel="#L540">540</span>
<span id="L541" rel="#L541">541</span>
<span id="L542" rel="#L542">542</span>
<span id="L543" rel="#L543">543</span>
<span id="L544" rel="#L544">544</span>
<span id="L545" rel="#L545">545</span>
<span id="L546" rel="#L546">546</span>
<span id="L547" rel="#L547">547</span>
<span id="L548" rel="#L548">548</span>
<span id="L549" rel="#L549">549</span>
<span id="L550" rel="#L550">550</span>
<span id="L551" rel="#L551">551</span>
<span id="L552" rel="#L552">552</span>
<span id="L553" rel="#L553">553</span>
<span id="L554" rel="#L554">554</span>
<span id="L555" rel="#L555">555</span>
<span id="L556" rel="#L556">556</span>
<span id="L557" rel="#L557">557</span>
<span id="L558" rel="#L558">558</span>
<span id="L559" rel="#L559">559</span>
<span id="L560" rel="#L560">560</span>
<span id="L561" rel="#L561">561</span>
<span id="L562" rel="#L562">562</span>
<span id="L563" rel="#L563">563</span>
<span id="L564" rel="#L564">564</span>
<span id="L565" rel="#L565">565</span>
<span id="L566" rel="#L566">566</span>
<span id="L567" rel="#L567">567</span>
<span id="L568" rel="#L568">568</span>
<span id="L569" rel="#L569">569</span>
<span id="L570" rel="#L570">570</span>
<span id="L571" rel="#L571">571</span>
<span id="L572" rel="#L572">572</span>
<span id="L573" rel="#L573">573</span>
<span id="L574" rel="#L574">574</span>
<span id="L575" rel="#L575">575</span>
<span id="L576" rel="#L576">576</span>
<span id="L577" rel="#L577">577</span>
<span id="L578" rel="#L578">578</span>
<span id="L579" rel="#L579">579</span>
<span id="L580" rel="#L580">580</span>
<span id="L581" rel="#L581">581</span>
<span id="L582" rel="#L582">582</span>
<span id="L583" rel="#L583">583</span>
<span id="L584" rel="#L584">584</span>
<span id="L585" rel="#L585">585</span>
<span id="L586" rel="#L586">586</span>
<span id="L587" rel="#L587">587</span>
<span id="L588" rel="#L588">588</span>
<span id="L589" rel="#L589">589</span>
<span id="L590" rel="#L590">590</span>
<span id="L591" rel="#L591">591</span>
<span id="L592" rel="#L592">592</span>
<span id="L593" rel="#L593">593</span>
<span id="L594" rel="#L594">594</span>
<span id="L595" rel="#L595">595</span>
<span id="L596" rel="#L596">596</span>
<span id="L597" rel="#L597">597</span>
<span id="L598" rel="#L598">598</span>
<span id="L599" rel="#L599">599</span>
<span id="L600" rel="#L600">600</span>
<span id="L601" rel="#L601">601</span>
<span id="L602" rel="#L602">602</span>
<span id="L603" rel="#L603">603</span>
<span id="L604" rel="#L604">604</span>
<span id="L605" rel="#L605">605</span>
<span id="L606" rel="#L606">606</span>
<span id="L607" rel="#L607">607</span>
<span id="L608" rel="#L608">608</span>
<span id="L609" rel="#L609">609</span>
<span id="L610" rel="#L610">610</span>
<span id="L611" rel="#L611">611</span>
<span id="L612" rel="#L612">612</span>
<span id="L613" rel="#L613">613</span>
<span id="L614" rel="#L614">614</span>
<span id="L615" rel="#L615">615</span>
<span id="L616" rel="#L616">616</span>
<span id="L617" rel="#L617">617</span>
<span id="L618" rel="#L618">618</span>
<span id="L619" rel="#L619">619</span>
<span id="L620" rel="#L620">620</span>
<span id="L621" rel="#L621">621</span>
<span id="L622" rel="#L622">622</span>
<span id="L623" rel="#L623">623</span>
<span id="L624" rel="#L624">624</span>
<span id="L625" rel="#L625">625</span>
<span id="L626" rel="#L626">626</span>
<span id="L627" rel="#L627">627</span>
<span id="L628" rel="#L628">628</span>
<span id="L629" rel="#L629">629</span>
<span id="L630" rel="#L630">630</span>
<span id="L631" rel="#L631">631</span>
<span id="L632" rel="#L632">632</span>
<span id="L633" rel="#L633">633</span>
<span id="L634" rel="#L634">634</span>
<span id="L635" rel="#L635">635</span>
<span id="L636" rel="#L636">636</span>
<span id="L637" rel="#L637">637</span>
<span id="L638" rel="#L638">638</span>
<span id="L639" rel="#L639">639</span>
<span id="L640" rel="#L640">640</span>
<span id="L641" rel="#L641">641</span>
<span id="L642" rel="#L642">642</span>
<span id="L643" rel="#L643">643</span>
<span id="L644" rel="#L644">644</span>
<span id="L645" rel="#L645">645</span>
<span id="L646" rel="#L646">646</span>
<span id="L647" rel="#L647">647</span>
<span id="L648" rel="#L648">648</span>
<span id="L649" rel="#L649">649</span>
<span id="L650" rel="#L650">650</span>
<span id="L651" rel="#L651">651</span>
<span id="L652" rel="#L652">652</span>
<span id="L653" rel="#L653">653</span>
<span id="L654" rel="#L654">654</span>
<span id="L655" rel="#L655">655</span>
<span id="L656" rel="#L656">656</span>
<span id="L657" rel="#L657">657</span>
<span id="L658" rel="#L658">658</span>
<span id="L659" rel="#L659">659</span>
<span id="L660" rel="#L660">660</span>
<span id="L661" rel="#L661">661</span>
<span id="L662" rel="#L662">662</span>
<span id="L663" rel="#L663">663</span>
<span id="L664" rel="#L664">664</span>
<span id="L665" rel="#L665">665</span>
<span id="L666" rel="#L666">666</span>
<span id="L667" rel="#L667">667</span>
<span id="L668" rel="#L668">668</span>
<span id="L669" rel="#L669">669</span>
<span id="L670" rel="#L670">670</span>
<span id="L671" rel="#L671">671</span>
<span id="L672" rel="#L672">672</span>
<span id="L673" rel="#L673">673</span>
<span id="L674" rel="#L674">674</span>
<span id="L675" rel="#L675">675</span>
<span id="L676" rel="#L676">676</span>
<span id="L677" rel="#L677">677</span>
<span id="L678" rel="#L678">678</span>
<span id="L679" rel="#L679">679</span>
<span id="L680" rel="#L680">680</span>
<span id="L681" rel="#L681">681</span>
<span id="L682" rel="#L682">682</span>
<span id="L683" rel="#L683">683</span>
<span id="L684" rel="#L684">684</span>
<span id="L685" rel="#L685">685</span>
<span id="L686" rel="#L686">686</span>
<span id="L687" rel="#L687">687</span>
<span id="L688" rel="#L688">688</span>
<span id="L689" rel="#L689">689</span>
<span id="L690" rel="#L690">690</span>
<span id="L691" rel="#L691">691</span>
<span id="L692" rel="#L692">692</span>
<span id="L693" rel="#L693">693</span>
<span id="L694" rel="#L694">694</span>
<span id="L695" rel="#L695">695</span>
<span id="L696" rel="#L696">696</span>
<span id="L697" rel="#L697">697</span>
<span id="L698" rel="#L698">698</span>
<span id="L699" rel="#L699">699</span>
<span id="L700" rel="#L700">700</span>
<span id="L701" rel="#L701">701</span>
<span id="L702" rel="#L702">702</span>
<span id="L703" rel="#L703">703</span>
</pre>
          </td>
          <td width="100%">
            
              
                <div class="highlight"><pre><div class='line' id='LC1'><span class="cm">/* Cases where transition is disabled:</span></div><div class='line' id='LC2'><span class="cm"> * - in incompatible browsers (Opera 11 included)</span></div><div class='line' id='LC3'><span class="cm"> * - when the animated object is not an element</span></div><div class='line' id='LC4'><span class="cm"> * - when there is a special easing</span></div><div class='line' id='LC5'><span class="cm"> * - when there is a step function</span></div><div class='line' id='LC6'><span class="cm"> * - when jQuery.fx.off is true (should work out of the box)</span></div><div class='line' id='LC7'><span class="cm"> *</span></div><div class='line' id='LC8'><span class="cm"> * jQuery.fx.stop() will stop animations instead of pausing them (undocumented method and behavior anyway).</span></div><div class='line' id='LC9'><span class="cm"> */</span></div><div class='line' id='LC10'><span class="p">(</span><span class="kd">function</span><span class="p">(</span> <span class="nx">jQuery</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC11'><br/></div><div class='line' id='LC12'><span class="kd">var</span> <span class="nx">elemdisplay</span> <span class="o">=</span> <span class="p">{},</span></div><div class='line' id='LC13'>	<span class="nx">rfxtypes</span> <span class="o">=</span> <span class="sr">/^(?:toggle|show|hide)$/</span><span class="p">,</span></div><div class='line' id='LC14'>	<span class="nx">rfxnum</span> <span class="o">=</span> <span class="sr">/^([+\-]=)?([\d+.\-]+)([a-z%]*)$/i</span><span class="p">,</span></div><div class='line' id='LC15'>	<span class="nx">timerId</span><span class="p">,</span></div><div class='line' id='LC16'>	<span class="nx">effectsTimestamp</span><span class="cm">/*,</span></div><div class='line' id='LC17'><span class="cm">	fxAttrs = [</span></div><div class='line' id='LC18'><span class="cm">		// height animations</span></div><div class='line' id='LC19'><span class="cm">		[ &quot;height&quot;, &quot;marginTop&quot;, &quot;marginBottom&quot;, &quot;paddingTop&quot;, &quot;paddingBottom&quot; ],</span></div><div class='line' id='LC20'><span class="cm">		// width animations</span></div><div class='line' id='LC21'><span class="cm">		[ &quot;width&quot;, &quot;marginLeft&quot;, &quot;marginRight&quot;, &quot;paddingLeft&quot;, &quot;paddingRight&quot; ],</span></div><div class='line' id='LC22'><span class="cm">		// opacity animations</span></div><div class='line' id='LC23'><span class="cm">		[ &quot;opacity&quot; ]</span></div><div class='line' id='LC24'><span class="cm">	]*/</span><span class="p">;</span></div><div class='line' id='LC25'><br/></div><div class='line' id='LC26'><span class="c1">// TRANSITION++</span></div><div class='line' id='LC27'><span class="c1">// Following feature test code should be moved to support.js</span></div><div class='line' id='LC28'><span class="kd">var</span> <span class="nx">div</span> <span class="o">=</span> <span class="nb">document</span><span class="p">.</span><span class="nx">createElement</span><span class="p">(</span><span class="s1">&#39;div&#39;</span><span class="p">),</span></div><div class='line' id='LC29'>	<span class="nx">divStyle</span> <span class="o">=</span> <span class="nx">div</span><span class="p">.</span><span class="nx">style</span><span class="p">,</span></div><div class='line' id='LC30'>	<span class="nx">trans</span> <span class="o">=</span> <span class="s2">&quot;Transition&quot;</span><span class="p">;</span></div><div class='line' id='LC31'><span class="c1">// Only test for transition support in Firefox and Webkit </span></div><div class='line' id='LC32'><span class="c1">// as we know for sure that Opera has too much bugs (see http://csstransition.net)</span></div><div class='line' id='LC33'><span class="c1">// and there&#39;s no guarantee that first IE implementation will be bug-free</span></div><div class='line' id='LC34'><span class="nx">jQuery</span><span class="p">.</span><span class="nx">support</span><span class="p">.</span><span class="nx">transition</span> <span class="o">=</span></div><div class='line' id='LC35'>	<span class="s1">&#39;Moz&#39;</span><span class="o">+</span><span class="nx">trans</span> <span class="k">in</span> <span class="nx">divStyle</span> <span class="o">?</span> <span class="s1">&#39;Moz&#39;</span><span class="o">+</span><span class="nx">trans</span><span class="o">:</span></div><div class='line' id='LC36'>	<span class="s1">&#39;Webkit&#39;</span><span class="o">+</span><span class="nx">trans</span> <span class="k">in</span> <span class="nx">divStyle</span> <span class="o">?</span> <span class="s1">&#39;Webkit&#39;</span><span class="o">+</span><span class="nx">trans</span><span class="o">:</span></div><div class='line' id='LC37'>	<span class="kc">false</span><span class="p">;</span></div><div class='line' id='LC38'><span class="c1">// prevent IE memory leak;</span></div><div class='line' id='LC39'><span class="nx">div</span> <span class="o">=</span> <span class="nx">divStyle</span> <span class="o">=</span> <span class="kc">null</span><span class="p">;</span></div><div class='line' id='LC40'><br/></div><div class='line' id='LC41'><span class="nx">jQuery</span><span class="p">.</span><span class="nx">fn</span><span class="p">.</span><span class="nx">extend</span><span class="p">({</span></div><div class='line' id='LC42'>	<span class="cm">/*show: function( speed, easing, callback ) {</span></div><div class='line' id='LC43'><span class="cm">		var elem, display;</span></div><div class='line' id='LC44'><br/></div><div class='line' id='LC45'><span class="cm">		if ( speed || speed === 0 ) {</span></div><div class='line' id='LC46'><span class="cm">			return this.animate( genFx(&quot;show&quot;, 3), speed, easing, callback);</span></div><div class='line' id='LC47'><br/></div><div class='line' id='LC48'><span class="cm">		} else {</span></div><div class='line' id='LC49'><span class="cm">			for ( var i = 0, j = this.length; i &lt; j; i++ ) {</span></div><div class='line' id='LC50'><span class="cm">				elem = this[i];</span></div><div class='line' id='LC51'><span class="cm">				display = elem.style.display;</span></div><div class='line' id='LC52'><br/></div><div class='line' id='LC53'><span class="cm">				// Reset the inline display of this element to learn if it is</span></div><div class='line' id='LC54'><span class="cm">				// being hidden by cascaded rules or not</span></div><div class='line' id='LC55'><span class="cm">				if ( !jQuery._data(elem, &quot;olddisplay&quot;) &amp;&amp; display === &quot;none&quot; ) {</span></div><div class='line' id='LC56'><span class="cm">					display = elem.style.display = &quot;&quot;;</span></div><div class='line' id='LC57'><span class="cm">				}</span></div><div class='line' id='LC58'><br/></div><div class='line' id='LC59'><span class="cm">				// Set elements which have been overridden with display: none</span></div><div class='line' id='LC60'><span class="cm">				// in a stylesheet to whatever the default browser style is</span></div><div class='line' id='LC61'><span class="cm">				// for such an element</span></div><div class='line' id='LC62'><span class="cm">				if ( display === &quot;&quot; &amp;&amp; jQuery.css( elem, &quot;display&quot; ) === &quot;none&quot; ) {</span></div><div class='line' id='LC63'><span class="cm">					jQuery._data(elem, &quot;olddisplay&quot;, defaultDisplay(elem.nodeName));</span></div><div class='line' id='LC64'><span class="cm">				}</span></div><div class='line' id='LC65'><span class="cm">			}</span></div><div class='line' id='LC66'><br/></div><div class='line' id='LC67'><span class="cm">			// Set the display of most of the elements in a second loop</span></div><div class='line' id='LC68'><span class="cm">			// to avoid the constant reflow</span></div><div class='line' id='LC69'><span class="cm">			for ( i = 0; i &lt; j; i++ ) {</span></div><div class='line' id='LC70'><span class="cm">				elem = this[i];</span></div><div class='line' id='LC71'><span class="cm">				display = elem.style.display;</span></div><div class='line' id='LC72'><br/></div><div class='line' id='LC73'><span class="cm">				if ( display === &quot;&quot; || display === &quot;none&quot; ) {</span></div><div class='line' id='LC74'><span class="cm">					elem.style.display = jQuery._data(elem, &quot;olddisplay&quot;) || &quot;&quot;;</span></div><div class='line' id='LC75'><span class="cm">				}</span></div><div class='line' id='LC76'><span class="cm">			}</span></div><div class='line' id='LC77'><br/></div><div class='line' id='LC78'><span class="cm">			return this;</span></div><div class='line' id='LC79'><span class="cm">		}</span></div><div class='line' id='LC80'><span class="cm">	},</span></div><div class='line' id='LC81'><br/></div><div class='line' id='LC82'><span class="cm">	hide: function( speed, easing, callback ) {</span></div><div class='line' id='LC83'><span class="cm">		if ( speed || speed === 0 ) {</span></div><div class='line' id='LC84'><span class="cm">			return this.animate( genFx(&quot;hide&quot;, 3), speed, easing, callback);</span></div><div class='line' id='LC85'><br/></div><div class='line' id='LC86'><span class="cm">		} else {</span></div><div class='line' id='LC87'><span class="cm">			for ( var i = 0, j = this.length; i &lt; j; i++ ) {</span></div><div class='line' id='LC88'><span class="cm">				var display = jQuery.css( this[i], &quot;display&quot; );</span></div><div class='line' id='LC89'><br/></div><div class='line' id='LC90'><span class="cm">				if ( display !== &quot;none&quot; &amp;&amp; !jQuery._data( this[i], &quot;olddisplay&quot; ) ) {</span></div><div class='line' id='LC91'><span class="cm">					jQuery._data( this[i], &quot;olddisplay&quot;, display );</span></div><div class='line' id='LC92'><span class="cm">				}</span></div><div class='line' id='LC93'><span class="cm">			}</span></div><div class='line' id='LC94'><br/></div><div class='line' id='LC95'><span class="cm">			// Set the display of the elements in a second loop</span></div><div class='line' id='LC96'><span class="cm">			// to avoid the constant reflow</span></div><div class='line' id='LC97'><span class="cm">			for ( i = 0; i &lt; j; i++ ) {</span></div><div class='line' id='LC98'><span class="cm">				this[i].style.display = &quot;none&quot;;</span></div><div class='line' id='LC99'><span class="cm">			}</span></div><div class='line' id='LC100'><br/></div><div class='line' id='LC101'><span class="cm">			return this;</span></div><div class='line' id='LC102'><span class="cm">		}</span></div><div class='line' id='LC103'><span class="cm">	},</span></div><div class='line' id='LC104'><br/></div><div class='line' id='LC105'><span class="cm">	// Save the old toggle function</span></div><div class='line' id='LC106'><span class="cm">	_toggle: jQuery.fn.toggle,</span></div><div class='line' id='LC107'><br/></div><div class='line' id='LC108'><span class="cm">	toggle: function( fn, fn2, callback ) {</span></div><div class='line' id='LC109'><span class="cm">		var bool = typeof fn === &quot;boolean&quot;;</span></div><div class='line' id='LC110'><br/></div><div class='line' id='LC111'><span class="cm">		if ( jQuery.isFunction(fn) &amp;&amp; jQuery.isFunction(fn2) ) {</span></div><div class='line' id='LC112'><span class="cm">			this._toggle.apply( this, arguments );</span></div><div class='line' id='LC113'><br/></div><div class='line' id='LC114'><span class="cm">		} else if ( fn == null || bool ) {</span></div><div class='line' id='LC115'><span class="cm">			this.each(function() {</span></div><div class='line' id='LC116'><span class="cm">				var state = bool ? fn : jQuery(this).is(&quot;:hidden&quot;);</span></div><div class='line' id='LC117'><span class="cm">				jQuery(this)[ state ? &quot;show&quot; : &quot;hide&quot; ]();</span></div><div class='line' id='LC118'><span class="cm">			});</span></div><div class='line' id='LC119'><br/></div><div class='line' id='LC120'><span class="cm">		} else {</span></div><div class='line' id='LC121'><span class="cm">			this.animate(genFx(&quot;toggle&quot;, 3), fn, fn2, callback);</span></div><div class='line' id='LC122'><span class="cm">		}</span></div><div class='line' id='LC123'><br/></div><div class='line' id='LC124'><span class="cm">		return this;</span></div><div class='line' id='LC125'><span class="cm">	},</span></div><div class='line' id='LC126'><br/></div><div class='line' id='LC127'><span class="cm">	fadeTo: function( speed, to, easing, callback ) {</span></div><div class='line' id='LC128'><span class="cm">		return this.filter(&quot;:hidden&quot;).css(&quot;opacity&quot;, 0).show().end()</span></div><div class='line' id='LC129'><span class="cm">					.animate({opacity: to}, speed, easing, callback);</span></div><div class='line' id='LC130'><span class="cm">	},*/</span></div><div class='line' id='LC131'><br/></div><div class='line' id='LC132'>	<span class="nx">animate</span><span class="o">:</span> <span class="kd">function</span><span class="p">(</span> <span class="nx">prop</span><span class="p">,</span> <span class="nx">speed</span><span class="p">,</span> <span class="nx">easing</span><span class="p">,</span> <span class="nx">callback</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC133'>		<span class="kd">var</span> <span class="nx">optall</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">speed</span><span class="p">(</span><span class="nx">speed</span><span class="p">,</span> <span class="nx">easing</span><span class="p">,</span> <span class="nx">callback</span><span class="p">),</span></div><div class='line' id='LC134'>			<span class="c1">// Fix #7917, synchronize animations.</span></div><div class='line' id='LC135'>			<span class="nx">_startTime</span> <span class="o">=</span> <span class="nx">effectsNow</span><span class="p">();</span></div><div class='line' id='LC136'><br/></div><div class='line' id='LC137'>		<span class="k">if</span> <span class="p">(</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">isEmptyObject</span><span class="p">(</span> <span class="nx">prop</span> <span class="p">)</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC138'>			<span class="k">return</span> <span class="k">this</span><span class="p">.</span><span class="nx">each</span><span class="p">(</span> <span class="nx">optall</span><span class="p">.</span><span class="nx">complete</span> <span class="p">);</span></div><div class='line' id='LC139'>		<span class="p">}</span></div><div class='line' id='LC140'><br/></div><div class='line' id='LC141'>		<span class="k">return</span> <span class="k">this</span><span class="p">[</span> <span class="nx">optall</span><span class="p">.</span><span class="nx">queue</span> <span class="o">===</span> <span class="kc">false</span> <span class="o">?</span> <span class="s2">&quot;each&quot;</span> <span class="o">:</span> <span class="s2">&quot;queue&quot;</span> <span class="p">](</span><span class="kd">function</span><span class="p">()</span> <span class="p">{</span></div><div class='line' id='LC142'>			<span class="c1">// XXX &#39;this&#39; does not always have a nodeName when running the</span></div><div class='line' id='LC143'>			<span class="c1">// test suite</span></div><div class='line' id='LC144'><br/></div><div class='line' id='LC145'>			<span class="kd">var</span> <span class="nx">self</span> <span class="o">=</span> <span class="k">this</span><span class="p">,</span></div><div class='line' id='LC146'>				<span class="c1">// cache jQuery properties to minimize lookups (and filesize)</span></div><div class='line' id='LC147'>				<span class="nx">extend</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">extend</span><span class="p">,</span></div><div class='line' id='LC148'>				<span class="nx">style</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">style</span><span class="p">,</span></div><div class='line' id='LC149'>				<span class="nx">support</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">support</span><span class="p">,</span></div><div class='line' id='LC150'>				<span class="nx">css</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">css</span><span class="p">,</span></div><div class='line' id='LC151'>				<span class="nx">fx</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">fx</span><span class="p">,</span></div><div class='line' id='LC152'>				<span class="nx">startTime</span> <span class="o">=</span> <span class="nx">_startTime</span><span class="p">,</span></div><div class='line' id='LC153'>				<span class="c1">// cache end</span></div><div class='line' id='LC154'>				<span class="nx">opt</span> <span class="o">=</span> <span class="nx">extend</span><span class="p">({},</span> <span class="nx">optall</span><span class="p">),</span> <span class="nx">p</span><span class="p">,</span></div><div class='line' id='LC155'>				<span class="nx">isElement</span> <span class="o">=</span> <span class="nx">self</span><span class="p">.</span><span class="nx">nodeType</span> <span class="o">===</span> <span class="mi">1</span><span class="p">,</span></div><div class='line' id='LC156'>				<span class="nx">hidden</span> <span class="o">=</span> <span class="nx">isElement</span> <span class="o">&amp;&amp;</span> <span class="nx">jQuery</span><span class="p">(</span><span class="nx">self</span><span class="p">).</span><span class="nx">is</span><span class="p">(</span><span class="s2">&quot;:hidden&quot;</span><span class="p">),</span></div><div class='line' id='LC157'>				<span class="nx">thisStyle</span> <span class="o">=</span> <span class="nx">self</span><span class="p">.</span><span class="nx">style</span><span class="p">,</span></div><div class='line' id='LC158'>				<span class="nx">name</span><span class="p">,</span> <span class="nx">val</span><span class="p">,</span> <span class="nx">easing</span><span class="p">,</span></div><div class='line' id='LC159'>				<span class="nx">display</span><span class="p">,</span></div><div class='line' id='LC160'>				<span class="nx">e</span><span class="p">,</span></div><div class='line' id='LC161'>				<span class="nx">parts</span><span class="p">,</span> <span class="nx">start</span><span class="p">,</span> <span class="nx">end</span><span class="p">,</span> <span class="nx">unit</span><span class="p">,</span></div><div class='line' id='LC162'>				<span class="c1">// TRANSITION++</span></div><div class='line' id='LC163'>				<span class="nx">cssProps</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">cssProps</span><span class="p">,</span></div><div class='line' id='LC164'>				<span class="c1">// disable transition if a step option is supplied</span></div><div class='line' id='LC165'>				<span class="nx">supportTransition</span> <span class="o">=</span> <span class="o">!</span><span class="nx">opt</span><span class="p">.</span><span class="nx">step</span> <span class="o">&amp;&amp;</span> <span class="nx">support</span><span class="p">.</span><span class="nx">transition</span><span class="p">,</span></div><div class='line' id='LC166'>				<span class="nx">transition</span><span class="p">,</span></div><div class='line' id='LC167'>				<span class="nx">transitions</span> <span class="o">=</span> <span class="p">[],</span></div><div class='line' id='LC168'>				<span class="nx">hook</span><span class="p">,</span> <span class="nx">real</span><span class="p">,</span> <span class="nx">lower</span><span class="p">;</span></div><div class='line' id='LC169'><br/></div><div class='line' id='LC170'>			<span class="c1">// jQuery.now() is called only once for all animated properties of all elements</span></div><div class='line' id='LC171'>			<span class="k">if</span> <span class="p">(</span><span class="o">!</span><span class="nx">startTime</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC172'>				<span class="nx">_startTime</span> <span class="o">=</span> <span class="nx">startTime</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">now</span><span class="p">();</span></div><div class='line' id='LC173'>			<span class="p">}</span></div><div class='line' id='LC174'><br/></div><div class='line' id='LC175'>			<span class="c1">// will store per property easing and be used to determine when an animation is complete</span></div><div class='line' id='LC176'>			<span class="nx">opt</span><span class="p">.</span><span class="nx">animatedProperties</span> <span class="o">=</span> <span class="p">{};</span></div><div class='line' id='LC177'>			<span class="c1">// TRANSITION++</span></div><div class='line' id='LC178'>			<span class="c1">// transition is enabled per property, when:</span></div><div class='line' id='LC179'>			<span class="c1">// - there is no step function for the animation</span></div><div class='line' id='LC180'>			<span class="c1">// - there is no special easing for the property</span></div><div class='line' id='LC181'>			<span class="nx">opt</span><span class="p">.</span><span class="nx">transition</span> <span class="o">=</span> <span class="p">{};</span></div><div class='line' id='LC182'><br/></div><div class='line' id='LC183'>			<span class="k">for</span> <span class="p">(</span> <span class="nx">p</span> <span class="k">in</span> <span class="nx">prop</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC184'><br/></div><div class='line' id='LC185'>				<span class="c1">// property name normalization</span></div><div class='line' id='LC186'>				<span class="nx">name</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">camelCase</span><span class="p">(</span> <span class="nx">p</span> <span class="p">);</span></div><div class='line' id='LC187'>				<span class="k">if</span> <span class="p">(</span> <span class="nx">p</span> <span class="o">!==</span> <span class="nx">name</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC188'>					<span class="nx">prop</span><span class="p">[</span> <span class="nx">name</span> <span class="p">]</span> <span class="o">=</span> <span class="nx">prop</span><span class="p">[</span> <span class="nx">p</span> <span class="p">];</span></div><div class='line' id='LC189'>					<span class="k">delete</span> <span class="nx">prop</span><span class="p">[</span> <span class="nx">p</span> <span class="p">];</span></div><div class='line' id='LC190'>					<span class="nx">p</span> <span class="o">=</span> <span class="nx">name</span><span class="p">;</span></div><div class='line' id='LC191'>				<span class="p">}</span></div><div class='line' id='LC192'><br/></div><div class='line' id='LC193'>				<span class="nx">val</span> <span class="o">=</span> <span class="nx">prop</span><span class="p">[</span><span class="nx">p</span><span class="p">];</span></div><div class='line' id='LC194'><br/></div><div class='line' id='LC195'>				<span class="k">if</span> <span class="p">(</span> <span class="nx">val</span> <span class="o">===</span> <span class="s2">&quot;hide&quot;</span> <span class="o">&amp;&amp;</span> <span class="nx">hidden</span> <span class="o">||</span> <span class="nx">val</span> <span class="o">===</span> <span class="s2">&quot;show&quot;</span> <span class="o">&amp;&amp;</span> <span class="o">!</span><span class="nx">hidden</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC196'>					<span class="k">return</span> <span class="nx">opt</span><span class="p">.</span><span class="nx">complete</span><span class="p">.</span><span class="nx">call</span><span class="p">(</span><span class="nx">self</span><span class="p">);</span></div><div class='line' id='LC197'>				<span class="p">}</span></div><div class='line' id='LC198'><br/></div><div class='line' id='LC199'>				<span class="k">if</span> <span class="p">(</span> <span class="nx">isElement</span> <span class="o">&amp;&amp;</span> <span class="p">(</span> <span class="nx">p</span> <span class="o">===</span> <span class="s2">&quot;height&quot;</span> <span class="o">||</span> <span class="nx">p</span> <span class="o">===</span> <span class="s2">&quot;width&quot;</span> <span class="p">)</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC200'>					<span class="c1">// Make sure that nothing sneaks out</span></div><div class='line' id='LC201'>					<span class="c1">// Record all 3 overflow attributes because IE does not</span></div><div class='line' id='LC202'>					<span class="c1">// change the overflow attribute when overflowX and</span></div><div class='line' id='LC203'>					<span class="c1">// overflowY are set to the same value</span></div><div class='line' id='LC204'>					<span class="nx">opt</span><span class="p">.</span><span class="nx">overflow</span> <span class="o">=</span> <span class="p">[</span> <span class="nx">thisStyle</span><span class="p">.</span><span class="nx">overflow</span><span class="p">,</span> <span class="nx">thisStyle</span><span class="p">.</span><span class="nx">overflowX</span><span class="p">,</span> <span class="nx">thisStyle</span><span class="p">.</span><span class="nx">overflowY</span> <span class="p">];</span></div><div class='line' id='LC205'><br/></div><div class='line' id='LC206'>					<span class="c1">// Set display property to inline-block for height/width</span></div><div class='line' id='LC207'>					<span class="c1">// animations on inline elements that are having width/height</span></div><div class='line' id='LC208'>					<span class="c1">// animated</span></div><div class='line' id='LC209'>					<span class="k">if</span> <span class="p">(</span> <span class="nx">css</span><span class="p">(</span> <span class="nx">self</span><span class="p">,</span> <span class="s2">&quot;display&quot;</span> <span class="p">)</span> <span class="o">===</span> <span class="s2">&quot;inline&quot;</span> <span class="o">&amp;&amp;</span></div><div class='line' id='LC210'>							<span class="nx">css</span><span class="p">(</span> <span class="nx">self</span><span class="p">,</span> <span class="s2">&quot;float&quot;</span> <span class="p">)</span> <span class="o">===</span> <span class="s2">&quot;none&quot;</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC211'>						<span class="k">if</span> <span class="p">(</span> <span class="o">!</span><span class="nx">support</span><span class="p">.</span><span class="nx">inlineBlockNeedsLayout</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC212'>							<span class="nx">thisStyle</span><span class="p">.</span><span class="nx">display</span> <span class="o">=</span> <span class="s2">&quot;inline-block&quot;</span><span class="p">;</span></div><div class='line' id='LC213'><br/></div><div class='line' id='LC214'>						<span class="p">}</span> <span class="k">else</span> <span class="p">{</span></div><div class='line' id='LC215'>							<span class="nx">display</span> <span class="o">=</span> <span class="nx">defaultDisplay</span><span class="p">(</span><span class="nx">self</span><span class="p">.</span><span class="nx">nodeName</span><span class="p">);</span></div><div class='line' id='LC216'><br/></div><div class='line' id='LC217'>							<span class="c1">// inline-level elements accept inline-block;</span></div><div class='line' id='LC218'>							<span class="c1">// block-level elements need to be inline with layout</span></div><div class='line' id='LC219'>							<span class="k">if</span> <span class="p">(</span> <span class="nx">display</span> <span class="o">===</span> <span class="s2">&quot;inline&quot;</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC220'>								<span class="nx">thisStyle</span><span class="p">.</span><span class="nx">display</span> <span class="o">=</span> <span class="s2">&quot;inline-block&quot;</span><span class="p">;</span></div><div class='line' id='LC221'><br/></div><div class='line' id='LC222'>							<span class="p">}</span> <span class="k">else</span> <span class="p">{</span></div><div class='line' id='LC223'>								<span class="nx">thisStyle</span><span class="p">.</span><span class="nx">display</span> <span class="o">=</span> <span class="s2">&quot;inline&quot;</span><span class="p">;</span></div><div class='line' id='LC224'>								<span class="nx">thisStyle</span><span class="p">.</span><span class="nx">zoom</span> <span class="o">=</span> <span class="mi">1</span><span class="p">;</span></div><div class='line' id='LC225'>							<span class="p">}</span></div><div class='line' id='LC226'>						<span class="p">}</span></div><div class='line' id='LC227'>					<span class="p">}</span></div><div class='line' id='LC228'>				<span class="p">}</span></div><div class='line' id='LC229'><br/></div><div class='line' id='LC230'>				<span class="c1">// easing resolution: per property &gt; opt.specialEasing &gt; opt.easing &gt; &#39;swing&#39; (default)</span></div><div class='line' id='LC231'>				<span class="k">if</span> <span class="p">(</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">isArray</span><span class="p">(</span> <span class="nx">val</span> <span class="p">)</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC232'>					<span class="nx">easing</span> <span class="o">=</span> <span class="nx">val</span><span class="p">[</span><span class="mi">1</span><span class="p">];</span></div><div class='line' id='LC233'>					<span class="nx">val</span> <span class="o">=</span> <span class="nx">val</span><span class="p">[</span><span class="mi">0</span><span class="p">];</span></div><div class='line' id='LC234'>				<span class="p">}</span> <span class="k">else</span> <span class="p">{</span></div><div class='line' id='LC235'>					<span class="nx">easing</span> <span class="o">=</span> <span class="nx">opt</span><span class="p">.</span><span class="nx">specialEasing</span> <span class="o">&amp;&amp;</span> <span class="nx">opt</span><span class="p">.</span><span class="nx">specialEasing</span><span class="p">[</span><span class="nx">p</span><span class="p">]</span> <span class="o">||</span> <span class="nx">opt</span><span class="p">.</span><span class="nx">easing</span> <span class="o">||</span> <span class="s1">&#39;swing&#39;</span><span class="p">;</span></div><div class='line' id='LC236'>				<span class="p">}</span></div><div class='line' id='LC237'>				<span class="nx">opt</span><span class="p">.</span><span class="nx">animatedProperties</span><span class="p">[</span><span class="nx">p</span><span class="p">]</span> <span class="o">=</span> <span class="nx">easing</span><span class="p">;</span></div><div class='line' id='LC238'><br/></div><div class='line' id='LC239'>				<span class="c1">// TRANSITION++</span></div><div class='line' id='LC240'>				<span class="c1">// prevent transition when a special easing is supplied</span></div><div class='line' id='LC241'>				<span class="nx">transition</span> <span class="o">=</span> <span class="nx">supportTransition</span> <span class="o">&amp;&amp;</span> <span class="nx">isElement</span> <span class="o">&amp;&amp;</span> <span class="p">(</span></div><div class='line' id='LC242'>					<span class="c1">// we could use a hash to convert the names</span></div><div class='line' id='LC243'>					<span class="nx">easing</span> <span class="o">==</span> <span class="s1">&#39;swing&#39;</span> <span class="o">?</span> <span class="s1">&#39;ease&#39;</span><span class="o">:</span></div><div class='line' id='LC244'>					<span class="nx">easing</span> <span class="o">==</span> <span class="s1">&#39;linear&#39;</span> <span class="o">?</span> <span class="nx">easing</span><span class="o">:</span></div><div class='line' id='LC245'>					<span class="kc">false</span></div><div class='line' id='LC246'>				<span class="p">);</span></div><div class='line' id='LC247'><br/></div><div class='line' id='LC248'>				<span class="c1">// collect the properties to be added to elem.style.transition...</span></div><div class='line' id='LC249'>				<span class="k">if</span> <span class="p">(</span> <span class="nx">transition</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC250'>					<span class="nx">real</span> <span class="o">=</span> <span class="nx">cssProps</span><span class="p">[</span><span class="nx">p</span><span class="p">]</span> <span class="o">||</span> <span class="nx">p</span><span class="p">;</span></div><div class='line' id='LC251'><br/></div><div class='line' id='LC252'>					<span class="nx">lower</span> <span class="o">=</span> <span class="nx">real</span><span class="p">.</span><span class="nx">replace</span><span class="p">(</span><span class="sr">/([A-Z])/g</span><span class="p">,</span> <span class="s1">&#39;-$1&#39;</span><span class="p">).</span><span class="nx">toLowerCase</span><span class="p">();</span></div><div class='line' id='LC253'><br/></div><div class='line' id='LC254'>					<span class="nx">transition</span> <span class="o">=</span></div><div class='line' id='LC255'>						<span class="nx">lower</span> <span class="o">+</span><span class="s2">&quot; &quot;</span><span class="o">+</span></div><div class='line' id='LC256'>						<span class="nx">opt</span><span class="p">.</span><span class="nx">duration</span> <span class="o">+</span><span class="s2">&quot;ms &quot;</span><span class="o">+</span></div><div class='line' id='LC257'>						<span class="nx">transition</span><span class="p">;</span></div><div class='line' id='LC258'><br/></div><div class='line' id='LC259'>					<span class="nx">opt</span><span class="p">.</span><span class="nx">transition</span><span class="p">[</span><span class="nx">p</span><span class="p">]</span> <span class="o">=</span> <span class="p">{</span></div><div class='line' id='LC260'>						<span class="nx">lower</span><span class="o">:</span> <span class="nx">lower</span><span class="p">,</span></div><div class='line' id='LC261'>						<span class="nx">real</span><span class="o">:</span> <span class="nx">real</span></div><div class='line' id='LC262'>					<span class="p">};</span></div><div class='line' id='LC263'><br/></div><div class='line' id='LC264'>					<span class="nx">transitions</span><span class="p">.</span><span class="nx">push</span><span class="p">(</span><span class="nx">transition</span><span class="p">);</span></div><div class='line' id='LC265'>				<span class="p">}</span></div><div class='line' id='LC266'>			<span class="p">}</span></div><div class='line' id='LC267'><br/></div><div class='line' id='LC268'>			<span class="k">if</span> <span class="p">(</span> <span class="nx">opt</span><span class="p">.</span><span class="nx">overflow</span> <span class="o">!=</span> <span class="kc">null</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC269'>				<span class="nx">thisStyle</span><span class="p">.</span><span class="nx">overflow</span> <span class="o">=</span> <span class="s2">&quot;hidden&quot;</span><span class="p">;</span></div><div class='line' id='LC270'>			<span class="p">}</span></div><div class='line' id='LC271'><br/></div><div class='line' id='LC272'>			<span class="k">for</span> <span class="p">(</span> <span class="nx">p</span> <span class="k">in</span> <span class="nx">prop</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC273'>				<span class="nx">e</span> <span class="o">=</span> <span class="k">new</span> <span class="nx">fx</span><span class="p">(</span> <span class="nx">self</span><span class="p">,</span> <span class="nx">opt</span><span class="p">,</span> <span class="nx">p</span> <span class="p">);</span></div><div class='line' id='LC274'><br/></div><div class='line' id='LC275'>				<span class="nx">val</span> <span class="o">=</span> <span class="nx">prop</span><span class="p">[</span><span class="nx">p</span><span class="p">];</span></div><div class='line' id='LC276'><br/></div><div class='line' id='LC277'>				<span class="k">if</span> <span class="p">(</span> <span class="nx">rfxtypes</span><span class="p">.</span><span class="nx">test</span><span class="p">(</span><span class="nx">val</span><span class="p">)</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC278'>					<span class="nx">e</span><span class="p">[</span> <span class="nx">val</span> <span class="o">===</span> <span class="s2">&quot;toggle&quot;</span> <span class="o">?</span> <span class="nx">hidden</span> <span class="o">?</span> <span class="s2">&quot;show&quot;</span> <span class="o">:</span> <span class="s2">&quot;hide&quot;</span> <span class="o">:</span> <span class="nx">val</span> <span class="p">](</span> <span class="nx">startTime</span> <span class="p">);</span></div><div class='line' id='LC279'><br/></div><div class='line' id='LC280'>				<span class="p">}</span> <span class="k">else</span> <span class="p">{</span></div><div class='line' id='LC281'>					<span class="nx">parts</span> <span class="o">=</span> <span class="nx">rfxnum</span><span class="p">.</span><span class="nx">exec</span><span class="p">(</span><span class="nx">val</span><span class="p">);</span></div><div class='line' id='LC282'>					<span class="nx">start</span> <span class="o">=</span> <span class="nx">e</span><span class="p">.</span><span class="nx">cur</span><span class="p">();</span></div><div class='line' id='LC283'><br/></div><div class='line' id='LC284'>					<span class="k">if</span> <span class="p">(</span> <span class="nx">parts</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC285'>						<span class="nx">end</span> <span class="o">=</span> <span class="nb">parseFloat</span><span class="p">(</span> <span class="nx">parts</span><span class="p">[</span><span class="mi">2</span><span class="p">]</span> <span class="p">);</span></div><div class='line' id='LC286'>						<span class="nx">unit</span> <span class="o">=</span> <span class="nx">parts</span><span class="p">[</span><span class="mi">3</span><span class="p">]</span> <span class="o">||</span> <span class="p">(</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">cssNumber</span><span class="p">[</span> <span class="nx">name</span> <span class="p">]</span> <span class="o">?</span> <span class="s2">&quot;&quot;</span> <span class="o">:</span> <span class="s2">&quot;px&quot;</span> <span class="p">);</span></div><div class='line' id='LC287'><br/></div><div class='line' id='LC288'>						<span class="c1">// We need to compute starting value</span></div><div class='line' id='LC289'>						<span class="k">if</span> <span class="p">(</span> <span class="nx">unit</span> <span class="o">!==</span> <span class="s2">&quot;px&quot;</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC290'>							<span class="nx">style</span><span class="p">(</span> <span class="nx">self</span><span class="p">,</span> <span class="nx">p</span><span class="p">,</span> <span class="p">(</span><span class="nx">end</span> <span class="o">||</span> <span class="mi">1</span><span class="p">)</span> <span class="o">+</span> <span class="nx">unit</span><span class="p">);</span></div><div class='line' id='LC291'>							<span class="nx">start</span> <span class="o">=</span> <span class="p">((</span><span class="nx">end</span> <span class="o">||</span> <span class="mi">1</span><span class="p">)</span> <span class="o">/</span> <span class="nx">e</span><span class="p">.</span><span class="nx">cur</span><span class="p">())</span> <span class="o">*</span> <span class="nx">start</span><span class="p">;</span></div><div class='line' id='LC292'>							<span class="nx">style</span><span class="p">(</span> <span class="nx">self</span><span class="p">,</span> <span class="nx">p</span><span class="p">,</span> <span class="nx">start</span> <span class="o">+</span> <span class="nx">unit</span><span class="p">);</span></div><div class='line' id='LC293'>						<span class="p">}</span></div><div class='line' id='LC294'><br/></div><div class='line' id='LC295'>						<span class="c1">// If a +=/-= token was provided, we&#39;re doing a relative animation</span></div><div class='line' id='LC296'>						<span class="k">if</span> <span class="p">(</span> <span class="nx">parts</span><span class="p">[</span><span class="mi">1</span><span class="p">]</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC297'>							<span class="nx">end</span> <span class="o">=</span> <span class="p">((</span><span class="nx">parts</span><span class="p">[</span><span class="mi">1</span><span class="p">]</span> <span class="o">===</span> <span class="s2">&quot;-=&quot;</span> <span class="o">?</span> <span class="o">-</span><span class="mi">1</span> <span class="o">:</span> <span class="mi">1</span><span class="p">)</span> <span class="o">*</span> <span class="nx">end</span><span class="p">)</span> <span class="o">+</span> <span class="nx">start</span><span class="p">;</span></div><div class='line' id='LC298'>						<span class="p">}</span></div><div class='line' id='LC299'><br/></div><div class='line' id='LC300'>						<span class="nx">e</span><span class="p">.</span><span class="nx">custom</span><span class="p">(</span> <span class="nx">startTime</span><span class="p">,</span> <span class="nx">start</span><span class="p">,</span> <span class="nx">end</span><span class="p">,</span> <span class="nx">unit</span> <span class="p">);</span></div><div class='line' id='LC301'><br/></div><div class='line' id='LC302'>					<span class="p">}</span> <span class="k">else</span> <span class="p">{</span></div><div class='line' id='LC303'>						<span class="nx">e</span><span class="p">.</span><span class="nx">custom</span><span class="p">(</span> <span class="nx">startTime</span><span class="p">,</span> <span class="nx">start</span><span class="p">,</span> <span class="nx">val</span><span class="p">,</span> <span class="s2">&quot;&quot;</span> <span class="p">);</span></div><div class='line' id='LC304'>					<span class="p">}</span></div><div class='line' id='LC305'>				<span class="p">}</span></div><div class='line' id='LC306'>			<span class="p">}</span></div><div class='line' id='LC307'><br/></div><div class='line' id='LC308'>			<span class="c1">// TRANSITION++</span></div><div class='line' id='LC309'>			<span class="k">if</span> <span class="p">(</span> <span class="nx">supportTransition</span> <span class="o">&amp;&amp;</span> <span class="nx">transitions</span><span class="p">.</span><span class="nx">length</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC310'>				<span class="nx">transition</span> <span class="o">=</span> <span class="nx">thisStyle</span><span class="p">[</span><span class="nx">supportTransition</span><span class="p">];</span></div><div class='line' id='LC311'>				<span class="nx">thisStyle</span><span class="p">[</span><span class="nx">supportTransition</span><span class="p">]</span> <span class="o">=</span> <span class="nx">transitions</span><span class="p">.</span><span class="nx">join</span><span class="p">()</span> <span class="o">+</span> <span class="p">(</span><span class="nx">transition</span> <span class="o">?</span> <span class="s1">&#39;,&#39;</span> <span class="o">+</span> <span class="nx">transition</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">);</span></div><div class='line' id='LC312'>			<span class="p">}</span></div><div class='line' id='LC313'><br/></div><div class='line' id='LC314'>			<span class="c1">// For JS strict compliance</span></div><div class='line' id='LC315'>			<span class="k">return</span> <span class="kc">true</span><span class="p">;</span></div><div class='line' id='LC316'>		<span class="p">});</span></div><div class='line' id='LC317'>	<span class="p">},</span></div><div class='line' id='LC318'><br/></div><div class='line' id='LC319'>	<span class="nx">stop</span><span class="o">:</span> <span class="kd">function</span><span class="p">(</span> <span class="nx">clearQueue</span><span class="p">,</span> <span class="nx">gotoEnd</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC320'>		<span class="k">if</span> <span class="p">(</span> <span class="nx">clearQueue</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC321'>			<span class="k">this</span><span class="p">.</span><span class="nx">queue</span><span class="p">([]);</span></div><div class='line' id='LC322'>		<span class="p">}</span></div><div class='line' id='LC323'><br/></div><div class='line' id='LC324'>		<span class="k">this</span><span class="p">.</span><span class="nx">each</span><span class="p">(</span><span class="kd">function</span><span class="p">()</span> <span class="p">{</span></div><div class='line' id='LC325'>			<span class="kd">var</span> <span class="nx">timers</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">timers</span><span class="p">,</span></div><div class='line' id='LC326'>				<span class="nx">i</span> <span class="o">=</span> <span class="nx">timers</span><span class="p">.</span><span class="nx">length</span><span class="p">,</span></div><div class='line' id='LC327'>				<span class="nx">supportTransition</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">support</span><span class="p">.</span><span class="nx">transition</span><span class="p">;</span></div><div class='line' id='LC328'>			<span class="c1">// go in reverse order so anything added to the queue during the loop is ignored</span></div><div class='line' id='LC329'>			<span class="k">while</span> <span class="p">(</span> <span class="nx">i</span><span class="o">--</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC330'>				<span class="k">if</span> <span class="p">(</span> <span class="nx">timers</span><span class="p">[</span><span class="nx">i</span><span class="p">].</span><span class="nx">elem</span> <span class="o">===</span> <span class="k">this</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC331'>					<span class="k">if</span> <span class="p">(</span> <span class="nx">gotoEnd</span> <span class="o">||</span> <span class="nx">supportTransition</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC332'>						<span class="c1">// force the next step to be the last</span></div><div class='line' id='LC333'>						<span class="nx">timers</span><span class="p">[</span><span class="nx">i</span><span class="p">](</span><span class="nx">gotoEnd</span><span class="p">);</span></div><div class='line' id='LC334'>					<span class="p">}</span></div><div class='line' id='LC335'><br/></div><div class='line' id='LC336'>					<span class="nx">timers</span><span class="p">.</span><span class="nx">splice</span><span class="p">(</span><span class="nx">i</span><span class="p">,</span> <span class="mi">1</span><span class="p">);</span></div><div class='line' id='LC337'>				<span class="p">}</span></div><div class='line' id='LC338'>			<span class="p">}</span></div><div class='line' id='LC339'>		<span class="p">});</span></div><div class='line' id='LC340'><br/></div><div class='line' id='LC341'>		<span class="c1">// start the next in the queue if the last step wasn&#39;t forced</span></div><div class='line' id='LC342'>		<span class="k">if</span> <span class="p">(</span> <span class="o">!</span><span class="nx">gotoEnd</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC343'>			<span class="k">this</span><span class="p">.</span><span class="nx">dequeue</span><span class="p">();</span></div><div class='line' id='LC344'>		<span class="p">}</span></div><div class='line' id='LC345'><br/></div><div class='line' id='LC346'>		<span class="k">return</span> <span class="k">this</span><span class="p">;</span></div><div class='line' id='LC347'>	<span class="p">}</span></div><div class='line' id='LC348'><br/></div><div class='line' id='LC349'><span class="p">});</span></div><div class='line' id='LC350'><br/></div><div class='line' id='LC351'><span class="cm">/*function genFx( type, num ) {</span></div><div class='line' id='LC352'><span class="cm">	var obj = {};</span></div><div class='line' id='LC353'><br/></div><div class='line' id='LC354'><span class="cm">	jQuery.each( fxAttrs.concat.apply([], fxAttrs.slice(0,num)), function() {</span></div><div class='line' id='LC355'><span class="cm">		obj[ this ] = type;</span></div><div class='line' id='LC356'><span class="cm">	});</span></div><div class='line' id='LC357'><br/></div><div class='line' id='LC358'><span class="cm">	return obj;</span></div><div class='line' id='LC359'><span class="cm">}</span></div><div class='line' id='LC360'><br/></div><div class='line' id='LC361'><span class="cm">// Generate shortcuts for custom animations</span></div><div class='line' id='LC362'><span class="cm">jQuery.each({</span></div><div class='line' id='LC363'><span class="cm">	slideDown: genFx(&quot;show&quot;, 1),</span></div><div class='line' id='LC364'><span class="cm">	slideUp: genFx(&quot;hide&quot;, 1),</span></div><div class='line' id='LC365'><span class="cm">	slideToggle: genFx(&quot;toggle&quot;, 1),</span></div><div class='line' id='LC366'><span class="cm">	fadeIn: { opacity: &quot;show&quot; },</span></div><div class='line' id='LC367'><span class="cm">	fadeOut: { opacity: &quot;hide&quot; },</span></div><div class='line' id='LC368'><span class="cm">	fadeToggle: { opacity: &quot;toggle&quot; }</span></div><div class='line' id='LC369'><span class="cm">}, function( name, props ) {</span></div><div class='line' id='LC370'><span class="cm">	jQuery.fn[ name ] = function( speed, easing, callback ) {</span></div><div class='line' id='LC371'><span class="cm">		return this.animate( props, speed, easing, callback );</span></div><div class='line' id='LC372'><span class="cm">	};</span></div><div class='line' id='LC373'><span class="cm">});</span></div><div class='line' id='LC374'><br/></div><div class='line' id='LC375'><span class="cm">jQuery.extend({</span></div><div class='line' id='LC376'><span class="cm">	speed: function( speed, easing, fn ) {</span></div><div class='line' id='LC377'><span class="cm">		var opt = speed &amp;&amp; typeof speed === &quot;object&quot; ? jQuery.extend({}, speed) : {</span></div><div class='line' id='LC378'><span class="cm">			complete: fn || !fn &amp;&amp; easing ||</span></div><div class='line' id='LC379'><span class="cm">				jQuery.isFunction( speed ) &amp;&amp; speed,</span></div><div class='line' id='LC380'><span class="cm">			duration: speed,</span></div><div class='line' id='LC381'><span class="cm">			easing: fn &amp;&amp; easing || easing &amp;&amp; !jQuery.isFunction(easing) &amp;&amp; easing</span></div><div class='line' id='LC382'><span class="cm">		},</span></div><div class='line' id='LC383'><span class="cm">		fx = jQuery.fx;</span></div><div class='line' id='LC384'><br/></div><div class='line' id='LC385'><span class="cm">		opt.duration = fx.off ? 0 : typeof opt.duration === &quot;number&quot; ? opt.duration :</span></div><div class='line' id='LC386'><span class="cm">			opt.duration in fx.speeds ? fx.speeds[opt.duration] : fx.speeds._default;</span></div><div class='line' id='LC387'><br/></div><div class='line' id='LC388'><span class="cm">		// Queueing</span></div><div class='line' id='LC389'><span class="cm">		opt.old = opt.complete;</span></div><div class='line' id='LC390'><span class="cm">		opt.complete = function() {</span></div><div class='line' id='LC391'><span class="cm">			if ( opt.queue !== false ) {</span></div><div class='line' id='LC392'><span class="cm">				jQuery(this).dequeue();</span></div><div class='line' id='LC393'><span class="cm">			}</span></div><div class='line' id='LC394'><span class="cm">			if ( jQuery.isFunction( opt.old ) ) {</span></div><div class='line' id='LC395'><span class="cm">				opt.old.call( this );</span></div><div class='line' id='LC396'><span class="cm">			}</span></div><div class='line' id='LC397'><span class="cm">		};</span></div><div class='line' id='LC398'><br/></div><div class='line' id='LC399'><span class="cm">		return opt;</span></div><div class='line' id='LC400'><span class="cm">	},</span></div><div class='line' id='LC401'><br/></div><div class='line' id='LC402'><span class="cm">	easing: {</span></div><div class='line' id='LC403'><span class="cm">		linear: function( p, n, firstNum, diff ) {</span></div><div class='line' id='LC404'><span class="cm">			return firstNum + diff * p;</span></div><div class='line' id='LC405'><span class="cm">		},</span></div><div class='line' id='LC406'><span class="cm">		swing: function( p, n, firstNum, diff ) {</span></div><div class='line' id='LC407'><span class="cm">			return ((-Math.cos(p*Math.PI)/2) + 0.5) * diff + firstNum;</span></div><div class='line' id='LC408'><span class="cm">		}</span></div><div class='line' id='LC409'><span class="cm">	},</span></div><div class='line' id='LC410'><br/></div><div class='line' id='LC411'><span class="cm">	timers: [],</span></div><div class='line' id='LC412'><br/></div><div class='line' id='LC413'><span class="cm">	fx: function( elem, options, prop ) {</span></div><div class='line' id='LC414'><span class="cm">		this.options = options;</span></div><div class='line' id='LC415'><span class="cm">		this.elem = elem;</span></div><div class='line' id='LC416'><span class="cm">		this.prop = prop;</span></div><div class='line' id='LC417'><br/></div><div class='line' id='LC418'><span class="cm">		options.orig = options.orig || {};</span></div><div class='line' id='LC419'><span class="cm">	}</span></div><div class='line' id='LC420'><br/></div><div class='line' id='LC421'><span class="cm">});*/</span></div><div class='line' id='LC422'><br/></div><div class='line' id='LC423'><span class="nx">jQuery</span><span class="p">.</span><span class="nx">extend</span><span class="p">(</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">fx</span><span class="p">.</span><span class="nx">prototype</span><span class="p">,</span> <span class="p">{</span></div><div class='line' id='LC424'><span class="cm">/*jQuery.fx.prototype = {</span></div><div class='line' id='LC425'><span class="cm">	// Simple function for setting a style value</span></div><div class='line' id='LC426'><span class="cm">	update: function() {</span></div><div class='line' id='LC427'><span class="cm">		if ( this.options.step ) {</span></div><div class='line' id='LC428'><span class="cm">			this.options.step.call( this.elem, this.now, this );</span></div><div class='line' id='LC429'><span class="cm">		}</span></div><div class='line' id='LC430'><br/></div><div class='line' id='LC431'><span class="cm">		(jQuery.fx.step[this.prop] || jQuery.fx.step._default)( this );</span></div><div class='line' id='LC432'><span class="cm">	},*/</span></div><div class='line' id='LC433'><br/></div><div class='line' id='LC434'>	<span class="c1">// Get the current size</span></div><div class='line' id='LC435'>	<span class="nx">cur</span><span class="o">:</span> <span class="kd">function</span><span class="p">()</span> <span class="p">{</span></div><div class='line' id='LC436'>		<span class="kd">var</span> <span class="nx">elem</span> <span class="o">=</span> <span class="k">this</span><span class="p">.</span><span class="nx">elem</span><span class="p">,</span></div><div class='line' id='LC437'>			<span class="nx">prop</span> <span class="o">=</span> <span class="k">this</span><span class="p">.</span><span class="nx">prop</span><span class="p">,</span></div><div class='line' id='LC438'>			<span class="nx">r</span><span class="p">,</span></div><div class='line' id='LC439'>			<span class="nx">parsed</span><span class="p">;</span></div><div class='line' id='LC440'>		<span class="k">if</span> <span class="p">(</span> <span class="nx">elem</span><span class="p">[</span><span class="nx">prop</span><span class="p">]</span> <span class="o">!=</span> <span class="kc">null</span> <span class="o">&amp;&amp;</span> <span class="p">(</span><span class="o">!</span><span class="nx">elem</span><span class="p">.</span><span class="nx">style</span> <span class="o">||</span> <span class="nx">elem</span><span class="p">.</span><span class="nx">style</span><span class="p">[</span><span class="nx">prop</span><span class="p">]</span> <span class="o">==</span> <span class="kc">null</span><span class="p">)</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC441'>			<span class="k">return</span> <span class="nx">elem</span><span class="p">[</span> <span class="nx">prop</span> <span class="p">];</span></div><div class='line' id='LC442'>		<span class="p">}</span></div><div class='line' id='LC443'><br/></div><div class='line' id='LC444'>		<span class="nx">r</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">css</span><span class="p">(</span> <span class="nx">elem</span><span class="p">,</span> <span class="nx">prop</span> <span class="p">);</span></div><div class='line' id='LC445'>		<span class="c1">// Empty strings, null, undefined and &quot;auto&quot; are converted to 0,</span></div><div class='line' id='LC446'>		<span class="c1">// complex values such as &quot;rotate(1rad)&quot; are returned as is,</span></div><div class='line' id='LC447'>		<span class="c1">// simple values such as &quot;10px&quot; are parsed to Float.</span></div><div class='line' id='LC448'>		<span class="k">return</span> <span class="nb">isNaN</span><span class="p">(</span> <span class="nx">parsed</span> <span class="o">=</span> <span class="nb">parseFloat</span><span class="p">(</span> <span class="nx">r</span> <span class="p">)</span> <span class="p">)</span> <span class="o">?</span> <span class="o">!</span><span class="nx">r</span> <span class="o">||</span> <span class="nx">r</span> <span class="o">===</span> <span class="s2">&quot;auto&quot;</span> <span class="o">?</span> <span class="mi">0</span> <span class="o">:</span> <span class="nx">r</span> <span class="o">:</span> <span class="nx">parsed</span><span class="p">;</span></div><div class='line' id='LC449'>	<span class="p">},</span></div><div class='line' id='LC450'><br/></div><div class='line' id='LC451'>	<span class="c1">// Start an animation from one number to another</span></div><div class='line' id='LC452'>	<span class="nx">custom</span><span class="o">:</span> <span class="kd">function</span><span class="p">(</span> <span class="nx">startTime</span><span class="p">,</span> <span class="nx">from</span><span class="p">,</span> <span class="nx">to</span><span class="p">,</span> <span class="nx">unit</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC453'>		<span class="kd">var</span> <span class="nx">self</span> <span class="o">=</span> <span class="k">this</span><span class="p">,</span></div><div class='line' id='LC454'>			<span class="nx">fx</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">fx</span><span class="p">,</span></div><div class='line' id='LC455'>			<span class="nx">prop</span> <span class="o">=</span> <span class="nx">self</span><span class="p">.</span><span class="nx">prop</span><span class="p">,</span></div><div class='line' id='LC456'>			<span class="c1">// TRANSITION++</span></div><div class='line' id='LC457'>			<span class="nx">transition</span> <span class="o">=</span> <span class="nx">self</span><span class="p">.</span><span class="nx">options</span><span class="p">.</span><span class="nx">transition</span><span class="p">,</span></div><div class='line' id='LC458'>			<span class="nx">timers</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">timers</span><span class="p">,</span></div><div class='line' id='LC459'>			<span class="nx">hook</span><span class="p">;</span></div><div class='line' id='LC460'><br/></div><div class='line' id='LC461'>		<span class="nx">self</span><span class="p">.</span><span class="nx">startTime</span> <span class="o">=</span> <span class="nx">startTime</span><span class="p">;</span></div><div class='line' id='LC462'>		<span class="nx">self</span><span class="p">.</span><span class="nx">start</span> <span class="o">=</span> <span class="nx">from</span><span class="p">;</span></div><div class='line' id='LC463'>		<span class="nx">self</span><span class="p">.</span><span class="nx">end</span> <span class="o">=</span> <span class="nx">to</span><span class="p">;</span></div><div class='line' id='LC464'>		<span class="nx">self</span><span class="p">.</span><span class="nx">unit</span> <span class="o">=</span> <span class="nx">unit</span> <span class="o">||</span> <span class="nx">self</span><span class="p">.</span><span class="nx">unit</span> <span class="o">||</span> <span class="p">(</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">cssNumber</span><span class="p">[</span> <span class="nx">prop</span> <span class="p">]</span> <span class="o">?</span> <span class="s2">&quot;&quot;</span> <span class="o">:</span> <span class="s2">&quot;px&quot;</span> <span class="p">);</span></div><div class='line' id='LC465'>		<span class="nx">self</span><span class="p">.</span><span class="nx">now</span> <span class="o">=</span> <span class="nx">self</span><span class="p">.</span><span class="nx">start</span><span class="p">;</span></div><div class='line' id='LC466'>		<span class="nx">self</span><span class="p">.</span><span class="nx">pos</span> <span class="o">=</span> <span class="nx">self</span><span class="p">.</span><span class="nx">state</span> <span class="o">=</span> <span class="mi">0</span><span class="p">;</span></div><div class='line' id='LC467'><br/></div><div class='line' id='LC468'>		<span class="kd">function</span> <span class="nx">t</span><span class="p">(</span> <span class="nx">gotoEnd</span><span class="p">,</span> <span class="nx">now</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC469'>			<span class="k">return</span> <span class="nx">self</span><span class="p">.</span><span class="nx">step</span><span class="p">(</span> <span class="nx">gotoEnd</span><span class="p">,</span> <span class="nx">now</span> <span class="p">);</span></div><div class='line' id='LC470'>		<span class="p">}</span></div><div class='line' id='LC471'><br/></div><div class='line' id='LC472'>		<span class="nx">t</span><span class="p">.</span><span class="nx">elem</span> <span class="o">=</span> <span class="nx">self</span><span class="p">.</span><span class="nx">elem</span><span class="p">;</span></div><div class='line' id='LC473'><br/></div><div class='line' id='LC474'>		<span class="k">if</span> <span class="p">(</span> <span class="nx">transition</span><span class="p">[</span><span class="nx">prop</span><span class="p">]</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC475'>			<span class="nx">timers</span><span class="p">.</span><span class="nx">push</span><span class="p">(</span><span class="nx">t</span><span class="p">);</span></div><div class='line' id='LC476'><br/></div><div class='line' id='LC477'>			<span class="c1">// explicitely set the property to it&#39;s current computed value to workaround bugzil.la/571344</span></div><div class='line' id='LC478'>			<span class="nx">self</span><span class="p">.</span><span class="nx">elem</span><span class="p">.</span><span class="nx">style</span><span class="p">[</span><span class="nx">transition</span><span class="p">[</span><span class="nx">prop</span><span class="p">].</span><span class="nx">real</span><span class="p">]</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">css</span><span class="p">(</span> <span class="nx">self</span><span class="p">.</span><span class="nx">elem</span><span class="p">,</span> <span class="nx">prop</span> <span class="p">);</span></div><div class='line' id='LC479'><br/></div><div class='line' id='LC480'>			<span class="c1">// Don&#39;t set the style immediatly, the transition property has not been filled yet</span></div><div class='line' id='LC481'>			<span class="nx">setTimeout</span><span class="p">(</span><span class="kd">function</span><span class="p">()</span> <span class="p">{</span></div><div class='line' id='LC482'>				<span class="nx">jQuery</span><span class="p">.</span><span class="nx">style</span><span class="p">(</span> <span class="nx">self</span><span class="p">.</span><span class="nx">elem</span><span class="p">,</span> <span class="nx">prop</span><span class="p">,</span> <span class="nx">to</span> <span class="o">+</span> <span class="nx">self</span><span class="p">.</span><span class="nx">unit</span> <span class="p">);</span></div><div class='line' id='LC483'><br/></div><div class='line' id='LC484'>				<span class="c1">// use a setTimeout to detect the end of a transition</span></div><div class='line' id='LC485'>				<span class="c1">// the transitionend event is unreliable</span></div><div class='line' id='LC486'>				<span class="nx">transition</span><span class="p">[</span><span class="nx">prop</span><span class="p">].</span><span class="nx">timeout</span> <span class="o">=</span> <span class="nx">setTimeout</span><span class="p">(</span><span class="kd">function</span><span class="p">()</span> <span class="p">{</span></div><div class='line' id='LC487'>					<span class="nx">timers</span><span class="p">.</span><span class="nx">splice</span><span class="p">(</span><span class="nx">timers</span><span class="p">.</span><span class="nx">indexOf</span><span class="p">(</span><span class="nx">t</span><span class="p">),</span> <span class="mi">1</span><span class="p">);</span></div><div class='line' id='LC488'>					<span class="nx">self</span><span class="p">.</span><span class="nx">step</span><span class="p">(</span><span class="kc">true</span><span class="p">);</span></div><div class='line' id='LC489'>				<span class="c1">// add an unperceptible delay to help some tests pass in Firefox</span></div><div class='line' id='LC490'>				<span class="p">},</span> <span class="nx">self</span><span class="p">.</span><span class="nx">options</span><span class="p">.</span><span class="nx">duration</span> <span class="o">+</span> <span class="mi">30</span><span class="p">);</span></div><div class='line' id='LC491'>			<span class="p">},</span> <span class="mi">0</span><span class="p">);</span></div><div class='line' id='LC492'><br/></div><div class='line' id='LC493'>		<span class="p">}</span> <span class="k">else</span> <span class="k">if</span> <span class="p">(</span> <span class="nx">t</span><span class="p">(</span> <span class="kc">false</span><span class="p">,</span> <span class="nx">startTime</span> <span class="p">)</span> <span class="o">&amp;&amp;</span> <span class="nx">timers</span><span class="p">.</span><span class="nx">push</span><span class="p">(</span><span class="nx">t</span><span class="p">)</span> <span class="o">&amp;&amp;</span> <span class="o">!</span><span class="nx">timerId</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC494'>			<span class="nx">timerId</span> <span class="o">=</span> <span class="nx">setInterval</span><span class="p">(</span><span class="nx">fx</span><span class="p">.</span><span class="nx">tick</span><span class="p">,</span> <span class="nx">fx</span><span class="p">.</span><span class="nx">interval</span><span class="p">);</span></div><div class='line' id='LC495'>		<span class="p">}</span></div><div class='line' id='LC496'>	<span class="p">},</span></div><div class='line' id='LC497'><br/></div><div class='line' id='LC498'>	<span class="cm">/*// Simple &#39;show&#39; function</span></div><div class='line' id='LC499'><span class="cm">	show: function( startTime ) {</span></div><div class='line' id='LC500'><span class="cm">		// Remember where we started, so that we can go back to it later</span></div><div class='line' id='LC501'><span class="cm">		this.options.orig[this.prop] = jQuery.style( this.elem, this.prop );</span></div><div class='line' id='LC502'><span class="cm">		this.options.show = true;</span></div><div class='line' id='LC503'><br/></div><div class='line' id='LC504'><span class="cm">		// Begin the animation</span></div><div class='line' id='LC505'><span class="cm">		// Make sure that we start at a small width/height to avoid any</span></div><div class='line' id='LC506'><span class="cm">		// flash of content</span></div><div class='line' id='LC507'><span class="cm">		this.custom( startTime, this.prop === &quot;width&quot; || this.prop === &quot;height&quot; ? 1 : 0, this.cur() );</span></div><div class='line' id='LC508'><br/></div><div class='line' id='LC509'><span class="cm">		// Start by showing the element</span></div><div class='line' id='LC510'><span class="cm">		jQuery( this.elem ).show();</span></div><div class='line' id='LC511'><span class="cm">	},</span></div><div class='line' id='LC512'><br/></div><div class='line' id='LC513'><span class="cm">	// Simple &#39;hide&#39; function</span></div><div class='line' id='LC514'><span class="cm">	hide: function( startTime ) {</span></div><div class='line' id='LC515'><span class="cm">		// Remember where we started, so that we can go back to it later</span></div><div class='line' id='LC516'><span class="cm">		this.options.orig[this.prop] = jQuery.style( this.elem, this.prop );</span></div><div class='line' id='LC517'><span class="cm">		this.options.hide = true;</span></div><div class='line' id='LC518'><br/></div><div class='line' id='LC519'><span class="cm">		// Begin the animation</span></div><div class='line' id='LC520'><span class="cm">		this.custom( startTime, this.cur(), 0 );</span></div><div class='line' id='LC521'><span class="cm">	},*/</span></div><div class='line' id='LC522'><br/></div><div class='line' id='LC523'>	<span class="c1">// Each step of an animation</span></div><div class='line' id='LC524'>	<span class="nx">step</span><span class="o">:</span> <span class="kd">function</span><span class="p">(</span> <span class="nx">gotoEnd</span><span class="p">,</span> <span class="nx">t</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC525'>		<span class="kd">var</span> <span class="nx">done</span> <span class="o">=</span> <span class="kc">true</span><span class="p">,</span></div><div class='line' id='LC526'>			<span class="nx">elem</span> <span class="o">=</span> <span class="k">this</span><span class="p">.</span><span class="nx">elem</span><span class="p">,</span></div><div class='line' id='LC527'>			<span class="nx">options</span> <span class="o">=</span> <span class="k">this</span><span class="p">.</span><span class="nx">options</span><span class="p">,</span></div><div class='line' id='LC528'>			<span class="nx">duration</span> <span class="o">=</span> <span class="nx">options</span><span class="p">.</span><span class="nx">duration</span><span class="p">,</span></div><div class='line' id='LC529'>			<span class="c1">// TRANSITION++</span></div><div class='line' id='LC530'>			<span class="nx">prop</span> <span class="o">=</span> <span class="k">this</span><span class="p">.</span><span class="nx">prop</span><span class="p">,</span></div><div class='line' id='LC531'>			<span class="nx">transition</span> <span class="o">=</span> <span class="nx">options</span><span class="p">.</span><span class="nx">transition</span><span class="p">[</span><span class="k">this</span><span class="p">.</span><span class="nx">prop</span><span class="p">],</span></div><div class='line' id='LC532'>			<span class="nx">supportTransition</span><span class="p">,</span></div><div class='line' id='LC533'>			<span class="nx">hook</span><span class="p">,</span></div><div class='line' id='LC534'>			<span class="nx">i</span><span class="p">,</span> <span class="nx">p</span><span class="p">,</span> <span class="nx">style</span><span class="p">;</span></div><div class='line' id='LC535'><br/></div><div class='line' id='LC536'>		<span class="k">if</span> <span class="p">(</span> <span class="nx">transition</span> <span class="o">||</span> <span class="nx">gotoEnd</span> <span class="o">||</span> <span class="nx">t</span> <span class="o">&gt;=</span> <span class="nx">duration</span> <span class="o">+</span> <span class="k">this</span><span class="p">.</span><span class="nx">startTime</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC537'>			<span class="k">if</span> <span class="p">(</span> <span class="o">!</span><span class="nx">transition</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC538'>				<span class="k">this</span><span class="p">.</span><span class="nx">now</span> <span class="o">=</span> <span class="k">this</span><span class="p">.</span><span class="nx">end</span><span class="p">;</span></div><div class='line' id='LC539'>				<span class="k">this</span><span class="p">.</span><span class="nx">pos</span> <span class="o">=</span> <span class="k">this</span><span class="p">.</span><span class="nx">state</span> <span class="o">=</span> <span class="mi">1</span><span class="p">;</span></div><div class='line' id='LC540'>				<span class="k">this</span><span class="p">.</span><span class="nx">update</span><span class="p">();</span></div><div class='line' id='LC541'><br/></div><div class='line' id='LC542'>			<span class="c1">// TRANSITION++</span></div><div class='line' id='LC543'>			<span class="p">}</span> <span class="k">else</span> <span class="p">{</span></div><div class='line' id='LC544'>				<span class="nx">clearTimeout</span><span class="p">(</span><span class="nx">transition</span><span class="p">.</span><span class="nx">timeout</span><span class="p">);</span></div><div class='line' id='LC545'>				<span class="c1">// Stop a transition halfway through</span></div><div class='line' id='LC546'>				<span class="k">if</span> <span class="p">(</span> <span class="o">!</span><span class="nx">gotoEnd</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC547'>					<span class="c1">// yes, stoping a transition halfway through should be as simple as setting a property to its current value.</span></div><div class='line' id='LC548'>					<span class="c1">// Try to call window.getComputedStyle() only once per element (in tick()?)</span></div><div class='line' id='LC549'>					<span class="k">this</span><span class="p">.</span><span class="nx">elem</span><span class="p">.</span><span class="nx">style</span><span class="p">[</span><span class="nx">transition</span><span class="p">.</span><span class="nx">real</span><span class="p">]</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">css</span><span class="p">(</span> <span class="k">this</span><span class="p">.</span><span class="nx">elem</span><span class="p">,</span> <span class="nx">transition</span><span class="p">.</span><span class="nx">real</span> <span class="p">);</span></div><div class='line' id='LC550'>				<span class="p">}</span></div><div class='line' id='LC551'>			<span class="p">}</span></div><div class='line' id='LC552'><br/></div><div class='line' id='LC553'>			<span class="nx">options</span><span class="p">.</span><span class="nx">animatedProperties</span><span class="p">[</span> <span class="k">this</span><span class="p">.</span><span class="nx">prop</span> <span class="p">]</span> <span class="o">=</span> <span class="kc">true</span><span class="p">;</span></div><div class='line' id='LC554'><br/></div><div class='line' id='LC555'>			<span class="k">for</span> <span class="p">(</span> <span class="nx">i</span> <span class="k">in</span> <span class="nx">options</span><span class="p">.</span><span class="nx">animatedProperties</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC556'>				<span class="k">if</span> <span class="p">(</span> <span class="nx">options</span><span class="p">.</span><span class="nx">animatedProperties</span><span class="p">[</span><span class="nx">i</span><span class="p">]</span> <span class="o">!==</span> <span class="kc">true</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC557'>					<span class="nx">done</span> <span class="o">=</span> <span class="kc">false</span><span class="p">;</span></div><div class='line' id='LC558'>				<span class="p">}</span></div><div class='line' id='LC559'>			<span class="p">}</span></div><div class='line' id='LC560'><br/></div><div class='line' id='LC561'>			<span class="k">if</span> <span class="p">(</span> <span class="nx">done</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC562'>				<span class="c1">// Reset the overflow</span></div><div class='line' id='LC563'>				<span class="k">if</span> <span class="p">(</span> <span class="nx">options</span><span class="p">.</span><span class="nx">overflow</span> <span class="o">!=</span> <span class="kc">null</span> <span class="o">&amp;&amp;</span> <span class="o">!</span><span class="nx">jQuery</span><span class="p">.</span><span class="nx">support</span><span class="p">.</span><span class="nx">shrinkWrapBlocks</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC564'><br/></div><div class='line' id='LC565'>					<span class="nx">jQuery</span><span class="p">.</span><span class="nx">each</span><span class="p">(</span> <span class="p">[</span> <span class="s2">&quot;&quot;</span><span class="p">,</span> <span class="s2">&quot;X&quot;</span><span class="p">,</span> <span class="s2">&quot;Y&quot;</span> <span class="p">],</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">index</span><span class="p">,</span> <span class="nx">value</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC566'>						<span class="nx">elem</span><span class="p">.</span><span class="nx">style</span><span class="p">[</span> <span class="s2">&quot;overflow&quot;</span> <span class="o">+</span> <span class="nx">value</span> <span class="p">]</span> <span class="o">=</span> <span class="nx">options</span><span class="p">.</span><span class="nx">overflow</span><span class="p">[</span><span class="nx">index</span><span class="p">];</span></div><div class='line' id='LC567'>					<span class="p">}</span> <span class="p">);</span></div><div class='line' id='LC568'>				<span class="p">}</span></div><div class='line' id='LC569'><br/></div><div class='line' id='LC570'>				<span class="c1">// Hide the element if the &quot;hide&quot; operation was done</span></div><div class='line' id='LC571'>				<span class="k">if</span> <span class="p">(</span> <span class="nx">options</span><span class="p">.</span><span class="nx">hide</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC572'>					<span class="nx">jQuery</span><span class="p">(</span><span class="nx">elem</span><span class="p">).</span><span class="nx">hide</span><span class="p">();</span></div><div class='line' id='LC573'>				<span class="p">}</span></div><div class='line' id='LC574'><br/></div><div class='line' id='LC575'>				<span class="c1">// Reset the properties, if the item has been hidden or shown</span></div><div class='line' id='LC576'>				<span class="k">if</span> <span class="p">(</span> <span class="nx">options</span><span class="p">.</span><span class="nx">hide</span> <span class="o">||</span> <span class="nx">options</span><span class="p">.</span><span class="nx">show</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC577'>					<span class="nx">style</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">style</span><span class="p">;</span></div><div class='line' id='LC578'>					<span class="k">for</span> <span class="p">(</span> <span class="nx">p</span> <span class="k">in</span> <span class="nx">options</span><span class="p">.</span><span class="nx">animatedProperties</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC579'>						<span class="nx">style</span><span class="p">(</span> <span class="nx">elem</span><span class="p">,</span> <span class="nx">p</span><span class="p">,</span> <span class="nx">options</span><span class="p">.</span><span class="nx">orig</span><span class="p">[</span><span class="nx">p</span><span class="p">]</span> <span class="p">);</span></div><div class='line' id='LC580'>					<span class="p">}</span></div><div class='line' id='LC581'>				<span class="p">}</span></div><div class='line' id='LC582'><br/></div><div class='line' id='LC583'>				<span class="c1">// TRANSITION++</span></div><div class='line' id='LC584'>				<span class="c1">// cleanup the transition property</span></div><div class='line' id='LC585'>				<span class="k">if</span> <span class="p">(</span> <span class="p">(</span><span class="nx">supportTransition</span> <span class="o">=</span> <span class="nx">elem</span><span class="p">.</span><span class="nx">nodeType</span> <span class="o">===</span> <span class="mi">1</span> <span class="o">&amp;&amp;</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">support</span><span class="p">.</span><span class="nx">transition</span><span class="p">)</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC586'>					<span class="nx">transition</span> <span class="o">=</span> <span class="s1">&#39;,&#39;</span> <span class="o">+</span> <span class="nx">elem</span><span class="p">.</span><span class="nx">style</span><span class="p">[</span><span class="nx">supportTransition</span><span class="p">];</span></div><div class='line' id='LC587'>					<span class="k">for</span> <span class="p">(</span> <span class="nx">p</span> <span class="k">in</span> <span class="nx">options</span><span class="p">.</span><span class="nx">transition</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC588'>						<span class="nx">transition</span> <span class="o">=</span> <span class="nx">transition</span><span class="p">.</span><span class="nx">split</span><span class="p">(</span> <span class="nx">options</span><span class="p">.</span><span class="nx">transition</span><span class="p">[</span><span class="nx">p</span><span class="p">].</span><span class="nx">lower</span> <span class="p">).</span><span class="nx">join</span><span class="p">(</span><span class="s1">&#39;_&#39;</span><span class="p">);</span></div><div class='line' id='LC589'>					<span class="p">}</span></div><div class='line' id='LC590'>					<span class="nx">elem</span><span class="p">.</span><span class="nx">style</span><span class="p">[</span><span class="nx">supportTransition</span><span class="p">]</span> <span class="o">=</span> <span class="nx">transition</span><span class="p">.</span><span class="nx">replace</span><span class="p">(</span><span class="sr">/, ?_[^,]*/g</span><span class="p">,</span> <span class="s1">&#39;&#39;</span><span class="p">).</span><span class="nx">substr</span><span class="p">(</span><span class="mi">1</span><span class="p">);</span></div><div class='line' id='LC591'>				<span class="p">}</span></div><div class='line' id='LC592'><br/></div><div class='line' id='LC593'>				<span class="c1">// Execute the complete function</span></div><div class='line' id='LC594'>				<span class="nx">options</span><span class="p">.</span><span class="nx">complete</span><span class="p">.</span><span class="nx">call</span><span class="p">(</span> <span class="nx">elem</span> <span class="p">);</span></div><div class='line' id='LC595'>			<span class="p">}</span></div><div class='line' id='LC596'><br/></div><div class='line' id='LC597'>			<span class="k">return</span> <span class="kc">false</span><span class="p">;</span></div><div class='line' id='LC598'><br/></div><div class='line' id='LC599'>		<span class="p">}</span> <span class="k">else</span> <span class="p">{</span></div><div class='line' id='LC600'>			<span class="c1">// classical easing cannot be used with an Infinity duration</span></div><div class='line' id='LC601'>			<span class="k">if</span> <span class="p">(</span><span class="nx">duration</span> <span class="o">==</span> <span class="kc">Infinity</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC602'>				<span class="k">this</span><span class="p">.</span><span class="nx">now</span> <span class="o">=</span> <span class="nx">t</span><span class="p">;</span></div><div class='line' id='LC603'>			<span class="p">}</span> <span class="k">else</span> <span class="p">{</span></div><div class='line' id='LC604'>				<span class="kd">var</span> <span class="nx">n</span> <span class="o">=</span> <span class="nx">t</span> <span class="o">-</span> <span class="k">this</span><span class="p">.</span><span class="nx">startTime</span><span class="p">;</span></div><div class='line' id='LC605'><br/></div><div class='line' id='LC606'>				<span class="k">this</span><span class="p">.</span><span class="nx">state</span> <span class="o">=</span> <span class="nx">n</span> <span class="o">/</span> <span class="nx">duration</span><span class="p">;</span></div><div class='line' id='LC607'>				<span class="c1">// Perform the easing function, defaults to swing</span></div><div class='line' id='LC608'>				<span class="k">this</span><span class="p">.</span><span class="nx">pos</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">easing</span><span class="p">[</span><span class="nx">options</span><span class="p">.</span><span class="nx">animatedProperties</span><span class="p">[</span><span class="k">this</span><span class="p">.</span><span class="nx">prop</span><span class="p">]](</span><span class="k">this</span><span class="p">.</span><span class="nx">state</span><span class="p">,</span> <span class="nx">n</span><span class="p">,</span> <span class="mi">0</span><span class="p">,</span> <span class="mi">1</span><span class="p">,</span> <span class="nx">duration</span><span class="p">);</span></div><div class='line' id='LC609'>				<span class="k">this</span><span class="p">.</span><span class="nx">now</span> <span class="o">=</span> <span class="k">this</span><span class="p">.</span><span class="nx">start</span> <span class="o">+</span> <span class="p">((</span><span class="k">this</span><span class="p">.</span><span class="nx">end</span> <span class="o">-</span> <span class="k">this</span><span class="p">.</span><span class="nx">start</span><span class="p">)</span> <span class="o">*</span> <span class="k">this</span><span class="p">.</span><span class="nx">pos</span><span class="p">);</span></div><div class='line' id='LC610'>			<span class="p">}</span></div><div class='line' id='LC611'>			<span class="c1">// Perform the next step of the animation</span></div><div class='line' id='LC612'>			<span class="k">this</span><span class="p">.</span><span class="nx">update</span><span class="p">();</span></div><div class='line' id='LC613'>		<span class="p">}</span></div><div class='line' id='LC614'><br/></div><div class='line' id='LC615'>		<span class="k">return</span> <span class="kc">true</span><span class="p">;</span></div><div class='line' id='LC616'>	<span class="p">}</span></div><div class='line' id='LC617'><span class="c1">//};</span></div><div class='line' id='LC618'><span class="p">});</span></div><div class='line' id='LC619'><br/></div><div class='line' id='LC620'><span class="nx">jQuery</span><span class="p">.</span><span class="nx">extend</span><span class="p">(</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">fx</span><span class="p">,</span> <span class="p">{</span></div><div class='line' id='LC621'>	<span class="nx">tick</span><span class="o">:</span> <span class="kd">function</span><span class="p">()</span> <span class="p">{</span></div><div class='line' id='LC622'>		<span class="kd">var</span> <span class="nx">timers</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">timers</span><span class="p">,</span></div><div class='line' id='LC623'>			<span class="nx">i</span> <span class="o">=</span> <span class="mi">0</span><span class="p">,</span></div><div class='line' id='LC624'>			<span class="nx">now</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">now</span><span class="p">();</span></div><div class='line' id='LC625'><br/></div><div class='line' id='LC626'>		<span class="c1">// don&#39;t cache timers.length since it might change at any time.</span></div><div class='line' id='LC627'>		<span class="k">for</span> <span class="p">(</span> <span class="p">;</span> <span class="nx">i</span> <span class="o">&lt;</span> <span class="nx">timers</span><span class="p">.</span><span class="nx">length</span><span class="p">;</span> <span class="nx">i</span><span class="o">++</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC628'>			<span class="k">if</span> <span class="p">(</span> <span class="o">!</span><span class="nx">timers</span><span class="p">[</span><span class="nx">i</span><span class="p">](</span> <span class="kc">false</span><span class="p">,</span> <span class="nx">now</span> <span class="p">)</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC629'>				<span class="nx">timers</span><span class="p">.</span><span class="nx">splice</span><span class="p">(</span><span class="nx">i</span><span class="o">--</span><span class="p">,</span> <span class="mi">1</span><span class="p">);</span></div><div class='line' id='LC630'>			<span class="p">}</span></div><div class='line' id='LC631'>		<span class="p">}</span></div><div class='line' id='LC632'><br/></div><div class='line' id='LC633'>		<span class="k">if</span> <span class="p">(</span> <span class="o">!</span><span class="nx">timers</span><span class="p">.</span><span class="nx">length</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC634'>			<span class="nx">jQuery</span><span class="p">.</span><span class="nx">fx</span><span class="p">.</span><span class="nx">stop</span><span class="p">();</span></div><div class='line' id='LC635'>		<span class="p">}</span></div><div class='line' id='LC636'>	<span class="p">}</span><span class="cm">/*,</span></div><div class='line' id='LC637'><br/></div><div class='line' id='LC638'><span class="cm">	interval: 13,</span></div><div class='line' id='LC639'><br/></div><div class='line' id='LC640'><span class="cm">	stop: function() {</span></div><div class='line' id='LC641'><span class="cm">		clearInterval( timerId );</span></div><div class='line' id='LC642'><span class="cm">		timerId = null;</span></div><div class='line' id='LC643'><span class="cm">	},</span></div><div class='line' id='LC644'><br/></div><div class='line' id='LC645'><span class="cm">	speeds: {</span></div><div class='line' id='LC646'><span class="cm">		slow: 600,</span></div><div class='line' id='LC647'><span class="cm">		fast: 200,</span></div><div class='line' id='LC648'><span class="cm">		// Default speed</span></div><div class='line' id='LC649'><span class="cm">		_default: 400</span></div><div class='line' id='LC650'><span class="cm">	},</span></div><div class='line' id='LC651'><br/></div><div class='line' id='LC652'><span class="cm">	step: {</span></div><div class='line' id='LC653'><span class="cm">		opacity: function( fx ) {</span></div><div class='line' id='LC654'><span class="cm">			jQuery.style( fx.elem, &quot;opacity&quot;, fx.now );</span></div><div class='line' id='LC655'><span class="cm">		},</span></div><div class='line' id='LC656'><br/></div><div class='line' id='LC657'><span class="cm">		_default: function( fx ) {</span></div><div class='line' id='LC658'><span class="cm">			if ( fx.elem.style &amp;&amp; fx.elem.style[ fx.prop ] != null ) {</span></div><div class='line' id='LC659'><span class="cm">				fx.elem.style[ fx.prop ] = (fx.prop === &quot;width&quot; || fx.prop === &quot;height&quot; ? Math.max(0, fx.now) : fx.now) + fx.unit;</span></div><div class='line' id='LC660'><span class="cm">			} else {</span></div><div class='line' id='LC661'><span class="cm">				fx.elem[ fx.prop ] = fx.now;</span></div><div class='line' id='LC662'><span class="cm">			}</span></div><div class='line' id='LC663'><span class="cm">		}</span></div><div class='line' id='LC664'><span class="cm">	}*/</span></div><div class='line' id='LC665'><span class="p">});</span></div><div class='line' id='LC666'><br/></div><div class='line' id='LC667'><span class="cm">/*if ( jQuery.expr &amp;&amp; jQuery.expr.filters ) {</span></div><div class='line' id='LC668'><span class="cm">	jQuery.expr.filters.animated = function( elem ) {</span></div><div class='line' id='LC669'><span class="cm">		return jQuery.grep(jQuery.timers, function( fn ) {</span></div><div class='line' id='LC670'><span class="cm">			return elem === fn.elem;</span></div><div class='line' id='LC671'><span class="cm">		}).length;</span></div><div class='line' id='LC672'><span class="cm">	};</span></div><div class='line' id='LC673'><span class="cm">}*/</span></div><div class='line' id='LC674'><br/></div><div class='line' id='LC675'><span class="kd">function</span> <span class="nx">defaultDisplay</span><span class="p">(</span> <span class="nx">nodeName</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC676'>	<span class="k">if</span> <span class="p">(</span> <span class="o">!</span><span class="nx">elemdisplay</span><span class="p">[</span> <span class="nx">nodeName</span> <span class="p">]</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC677'>		<span class="kd">var</span> <span class="nx">elem</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">(</span><span class="s2">&quot;&lt;&quot;</span> <span class="o">+</span> <span class="nx">nodeName</span> <span class="o">+</span> <span class="s2">&quot;&gt;&quot;</span><span class="p">).</span><span class="nx">appendTo</span><span class="p">(</span><span class="s2">&quot;body&quot;</span><span class="p">),</span></div><div class='line' id='LC678'>			<span class="nx">display</span> <span class="o">=</span> <span class="nx">elem</span><span class="p">.</span><span class="nx">css</span><span class="p">(</span><span class="s2">&quot;display&quot;</span><span class="p">);</span></div><div class='line' id='LC679'><br/></div><div class='line' id='LC680'>		<span class="nx">elem</span><span class="p">.</span><span class="nx">remove</span><span class="p">();</span></div><div class='line' id='LC681'><br/></div><div class='line' id='LC682'>		<span class="k">if</span> <span class="p">(</span> <span class="nx">display</span> <span class="o">===</span> <span class="s2">&quot;none&quot;</span> <span class="o">||</span> <span class="nx">display</span> <span class="o">===</span> <span class="s2">&quot;&quot;</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC683'>			<span class="nx">display</span> <span class="o">=</span> <span class="s2">&quot;block&quot;</span><span class="p">;</span></div><div class='line' id='LC684'>		<span class="p">}</span></div><div class='line' id='LC685'><br/></div><div class='line' id='LC686'>		<span class="nx">elemdisplay</span><span class="p">[</span> <span class="nx">nodeName</span> <span class="p">]</span> <span class="o">=</span> <span class="nx">display</span><span class="p">;</span></div><div class='line' id='LC687'>	<span class="p">}</span></div><div class='line' id='LC688'><br/></div><div class='line' id='LC689'>	<span class="k">return</span> <span class="nx">elemdisplay</span><span class="p">[</span> <span class="nx">nodeName</span> <span class="p">];</span></div><div class='line' id='LC690'><span class="p">}</span></div><div class='line' id='LC691'><br/></div><div class='line' id='LC692'><span class="c1">// Function to synchronize now() values between animations</span></div><div class='line' id='LC693'><span class="kd">function</span> <span class="nx">effectsNow</span><span class="p">()</span> <span class="p">{</span></div><div class='line' id='LC694'>	<span class="k">if</span> <span class="p">(</span> <span class="o">!</span><span class="nx">effectsTimestamp</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC695'>		<span class="nx">effectsTimestamp</span> <span class="o">=</span> <span class="nx">jQuery</span><span class="p">.</span><span class="nx">now</span><span class="p">();</span></div><div class='line' id='LC696'>		<span class="nx">setTimeout</span><span class="p">(</span><span class="kd">function</span><span class="p">()</span> <span class="p">{</span></div><div class='line' id='LC697'>			<span class="nx">effectsTimestamp</span> <span class="o">=</span> <span class="kc">null</span><span class="p">;</span></div><div class='line' id='LC698'>		<span class="p">},</span> <span class="mi">0</span><span class="p">);</span></div><div class='line' id='LC699'>	<span class="p">}</span></div><div class='line' id='LC700'>	<span class="k">return</span> <span class="nx">effectsTimestamp</span><span class="p">;</span></div><div class='line' id='LC701'><span class="p">}</span></div><div class='line' id='LC702'><br/></div><div class='line' id='LC703'><span class="p">})(</span> <span class="nx">jQuery</span> <span class="p">);</span></div></pre></div>
              
            
          </td>
        </tr>
      </table>
    
  </div>


          </div>
        </div>
      </div>
    </div>
  

  </div>


<div class="frame frame-loading" style="display:none;">
  <img src="https://d3nwyuy0nl342s.cloudfront.net/images/modules/ajax/big_spinner_336699.gif" height="32" width="32">
</div>

    </div>
  
      
    </div>

    <div id="footer" class="clearfix">
      <div class="site">
        <div class="sponsor">
          <a href="http://www.rackspace.com" class="logo">
            <img alt="Dedicated Server" height="36" src="https://d3nwyuy0nl342s.cloudfront.net/images/modules/footer/rackspace_logo.png?v2" width="38" />
          </a>
          Powered by the <a href="http://www.rackspace.com ">Dedicated
          Servers</a> and<br/> <a href="http://www.rackspacecloud.com">Cloud
          Computing</a> of Rackspace Hosting<span>&reg;</span>
        </div>

        <ul class="links">
          <li class="blog"><a href="https://github.com/blog">Blog</a></li>
          <li><a href="/login/multipass?to=http%3A%2F%2Fsupport.github.com">Support</a></li>
          <li><a href="https://github.com/training">Training</a></li>
          <li><a href="http://jobs.github.com">Job Board</a></li>
          <li><a href="http://shop.github.com">Shop</a></li>
          <li><a href="https://github.com/contact">Contact</a></li>
          <li><a href="http://develop.github.com">API</a></li>
          <li><a href="http://status.github.com">Status</a></li>
        </ul>
        <ul class="sosueme">
          <li class="main">&copy; 2011 <span id="_rrt" title="0.05588s from fe5.rs.github.com">GitHub</span> Inc. All rights reserved.</li>
          <li><a href="/site/terms">Terms of Service</a></li>
          <li><a href="/site/privacy">Privacy</a></li>
          <li><a href="https://github.com/security">Security</a></li>
        </ul>
      </div>
    </div><!-- /#footer -->

    
      
      
        <!-- current locale:  -->
        <div class="locales instapaper_ignore readability-footer">
          <div class="site">

            <ul class="choices clearfix limited-locales">
              <li><span class="current">English</span></li>
              
                  <li><a rel="nofollow" href="?locale=de">Deutsch</a></li>
              
                  <li><a rel="nofollow" href="?locale=fr">Français</a></li>
              
                  <li><a rel="nofollow" href="?locale=ja">日本語</a></li>
              
                  <li><a rel="nofollow" href="?locale=pt-BR">Português (BR)</a></li>
              
                  <li><a rel="nofollow" href="?locale=ru">Русский</a></li>
              
                  <li><a rel="nofollow" href="?locale=zh">中文</a></li>
              
              <li class="all"><a href="#" class="minibutton btn-forward js-all-locales"><span><span class="icon"></span>See all available languages</span></a></li>
            </ul>

            <div class="all-locales clearfix">
              <h3>Your current locale selection: <strong>English</strong>. Choose another?</h3>
              
              
                <ul class="choices">
                  
                      <li><a rel="nofollow" href="?locale=en">English</a></li>
                  
                      <li><a rel="nofollow" href="?locale=af">Afrikaans</a></li>
                  
                      <li><a rel="nofollow" href="?locale=ca">Català</a></li>
                  
                      <li><a rel="nofollow" href="?locale=cs">Čeština</a></li>
                  
                      <li><a rel="nofollow" href="?locale=de">Deutsch</a></li>
                  
                </ul>
              
                <ul class="choices">
                  
                      <li><a rel="nofollow" href="?locale=es">Español</a></li>
                  
                      <li><a rel="nofollow" href="?locale=fr">Français</a></li>
                  
                      <li><a rel="nofollow" href="?locale=hr">Hrvatski</a></li>
                  
                      <li><a rel="nofollow" href="?locale=hu">Magyar</a></li>
                  
                      <li><a rel="nofollow" href="?locale=id">Indonesia</a></li>
                  
                </ul>
              
                <ul class="choices">
                  
                      <li><a rel="nofollow" href="?locale=it">Italiano</a></li>
                  
                      <li><a rel="nofollow" href="?locale=ja">日本語</a></li>
                  
                      <li><a rel="nofollow" href="?locale=nl">Nederlands</a></li>
                  
                      <li><a rel="nofollow" href="?locale=no">Norsk</a></li>
                  
                      <li><a rel="nofollow" href="?locale=pl">Polski</a></li>
                  
                </ul>
              
                <ul class="choices">
                  
                      <li><a rel="nofollow" href="?locale=pt-BR">Português (BR)</a></li>
                  
                      <li><a rel="nofollow" href="?locale=ru">Русский</a></li>
                  
                      <li><a rel="nofollow" href="?locale=sr">Српски</a></li>
                  
                      <li><a rel="nofollow" href="?locale=sv">Svenska</a></li>
                  
                      <li><a rel="nofollow" href="?locale=zh">中文</a></li>
                  
                </ul>
              
            </div>

          </div>
          <div class="fade"></div>
        </div>
      
    

    <script>window._auth_token = "f48685d6b856fb0d6f71b994bff692fa0b8cc92a"</script>
    

<div id="keyboard_shortcuts_pane" class="instapaper_ignore readability-extra" style="display:none">
  <h2>Keyboard Shortcuts <small><a href="#" class="js-see-all-keyboard-shortcuts">(see all)</a></small></h2>

  <div class="columns threecols">
    <div class="column first">
      <h3>Site wide shortcuts</h3>
      <dl class="keyboard-mappings">
        <dt>s</dt>
        <dd>Focus site search</dd>
      </dl>
      <dl class="keyboard-mappings">
        <dt>?</dt>
        <dd>Bring up this help dialog</dd>
      </dl>
    </div><!-- /.column.first -->

    <div class="column middle" style='display:none'>
      <h3>Commit list</h3>
      <dl class="keyboard-mappings">
        <dt>j</dt>
        <dd>Move selected down</dd>
      </dl>
      <dl class="keyboard-mappings">
        <dt>k</dt>
        <dd>Move selected up</dd>
      </dl>
      <dl class="keyboard-mappings">
        <dt>t</dt>
        <dd>Open tree</dd>
      </dl>
      <dl class="keyboard-mappings">
        <dt>p</dt>
        <dd>Open parent</dd>
      </dl>
      <dl class="keyboard-mappings">
        <dt>c <em>or</em> o <em>or</em> enter</dt>
        <dd>Open commit</dd>
      </dl>
    </div><!-- /.column.first -->

    <div class="column last" style='display:none'>
      <h3>Pull request list</h3>
      <dl class="keyboard-mappings">
        <dt>j</dt>
        <dd>Move selected down</dd>
      </dl>
      <dl class="keyboard-mappings">
        <dt>k</dt>
        <dd>Move selected up</dd>
      </dl>
      <dl class="keyboard-mappings">
        <dt>o <em>or</em> enter</dt>
        <dd>Open issue</dd>
      </dl>
    </div><!-- /.columns.last -->

  </div><!-- /.columns.equacols -->

  <div style='display:none'>
    <div class="rule"></div>

    <h3>Issues</h3>

    <div class="columns threecols">
      <div class="column first">
        <dl class="keyboard-mappings">
          <dt>j</dt>
          <dd>Move selected down</dd>
        </dl>
        <dl class="keyboard-mappings">
          <dt>k</dt>
          <dd>Move selected up</dd>
        </dl>
        <dl class="keyboard-mappings">
          <dt>x</dt>
          <dd>Toggle select target</dd>
        </dl>
        <dl class="keyboard-mappings">
          <dt>o <em>or</em> enter</dt>
          <dd>Open issue</dd>
        </dl>
      </div><!-- /.column.first -->
      <div class="column middle">
        <dl class="keyboard-mappings">
          <dt>I</dt>
          <dd>Mark selected as read</dd>
        </dl>
        <dl class="keyboard-mappings">
          <dt>U</dt>
          <dd>Mark selected as unread</dd>
        </dl>
        <dl class="keyboard-mappings">
          <dt>e</dt>
          <dd>Close selected</dd>
        </dl>
        <dl class="keyboard-mappings">
          <dt>y</dt>
          <dd>Remove selected from view</dd>
        </dl>
      </div><!-- /.column.middle -->
      <div class="column last">
        <dl class="keyboard-mappings">
          <dt>c</dt>
          <dd>Create issue</dd>
        </dl>
        <dl class="keyboard-mappings">
          <dt>l</dt>
          <dd>Create label</dd>
        </dl>
        <dl class="keyboard-mappings">
          <dt>i</dt>
          <dd>Back to inbox</dd>
        </dl>
        <dl class="keyboard-mappings">
          <dt>u</dt>
          <dd>Back to issues</dd>
        </dl>
        <dl class="keyboard-mappings">
          <dt>/</dt>
          <dd>Focus issues search</dd>
        </dl>
      </div>
    </div>
  </div>

  <div style='display:none'>
    <div class="rule"></div>

    <h3>Network Graph</h3>
    <div class="columns equacols">
      <div class="column first">
        <dl class="keyboard-mappings">
          <dt><span class="badmono">←</span> <em>or</em> h</dt>
          <dd>Scroll left</dd>
        </dl>
        <dl class="keyboard-mappings">
          <dt><span class="badmono">→</span> <em>or</em> l</dt>
          <dd>Scroll right</dd>
        </dl>
        <dl class="keyboard-mappings">
          <dt><span class="badmono">↑</span> <em>or</em> k</dt>
          <dd>Scroll up</dd>
        </dl>
        <dl class="keyboard-mappings">
          <dt><span class="badmono">↓</span> <em>or</em> j</dt>
          <dd>Scroll down</dd>
        </dl>
        <dl class="keyboard-mappings">
          <dt>t</dt>
          <dd>Toggle visibility of head labels</dd>
        </dl>
      </div><!-- /.column.first -->
      <div class="column last">
        <dl class="keyboard-mappings">
          <dt>shift <span class="badmono">←</span> <em>or</em> shift h</dt>
          <dd>Scroll all the way left</dd>
        </dl>
        <dl class="keyboard-mappings">
          <dt>shift <span class="badmono">→</span> <em>or</em> shift l</dt>
          <dd>Scroll all the way right</dd>
        </dl>
        <dl class="keyboard-mappings">
          <dt>shift <span class="badmono">↑</span> <em>or</em> shift k</dt>
          <dd>Scroll all the way up</dd>
        </dl>
        <dl class="keyboard-mappings">
          <dt>shift <span class="badmono">↓</span> <em>or</em> shift j</dt>
          <dd>Scroll all the way down</dd>
        </dl>
      </div><!-- /.column.last -->
    </div>
  </div>

  <div >
    <div class="rule"></div>

    <h3>Source Code Browsing</h3>
    <div class="columns threecols">
      <div class="column first">
        <dl class="keyboard-mappings">
          <dt>t</dt>
          <dd>Activates the file finder</dd>
        </dl>
        <dl class="keyboard-mappings">
          <dt>l</dt>
          <dd>Jump to line</dd>
        </dl>
      </div>
    </div>
  </div>

</div>
    

    <!--[if IE 8]>
    <script type="text/javascript" charset="utf-8">
      $(document.body).addClass("ie8")
    </script>
    <![endif]-->

    <!--[if IE 7]>
    <script type="text/javascript" charset="utf-8">
      $(document.body).addClass("ie7")
    </script>
    <![endif]-->

    
    <script type='text/javascript'></script>
    
  </body>
</html>

