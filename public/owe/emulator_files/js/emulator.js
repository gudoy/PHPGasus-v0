/**
 *
 * @fileoverview
 *
 * <p>This file handles the 'emulation' of the widget. This includes:</p>
 *
 * <ul>
 *   <li>Creation of the iFrame that contains the widget
 *   <li>Reading of config.xml to get name/size etc.
 *   <li>Windowing environment (window.moveTo etc.)
 *   <li>Handling of links (window.openURL(), anchors, forms)
 *   <li>Notifications
 *   <li>etc.
 * </ul>
 * 
 * loadDevice(string: media, int: width, int: height, int:storage)
 * - this function will prepare the device and show the screen
 * - at the end it will call this.loadWidget()
 *
 * loadWidget()
 * - prepares config.xml for reading and waits for the onload event to call this.analyseWidget()
 *
 * analyseWidget()
 * - reads the config.xml and alerts the user if it is invalid
 * - sets the initial coordinates of the widget based on the width/height defined
 * - calls this.runWidget()
 *
 * runWidget()
 * - sets some more properties
 * - creates the iframe for the widget to run in
 *
 * begin()
 * - gets called by the index.html inside the iframe (while it is loading)
 * - creates all the wrappers and environment stuff for the index.html
 * 
 */



var emulator = new function()
{  
  var iframe = null; // iframe element
  var frameWindow = null; // window object in the iframe
  
  var self = this;
  
  var hideNotification = null; // function
  var showNotification = null; // function
  
  // initialise element references
  this.init = function()
  {
    g('info-program').addEventListener('click', function()
    {
      g('screen').removeClass('widgetMinimised');
    }, false);
    
    prepareNotifier();
  };
  
  function prepareNotifier()
  {
    var parent = document.getElementById('notifier');
    var text = document.getElementById('notifier-text');
    var close = document.getElementById('notifier-close');
    
    var HEIGHT = 28;
    
    var count = 0;
    var interval = 0;
    var reservedCallback = null;
    
    function nextFrame()
    {
      count++;
      
      var off = count/20;
      off = off < 1 ? off : off > 7 ? 8-off : 1;
      
      parent.style.marginBottom = Math.round((off-1)*HEIGHT) + 'px';
      
      if (count == 160)
      {
        hideNotification();
      }
    }      
    
    hideNotification = function()
    {
      reservedCallback = null;
      
      clearInterval(interval);
      interval = 0;
      
      text.innerText = '';
      close.disabled = true;
      parent.style.display = '';
    };
    
    showNotification = function(str, callback)
    {
      reservedCallback = callback;
      
      parent.style.display = 'block';
      
      text.innerText = str+'';
      close.disabled = false;
      
      count = 0;
      if (!interval)
      {
        interval = setInterval(nextFrame, 30);
        nextFrame();
      }
    };
    
    close.onclick = hideNotification;
    text.onclick = function()
    {
      /* we want to call hide() before
      we do the callback in case an error
      occurs in the callback (We don't want
      the notifier to still be there, but
      then we need to store a reference to
      the callback because hide will
      destroy it */
      var callback = reservedCallback;
      hideNotification();
      if (callback)
      {
        callback();
      }
    }
    
  };
  
  this.clearPreferences = function()
  {
    Control.device.widget.preferences = {};
    Control.device.widget.storage = 0;
    Control.savePreferences();
  }
  
  this.loadDevice = function(device, autoload)
  {
    var title = device.title;
    var mediaType = device.media;
    var width = device.screen[0];
    var height = device.screen[1];
    var storage = device.storage;
    var dockX = device.dock && device.dock[0] || 0;
    var dockY = device.dock && device.dock[1] || 0;
    var chrome = device.chrome;
    var plugins = device.plugins;
    
    g('dockBlocker').style.display = 'none';
    g('device').style.display = 'block';
  
    var d = Control.device; // shorthand
  
    d.settings = {
      title: title,
      loaded: true,
      storage: storage,
      chrome: chrome || [0,0,0,0],
      dockX: dockX,
      dockY: dockY,
      useragent: device.useragent,
      plugins: plugins
    };
    
    // media object
    d.media = new Media();
    d.media.type = mediaType;
    
    d.media.features['device-width'] = width;
    d.media.features['device-height'] = height;
    d.media.features['device-aspect-ratio'] = width/height;
    /* width, height and -o-widget-mode are set after analysing confix.xml */
    
    // directly exposed to the iframe's window:
    d.screen = {
      width: width,
      height: height,
      availWidth: width - d.settings.chrome[1] - d.settings.chrome[3],
      availHeight: height - d.settings.chrome[0] - d.settings.chrome[2]
    };
    
    d.plugins = plugins;
    
    d.widget.opened = false;
    d.widget.loaded = false;
    d.widget.began = false;
    
    window.scrollbars.fix(true); // true causes full scrollbar reset
    
    ui.updateStatus();
    
    ui.resizeWindow();
  
    if (autoload) this.loadWidget(); // autoload
  };
  
  // loads the config.xml
  this.loadWidget = function(path)
  {
    Control.device.widget.path = path;
    g('desktop').removeClass('error');
    g('dockBlocker').style.display = 'none';
    g('screen-tools-dock').removeClass('down');
    g('screen').removeClass('widgetMinimised');
    g('frameWrapper').innerHTML = '';
    loadXML('widgets/'+path+'/config.xml', analyseWidget)
  };
  
  function analyseWidget(xml)
  {

    var widget = null;
    var width = null;
    var height = null;
    
    // any changes to the config.xml since last read will be shown in the manager
    manager.updateWidget(Control.device.widget.path, xml);
    
    try {
      widget = xml.getElementsByTagName('widget')[0];
    }
    catch(e){
      ui.failWidget('config');
      return;
    }
    
    if (!widget)
    {
      ui.failWidget('config');
      return;
    }
    
    var dockable = (widget.getAttribute('dockable')||'').toLowerCase()
    
    // fixme: dockable="yes" is not allowed per spec. Added as workaround until bug is fixed (runeh)
    Control.device.widget.dockable = Control.device.settings.dockX &&
                                     (dockable=='1' ||
                                      dockable=='yes' ||
                                      dockable=='true' ||
                                      dockable=='dockable');
    
    if (Control.device.widget.dockable)
    {
      g('screen').addClass('dockable');
    }
    else
    {
      g('screen').removeClass('dockable');
    }
    
    function stripInt(str)
    {
      return parseInt(str.replace(/^\s+0*|\s+$/g,''));
    }
    
    try { width  = stripInt(widget.getElementsByTagName('width' )[0].firstChild.nodeValue); } catch ( e ) { }
    try { height = stripInt(widget.getElementsByTagName('height')[0].firstChild.nodeValue); } catch ( e ) { }
    
    // default values
    if ( !width  || width  <= 0) width  = 200;
    if ( !height || height <= 0) height = 150;
    
    // set widgetfile
    var widgetfile = "";
    
    try {
      widgetfile = widget.getElementsByTagName('widgetfile')[0].firstChild.nodeValue;
    }
    catch(e){}
    
    Control.device.widget.widgetfile = widgetfile || 'index.html';
    
    // set icon
    
    try {
      ui.setIcon(widget.getElementsByTagName('icon')[0].firstChild.nodeValue);
    }
    catch (e)
    {
      ui.setIcon(null)
    }
    
    var d = Control.device;
    
    d.widget.configXML = widget;
    
    d.widget.width  = width;
    d.widget.height = height;
    
    d.widget.top  = Math.max( 0, Math.round( ( d.screen.availHeight - d.widget.height ) / 2) );
    d.widget.left = Math.max( 0, Math.round( ( d.screen.availWidth  - d.widget.width  ) / 2) );
    
    d.media.features['width'] = width;
    d.media.features['height'] = width;
    d.media.features['-o-widget-mode'] = 'widget';
    
    d.widget.opened = true;
    d.widget.loaded = false;
    
    self.runWidget();
    
  };
    
  this.runWidget = function()
  {
    
    g('desktop').style.display = '';
    g('screen-content').style.display = 'block';
    g('info-program').getElementsByTagName('span')[0].innerHTML = '';
    g('info-program').style.display = 'block';
    g('screen-tools').style.display = 'block';
    
    var d = Control.device;
    
    d.widget.scrollTop = 0;
    d.widget.scrollLeft = 0;
    
    d.widget.storage = 0;
    for (var i in d.widget.preferences)
    {
      if (typeof d.widget.preferences[i] == 'string')
      {
        d.widget.storage += (i+'').length + (d.widget.preferences[i]+'').length;
      }
    }
    
    d.widget.mode = 'widget';
    
    g('frameWrapper').innerHTML = '\
      <iframe id="iframe" scroll="no" style="visibility:hidden;top:'+d.widget.top+'px;left:'+d.widget.left+'px;" src="widgets/'+Control.device.widget.path+'/'+Control.device.widget.widgetfile+'" width="'+Control.device.widget.width+'" height="'+Control.device.widget.height+'">\
      <\/iframe>\
    ';
    
    if (!g('iframe').document)
    {
      ui.failWidget('index');
      return;
    }
    
    g('iframe').addEventListener('load', function()
    {
      if (!d.widget.began && !d.widget.loaded)
      {
        ui.failWidget('script');
      }
    }, false);
    
    window.scrollbars.fix(true); // true causes full scrollbar reset
    
    ui.updateStatus();
  }
  
  var interval = 0;
  /* this should only be called by Control */
  this.closeWidget = function()
  {
    g('device').removeClass('running');
    
    // reset the chrome elements
    // the only way to be sure they are completely cleared of styles+properties
    // is to remove them and add new divs
    
    var chromeNames = ['top','right','bottom','left'];
    var parent = g('screen-chrome');
    var ele = null;
    for (var i=0; i<4; i++)
    {
      parent.removeChild(g('screen-chrome-'+chromeNames[i]));
      ele = document.createElement('div');
      ele.setAttribute('id','screen-chrome-'+chromeNames[i]);
      parent.appendChild(ele);
      
    }
    
    g('plugin-ui').innerHTML = '';
    
    if (interval)
    {
      clearInterval(interval);
      interval = 0;
    }
    
    hideNotification();
    
    self.dragHandler.disable();
    
    g('dockBlocker').style.display = 'none';
    
    emulator.changeWidgetMode = null;
  }
  
  this.dragHandler = (function()
  {
    var startTop = 0;
    var startLeft = 0;
    var x0 = 0;
    var y0 = 0;
    var newX = 0;
    var newY = 0;
    
    function mousedown(evt)
    {
      startX = Control.device.widget.left;
      startY = Control.device.widget.top;
      
      x0 = evt.clientX;
      y0 = evt.clientY;
      
      g('screen-blocker').style.display = 'block';
      
      document.addEventListener('mousemove', mousemove, false);
      document.addEventListener('mouseup', mouseup, false);
      
      evt.preventDefault();
      
    }
    
    function mousemove(evt)
    {
      newX = startX + evt.clientX - x0;
      newY = startY + evt.clientY - y0;
      if (!timeout)
      {
        timeout = setTimeout(applyMove, 1);
      }
    }
    
    var timeout = 0;
    function applyMove()
    {
      timeout = 0;
      self.widgetMoveTo(newX, newY);
    }
    
    function mouseup()
    {
      document.removeEventListener('mousemove', mousemove, false);
      document.removeEventListener('mouseup', mouseup, false);
    }
    
    function keydown(evt)
    {
      if (evt.keyCode == 18)
      {
        g('screen-blocker').style.display = 'block';
        frameWrapper.addClass('draggable');
        
        document.addEventListener('mousedown', mousedown, false);

        document.   addEventListener('keyup', keyup, false);
        frameWindow.addEventListener('keyup', keyup, false);
      }
    }
    function keyup(evt)
    {
      if (evt.keyCode == 18)
      {
        g('screen-blocker').style.display = 'none';
        frameWrapper.removeClass('draggable');
        
        document.removeEventListener('mousedown', mousedown, false);
        
        document.   removeEventListener('keyup', keyup, false);
        frameWindow.removeEventListener('keyup', keyup, false);
      }
    }
    
    return {
      enable: function()
      {
        document.   addEventListener('keydown', keydown, false);
        frameWindow.addEventListener('keydown', keydown, false);
      },
      disable: function()
      {
        if (timeout)
        {
          clearTimeout(timeout);
        }
        try {
          document.removeEventListener('keydown', keydown, false);
          frameWindow && frameWindow.removeEventListener('keydown', keydown, false);
        }
        catch(ignore){}
      }
    }
  })();
  
  // will be called by window.moveTo and window.moveBy
  this.widgetMoveTo = function(x, y)
  {
    
    if (Control.device.widget.mode == 'docked')
    {
      return; // docked widgets cannot move
    }
    
    x = parseInt(x);
    y = parseInt(y);
    if ( typeof x != 'number' || isNaN(x) || !isFinite(x) ) return;
    if ( typeof y != 'number' || isNaN(y) || !isFinite(y) ) return;
    
    x = Math.max(0, Math.min( x, Control.device.screen.availWidth - Control.device.widget.width) );
    y = Math.max(0, Math.min( y, Control.device.screen.availHeight - Control.device.widget.height) );
    
    // store the values
    Control.device.widget.left = x;
    Control.device.widget.top = y;
    // apply the values
    iframe.style.left = x + 'px';
    iframe.style.top = y + 'px';
    // broadcast new values to the widget
    frameWindow.screenLeft = x;
    frameWindow.screenTop = y;
    // output to the developer
    ui.updateStatus();
  };
  
  /**
  * Creates a question for the user
  *
  * @param title {String} The title of the question
  * @param text {String} The body message
  * @param checkbox {String} If set, then a label to apply to a checkbox question
  * @param callback {String} The callback, gets called with 'true' or 'false'
  */
  
  this.prompt = (function()
  {
    var stack = [];
    
    var markup = [
      'form',
        'onsubmit', ok,
        ['div',
          ['h4',
           ''
          ],
          ['p',
           ''
          ],
        ],
        ['fieldset',
         ['input',
          'type','button',
          'value','Cancel',
          'onclick',cancel
         ],
         ['input',
          'type','submit',
          'value','Ok'
         ]
        ]
      ];
    
    function ok()
    {
      done(true);
      return false;
    }
    function cancel()
    {
      done(false);
    }
    function onKeypress(evt)
    {
      if (evt.keyCode == 27) // escape key
      {
        done(false);
      }
    }
    function done(ok)
    {
      var checkbox = g('promptCheckbox');
      checkbox = checkbox && checkbox.checked;
      
      document.removeEventListener('keypress', onKeypress, false);
      
      ui.alertClose();
      
      var callback = stack.pop().callback;
      
      if (callback) callback(ok, checkbox);
      
      if (stack.length)
      {
        showNextPrompt();
      }
    }
    
    function showNextPrompt()
    {
      document.addEventListener('keypress', onKeypress, false);
      
      markup[3][1][1] = stack[stack.length-1].title;
      markup[3][2][1] = stack[stack.length-1].text;
      if (stack[stack.length-1].checkbox)
      {
        markup[3][3] = ['label',
          ['input','type','checkbox','id','promptCheckbox'],
          stack[stack.length-1].checkbox
        ];
      }
      else
      {
        markup[3].length = 3;
      }
      ui.alert(markup, true)
    }
    
    return function(title, text, checkbox, callback)
    {
      stack.push({
        title: title,
        text: text,
        checkbox: checkbox,
        callback: callback
	    });
      
      if (stack.length == 1)
      {
        showNextPrompt();
      }
    };
  })();
  
  /**
   * Checks which JS Plugins are enabled in a widget's
   * configuration file.
   *
   * @param configXML {Document} The XML Document of the config.xml
   * @returns {Object} An object with string properties deliniating the enabled plugins
   */
  
  this.detectJSPlugins = function(configXML)
  {
    var security = configXML.getElementsByTagName('security')[0];
    var jsplugins = {};
    var ele = null;
    var str1 = '';
    var str2 = '';
    
    // check for "widget/@jsplugins" then "widget/security/content/@jsplugins"
    // one of these must be set for JS Plugins to be enabled
    try
    {
      str1 = configXML.getAttribute('jsplugins').toLowerCase();
    }
    catch(ignore){}
    
    try
    {
      str2 = security.getElementsByTagName('content')[0].
                      getAttribute('jsplugins').toLowerCase();
    }
    catch(ignore){}
    
    if ((str1 == 'yes'       || str2 == 'yes'       ||
         str1 == '1'         || str2 == '1'         ||
         str1 == 'true'      || str2 == 'true'      ||
         str1 == 'jsplugins' || str2 == 'jsplugins') && str2 != 'no')
    {
      // find the individual jsplugins that are allowed
      ele = security.getElementsByTagName('jsplugin');
      
      for (i=0; i<ele.length; i++)
      {
        if (ele[i].parentNode == security) // must be direct child
        {
          jsplugins[ele[i].getAttribute('src')] = true;
        }
      }
    }
    
    return jsplugins;
  };
  
  this.alert = (function()
  {
    var stack = [];
    
    var markup = [
      'form',
        'onsubmit', done,
        ['div',
          ['h4',
           ''
          ],
          ['p',
           ''
          ],
        ],
        ['fieldset',
         ['input',
          'type','submit',
          'value','Ok'
         ]
        ]
      ];
    
    function done()
    {      
      ui.alertClose();
      
      var callback = stack.pop().callback;
      
      if (callback) callback();
    }
    
    function showNextAlert()
    {
      markup[3][1][1] = stack[stack.length-1].title;
      markup[3][2][1] = stack[stack.length-1].text;
      
      ui.alert(markup, true)
    }
    return function(title, text, callback)
    {
      stack.push({
        title: title,
        text: text,
        callback: callback
	    });
      
      if (stack.length == 1)
      {
        showNextAlert();
      }
    };
  })();
  
  this.begin = function(frameWin) // creates the wrappers and such
  {
    frameWindow = frameWin; // expose in larger scope (not globally though)
    
    Control.device.screen.availWidth = Control.device.screen.width;
    Control.device.screen.availHeight = Control.device.screen.height;
    
    // adjust for chrome
    {
      Control.device.screen.availWidth -= Control.device.settings.chrome[1] + Control.device.settings.chrome[3];
      Control.device.screen.availHeight -= Control.device.settings.chrome[0] + Control.device.settings.chrome[2];
      
      g('screen-chrome-top').style.height    = Control.device.settings.chrome[0] + 'px';
      g('screen-chrome-right').style.width   = Control.device.settings.chrome[1] + 'px';
      g('screen-chrome-bottom').style.height = Control.device.settings.chrome[2] + 'px';
      g('screen-chrome-left').style.width    = Control.device.settings.chrome[3] + 'px';
      
      // left and right toolbars need correct positioning
      g('screen-chrome-right').style.top = //
      g('screen-chrome-left') .style.top = Control.device.settings.chrome[0] + 'px';
      g('screen-chrome-right').style.bottom = //
      g('screen-chrome-left') .style.bottom = Control.device.settings.chrome[2] + 'px';
    }
    
    Control.device.widget.began = true;
    
    if (!frameWindow)
    {
      opera.postError('Send a reference to the window object as the first argument to parent.emulator.begin.');
      return;
    }
    emulator.window = frameWindow;
    
    emulator.window.addEventListener('load',function()
    {
       Control.device.widget.loaded = true;
    }, false);
    
    // add css to make it undraggable, and remove scrollbars
    frameWindow.document.write('<style type="text/css">'+
      'html{position:absolute;top:0;left:0;right:0;bottom:0;-apple-dashboard-region:dashboard-region(control rectangle);}'+
      'html,body{overflow:hidden !important;}'+
      '</style>');
    
    iframe = g('iframe');
    
    frameWindow.addEventListener('DOMContentLoaded',function()
    {
      // we hide the iframe while it is loading
      iframe.style.visibility = 'visible';
    }, true);
    
    // set user agent
    if (Control.device.settings.useragent)
    {
      frameWindow.navigator.userAgent = Control.device.settings.useragent;
    }
    
    var e_infoProgramTitle = g('info-program').getElementsByTagName('span')[0];
    var docTitle = '';

    function looper()
    {
      if (!frameWindow || !frameWindow.document)
      {
        clearInterval(interval);
        interval = 0;
        return;
      }
      
      var str = frameWindow.document.title || '';
      if (str != docTitle)
      {
        docTitle = str;
        e_infoProgramTitle.innerText = str;
      }
      
      Control.device.media.matchDocument(frameWindow.document);
    }
    
    if (interval)
    {
      clearInterval(interval);
    }
    interval = setInterval(looper, 1000);
    setTimeout(looper,1); // fast response
    
    self.setScreenSize = function(width, height)
    {
      ui.setScreenSize(width, height);
      
      var d = Control.device;
      
      d.screen.width = width;
      d.screen.height = height;
      
      if (d.widget.mode != 'docked') // docked mode is oblivious to screen changes
      {
        d.screen.availWidth = width - d.settings.chrome[1] - d.settings.chrome[3];
        d.screen.availHeight = height - d.settings.chrome[0] - d.settings.chrome[2];
      }
      
      d.media.features['device-width'] = width;
      d.media.features['device-height'] = height;
      d.media.features['device-aspect-ratio'] = width/height;
      
      self.widgetMoveTo(d.widget.left, d.widget.top);
      
      looper();
      
      window.scrollbars.fix(true); // true causes full scrollbar reset
      
      ui.updateStatus();
      
      ui.resizeWindow();
      
      if (d.widget.began && d.widget.mode != 'docked') // docked mode is oblivious to screen changes
      {
        frameWindow.widget.dispatchEvent({
          type: 'resolution',
          width: d.screen.availWidth,
          height: d.screen.availHeight
        });
      }
      
    };
    
    self.rotate = function()
    {
      self.setScreenSize(Control.device.screen.height, Control.device.screen.width);
    };
    
    var defaultWidgetMode = 'widget';
    
    /**
     * Change the widget mode
     *
     * <p>This function will change the mode
     * the widget is running in, currently
     * supports 'widget' and 'docked' modes.</p>
     *
     * @param {string} mode The new mode to switch to
     */
    
    self.toggleDocked = function()
    {
      changeWidgetMode( Control.device.widget.mode == 'docked' ? defaultWidgetMode : 'docked');
    };
    
    self.setDefaultWidgetMode = function(mode)
    {
      var d = Control.device;
      if (d.widget.mode == defaultWidgetMode)
      {
        d.widget.mode = mode;
        frameWindow.widget.widgetMode = mode;
      }
      defaultWidgetMode = mode;
    };
    
    /**
     * Changes widgetMode
     *
     * <p>This will change the mode of the widget
     * (exmaple from 'widget' to 'docked'). It will
     * make the visible changes (by adjusting the iFrame),
     * set the appropriate values (screen.availWidth etc.)
     * then trigger the events on the widget object.</p>
     *
     * @param {string} mode The new mode to change to
     */
    
    function changeWidgetMode( mode )
    {
      var d = Control.device; // alias
      
      if (mode!=defaultWidgetMode && mode!='docked')
      {
        opera.postError('Unsupported mode attempted: ' + mode)
        return;
      }
      
      if (d.widget.mode == mode)
      {
        return; // nothing to change
      }
      if ( mode == 'docked' && !d.widget.dockable)
      {
        return; // widget does not implement 'docked'
      }
      
      d.widget.mode = mode;
      
      if (mode == 'docked')
      {
        d.tempData = { // store old values for when we go back
          top: d.widget.top,
          left: d.widget.left,
          width: d.widget.width,
          height: d.widget.height
        };
        d.widget.width = d.settings.dockX;
        d.widget.height = d.settings.dockY;
        
        // position of widget in dock is done by css file using "!important"
        d.widget.top = 0;
        d.widget.left = 0;
        
        d.screen.availWidth = d.settings.dockX;
        d.screen.availHeight = d.settings.dockY;
        
        g('dockBlocker').style.width = d.widget.width+'px';
        g('dockBlocker').style.height = d.widget.height+'px';
        g('dockBlocker').style.top = d.settings.chrome[0]+'px';
        g('dockBlocker').style.right = d.settings.chrome[1]+'px';
        
        g('device').addClass('docked');
        g('screen-tools-dock').addClass('down');
        
      }
      else
      {
        g('dockBlocker').style.display = 'none';
        
        d.widget.width = d.tempData.width;
        d.widget.height = d.tempData.height;
        d.widget.top = d.tempData.top;
        d.widget.left = d.tempData.left;
        d.screen.availWidth = d.screen.width-d.settings.chrome[1]-d.settings.chrome[3];
        d.screen.availHeight = d.screen.height-d.settings.chrome[0]-d.settings.chrome[2];
        d.tempData = null; // not needed any more
        
        g('device').removeClass('docked');
        g('screen-tools-dock').removeClass('down');
      }
      
      iframe.style.visibility = 'hidden';
      g('dockBlocker').style.display = 'none';
      showWidgetWait();
      
      /* set actual dom values */
      iframe.width = d.widget.width;
      iframe.height = d.widget.height;
      iframe.style.top = d.widget.top+'px';
      iframe.style.left = d.widget.left+'px';
      
      /* broadcast values to the widget */
      frameWindow.screenLeft = d.widget.left;
      frameWindow.screenTop = d.widget.top;
      frameWindow.innerWidth = d.widget.width;
      frameWindow.innerHeight = d.widget.height;
      
      d.media.features['-o-widget-mode'] = mode;
      d.media.features['width'] = d.widget.width;
      d.media.features['height'] = d.widget.height;
      
      Control.device.media.matchDocument(frameWindow.document)
      
      frameWindow.widget.widgetMode = mode;
      
      frameWindow.widget.dispatchEvent({
        type: 'widgetmodechange',
        widgetMode: mode
      });
      frameWindow.widget.dispatchEvent({
        type: 'resolution',
        width: d.screen.availWidth,
        height: d.screen.availHeight
      });
      
      scrollbars.fix();
      ui.updateStatus();
      
    };
    
    /**
     * Shows the widget after 1ms has passed.
     * 
     * Used by changeWidgetMode. Necessary to avoid
     * flickering content when switching to 'dock' then
     * 'widget' mode quicky.
     */
    
    var showWidgetWait = (function()
    {
      var timeout = 0;
      
      function show()
      {
        iframe.style.visibility = 'visible';
        g('dockBlocker').style.display = Control.device.widget.mode == 'docked' ? 'block' : 'none';
        timeout = 0;
      }
      
      return function()
      {
        if (!timeout)
        {
          timeout = setTimeout(show,1);
        }
      }
    })();
    
    // change the screen size and properties
    frameWindow.screen = Control.device.screen;
    frameWindow.screenLeft = Control.device.widget.left;
    frameWindow.screenTop = Control.device.widget.top;
    frameWindow.innerWidth = Control.device.widget.width;
    frameWindow.innerHeight = Control.device.widget.height;
    
    
    // remove reference to the parent window
    frameWindow.parent = frameWindow;
    frameWindow.top = frameWindow;
    
    // over-ride the close function
    frameWindow.close = function()
    {
      Control.closeWidget();
    };
    
    frameWindow.document.addEventListener('click',function(e)
    {
      if (Control.device.widget.mode == 'docked')
      {
        self.toggleDocked(); // clicking docked widget should 'undock'
      }
      else
      {
        if (document.onclick) document.onclick(); /* not sure why this line is here, pending deletion... */
      }
    }, false);
    
    g('dockBlocker').onclick = function()
    {
      if (Control.device.widget.mode == 'docked')
      {
        self.toggleDocked();
      }
    }
    
    frameWindow.document.documentElement.addEventListener('DOMNodeInserted',function(e)
    {
        Control.device.media.matchDocument(frameWindow.document);
    }, false);
    
    // will be called by window.resizeTo and resizeBy
    function resizeTo(x, y)
    {
      if (Control.device.widget.mode == 'docked')
      {
        return; // docked widgets cannot resize
      }
      
      x = parseInt(x);
      y = parseInt(y);
      if ( typeof x != 'number' || isNaN(x) || !isFinite(x) ) return;
      if ( typeof y != 'number' || isNaN(y) || !isFinite(y) ) return;
      
      x = x<1 ? 1 : x>10000 ? 10000 : x;
      y = y<1 ? 1 : y>10000 ? 10000 : y;
      
      var d = Control.device; // shortcut
      // store the values
      d.widget.width = x;
      d.widget.height = y;
      // apply the values
      iframe.width = x;
      iframe.height = y;
      // broadcast new values to the widget
      frameWindow.innerWidth = x;
      frameWindow.innerHeight = y;
      // store values on media object
      Control.device.media.features['width'] = x;
      Control.device.media.features['height'] = y;
    
      if (
        d.widget.left > 0 && x + d.widget.left > d.screen.availWidth
        ||
        d.widget.top > 0 && y + d.widget.top > d.screen.availHeight
      )
      {
        self.widgetMoveTo( Math.min(d.widget.left, d.screen.width-x), Math.min(d.widget.top, d.screen.height-y) );
      }
      // output to the developer
      ui.updateStatus();
      scrollbars.fix();
      
      Control.device.media.matchDocument(frameWindow.document);
    }
    
    frameWindow.resizeTo = function(valueWidth, valueHeight)
    {
      resizeTo(valueWidth, valueHeight);
    };
    
    frameWindow.resizeBy = function(deltaWidth, deltaHeight)
    {
      frameWindow.resizeTo(Control.device.widget.width + deltaWidth, Control.device.widget.height + deltaHeight);
    };
    
    frameWindow.moveTo = function(valueLeft, valueTop)
    {
      self.widgetMoveTo(valueLeft, valueTop);
    };
    
    frameWindow.moveBy = function(deltaLeft, deltaTop)
    {    
      frameWindow.moveTo(Control.device.widget.top + deltaTop, Control.device.widget.left + deltaLeft);
    };
    
    /*
        Stop anchor tags causing the iframe to change location
    */
    
    frameWindow.document.addEventListener('click', function(evt)
    {
      // a link must open in a new window, not in the iframe
      var ele = evt.target;
      var href = '';
      
      while (ele)
      {
        // we have to check the tagName instead of HTMLAnchorElement
        // because that object's prototype is different between the
        // parent and the iframe
        if (ele && 'a' == (ele.tagName||'').toLowerCase()
            && !ele.getAttribute('target')
            && (href = ele.getAttribute('href'))
            && href.indexOf('#')
            && href.indexOf('javascript:'))
        {
          ele.setAttribute('target', '_blank');
        }
        ele = ele.parentNode;
      }
    }, false);
    
    /*
        Stop form submitions from reloading the iframe
    */
    
    frameWindow.document.addEventListener('submit',function(evt)
    {
        try {           
            /*
                evt.target.target is the target attribute of the form.
                If it is present we should not abort the form submission
                as it will naturally open in a new window or an iframe
                
                If the form has a reserved target*, and this target leads to
                intrisically replacing the topmost document in the widget,
                in effect replacing the widget, submitting the form should
                fail silently
                
                * Opera treats reserved targets as case-insensitive
                http://www.w3.org/TR/html401/types.html#type-frame-target
            */
            
            var targetString = (evt.target.target||'')+'' // ensure it is a string
            var targetStringLC = targetString.toLowerCase();
            
            if (!targetString)
            {
              evt.preventDefault();
            }
            else
            {
              if (
                  targetStringLC == '_self' ||
                  targetStringLC == '_top' ||
                  targetStringLC == '_parent')
              {
                opera.postError('A form must must not use target="'+targetString+'".');
                evt.preventDefault();
              }
              else
              {
                if (!frameWindow.frames[targetString] &&
                    ((evt.target.method+'')+'').toLowerCase() == 'post')
                {
                  opera.postError('A form must not POST to a new window.');
                  evt.preventDefault();
                }
              }
            }
        }
        catch(ignore){}
    }, false)
    
    /*
      For the preferences, we add an underscore before each key so that
      they do not conflict with our own preferences
    */
    
    var eventListeners = {};
    
    frameWindow.widget = {
      
      /*
        Three functions that follow allow events
        to be added to the widget object.
      */
      widgetMode: 'widget',
      addEventListener: function(evt, funct)
      {
	evt = evt.toLowerCase();
        if (!eventListeners.hasOwnProperty(evt))
        {
          eventListeners[evt] = [];
        }
        eventListeners[evt].push(funct);
      },
      removeEventListener: function(evt, funct)
      {
    	evt = ((evt||'')+'').toLowerCase();
        if (!eventListeners.hasOwnProperty(evt)) { return; }
        var arr = eventListeners[evt];
        for (var i=0; i<arr.length; i++)
        {
          if (arr[i] == funct)
          {
            arr.splice(i,1);
            i--;
          }
        }
      },
      dispatchEvent: function(event)
      {
        var type = ((event.type||'')+'').toLowerCase();
        if (!eventListeners.hasOwnProperty(type)){return;}
        var arr = eventListeners[type];
        
        for (var i=0; i<arr.length; i++)
        {
          arr[i].call(frameWindow.widget, event);
        }
      },
      
      preferenceForKey: function(key)
      {
        return Control.device.widget.preferences[key] || '';
      },
      setPreferenceForKey: function(pref, key)
      {
        pref = '' + pref, key = '' + key; // make them strings
        var oldPref = Control.device.widget.preferences[key];
        var storage;
        if (pref)
        {
          storage = Control.device.widget.storage + (oldPref ? pref.length - oldPref.length : pref.length + key.length);
          if (storage > Control.device.settings.storage)
          {
            throw new Error('Widget preferences have exceeded the maximum volume.');
          }
          Control.device.widget.storage += oldPref ? pref.length - oldPref.length : pref.length + key.length;
          Control.device.widget.preferences[key] = pref.toString();
        }
        else
        {
          delete Control.device.widget.preferences[key];
        }
        ui.updateStatus();
        Control.savePreferences();
      },
      openURL: function()
      {
        widget.openURL.apply(widget, arguments);
      },
      show : function()
      {
        g('screen').removeClass('widgetMinimised');
      },
      hide : function()
      {
        g('screen').addClass('widgetMinimised');
      },
      showNotification : showNotification,
      
      getAttention : (function()
      {
        var count = 0;
        var MAX = 12;
        var interval = 0;

        function flash()
        {
          count++;
          if (count == MAX)
          {
            clearInterval(interval);
            interval = 0;
            g('info-program').removeClass('flashing');
          }
          else
          {
            g('info-program').toggleClass('flashing')
          }
        }
        
        return function()
        {
          count = 0;
          if (!interval)
          {
            interval = setInterval(flash, 300);
          }
        };
      })()
    };
    
    self.dragHandler.enable();
    scrollbars.fix(false, frameWindow); // this allows us to add scroll events to the window
    
    g('device').addClass('running');
    g('plugin-ui').innerHTML = '';
    Control.startPlugins(frameWindow);
    
    ui.resizeWindow();
  }
  
  
};