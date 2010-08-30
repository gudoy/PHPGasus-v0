var ui = (function()
{
  if (!window.widget) { return null; }
  
  var self = null; // set as the return value
  
  var knownWidth;
  var knownHeight;
  
  var settings = widget.preferenceForKey('ui-settings') || {
    showInfo: false,
    showPanel: false
  }
  settings.save = function()
  {
    widget.setPreferenceForKey(this, 'ui-settings');
  }
  
  function applyOutline()
  {
    var frameWrapper = g('frameWrapper');
    if (frameWrapper)
    {
      if (Control.config.showOutline)
      {
        frameWrapper.addClass('showOutline');
      }
      else
      {
        frameWrapper.removeClass('showOutline');
      }
    }
  }
  
  
  /*
    If the emulated widget doesnt have the special script tag then any
    resizewindow it calls will effect the real window size, we don't want this
    to happen so we make sure it get's fixed when it is changed
  */
  var screenInterval = setInterval(function(){
    if (window.innerWidth != knownWidth || window.innerHeight != knownHeight)
    {
        window.resizeTo(knownWidth, knownHeight);
        self.failWidget('script');
    }
  },500);
  
  /**
   * Selector for device button
   *
   * @returns Element A reference to the dom element
   */
  
  var updateTitle = (function()
  {
    var e_container = null;
    var e_button = null;
    var e_dropdown = null;
    
    function init()
    {
      e_button = g('screen-title-button');
      e_dropdown = g('screen-title-dropdown');
      e_button.addEventListener('click', function(evt)
      {
        g('screen-title').toggleClass('expanded');
        if (g('screen-title').hasClass('expanded'))
        {
          document.addEventListener('click', closeDropdown, true);
          document.addEventListener('mousedown', closeDropdown, true);
        }
        else
        {
          document.removeEventListener('click', closeDropdown, true);
          document.removeEventListener('mousedown', closeDropdown, true);
        }
      }, false);
      e_dropdown.addEventListener('click', function(evt)
      {
        if (evt.target.tagName.toLowerCase()!='button') { return; }
        
        var count = evt.target.getAttribute('_count');
        if (count)
        {
          load(count);
        }
      }, false);
      g('screen-title-up').addEventListener('click', function()
      {
        var n = Control.getIndex()-1;
        if (n>=0)
        {
          load(n);
        }
      }, false);
      g('screen-title-down').addEventListener('click', function()
      {
        var n = Control.getIndex()+1;
        if (n < Control.devices.length)
        {
          load(n);
          Control.loadDevice(Control.devices[n]);
        }
      }, false);
      
      function load(n)
      {
        g('screen-title').removeClass('expanded');
        e_button.innerText = Control.devices[n].title;
        Control.loadDevice(Control.devices[n]);
      }
    }
    
    function closeDropdown(evt)
    {
      if (!g('screen-title').hasChild(evt.target))
      {
        g('screen-title').removeClass('expanded');
        document.removeEventListener('click', closeDropdown, true);
        document.removeEventListener('mousedown', closeDropdown, true);
      }
    }
    
    return function()
    {
      if (!e_button) { init(); }
      
      var frag = document.createDocumentFragment();
      var ele = null;
      
      for (var i=0; i<Control.devices.length; i++)
      {
        ele = document.createElement('button');
        ele.setAttribute('_count', i);
        ele.innerText = Control.devices[i].title;
        frag.appendChild(ele);
      }
      
      g('panel-title').innerText = 
      e_button.innerText = Control.device.settings.title;
      e_dropdown.innerHTML = '';
      e_dropdown.appendChild(frag);
      g('screen-title').removeClass('expanded');
      document.removeEventListener('click', closeDropdown, true);
      document.removeEventListener('mousedown', closeDropdown, true);
      
      g('screen-title-up').disabled = (Control.getIndex() < 1);
      g('screen-title-down').disabled = (Control.getIndex() >= Control.devices.length-1);
    }
  })();
  
  function initCustomPlugins(useCurrent)
  {
    var frag = document.createDocumentFragment();
    var label = null;
    var ele = null;
    var names = plugin_handler.getNames();
    for (var i=0; i<names.length; i++)
    {
      label = document.createElement('label');
      ele = document.createElement('input');
      ele.type = 'checkbox';
      label.appendChild(ele);
      ele = document.createElement('span');
      ele.appendChild(document.createTextNode(names[i]));
      label.appendChild(ele);
      frag.appendChild(label);
    }
    g('custom-plugins').appendChild(frag);
  }
  
  var prepareCollapsables = (function()
  {
    function onclick(evt)
    {
      this.parentNode.toggleClass('collapsed');
    }
    
    return function()
    {
      var eles = document.getElementsByClassName('collapsable');
      var children = null;
      for (var i=0; i<eles.length; i++)
      {
        children = eles[i].getElementsByTagName('*');
        children[0].onclick = onclick;
      }
    }
  })();
  
  function toggleConfigMode()
  {
    var device = g('device');
    if (device.hasClass('config'))
    {
      applyGlobalDisable(false);
      device.removeClass('config')
      g('screen-buttons-config').removeClass('down');
    }
    else
    {
      var avoid = [
        g('screen-buttons'),
        g('config')
      ];
      applyGlobalDisable(true, avoid);
      device.addClass('config')
      g('screen-buttons-config').addClass('down');
    }
  }
  
  /**
   * Calculates how big the window size should be to fit the ui correctly.
   * @param screenWidth {Number} Optional width of screen to override the current screen's width
   * @param screenWidth {Number} Optional height of screen to override the current screen's height
   * @returns {Arraty} The width and height of the screen
   */
  
  function getScreenSize(screenWidth, screenHeight)
  {
    var width = Math.max(screenWidth || Control.device.screen.width, 200)+60;
    var height = Math.max(screenHeight || Control.device.screen.height, 200)+60;
    var ele = null;
    
    ele = g('panel');
    width += settings.showPanel ? ele.offsetWidth : 0;
    height = Math.max(height, ele.offsetHeight + 56);
    // constant of 56 makes up for things like style margins
    // which do not get calculated into offsetHeight
    
    height += Control.device.settings.loaded ? g('info').offsetHeight : 0;
    return [width,height];
  }
  
  /**
   * Shows and hides the panels
   */
  
  function showHidePanels()
  {
    g('panel').style.display = settings.showPanel ? 'block' : 'none';
    
    g('screen-title-info')[settings.showPanel?'addClass':'removeClass']('down');
    
    self.resizeWindow();
  };
  
  return self = {
    
    init: function()
    {
      tooltips.init();
      tooltips.crawl();
      applyOutline();
      this.updateStatus();
      showHidePanels();
      
      prepareCollapsables();
      
      g('config-showOutline').checked  = Control.config.showOutline;
      g('config-showOutline').onchange = function()
      {
        Control.config.showOutline = this.checked;
        widget.setPreferenceForKey(this.checked?'1':'0','config-showOutline');
        applyOutline();
      };
      
      g('config-animations').checked  = Control.config.animations;
      g('config-animations').onchange = function()
      {
        Control.config.animations = this.checked;
        widget.setPreferenceForKey(this.checked?'1':'0','config-animations');
      };
      
      g('screen-title-info').onclick = function()
      {
        settings.showPanel = !settings.showPanel;
        settings.save();
        showHidePanels();
      };
      
      g('screen-buttons-close').onclick = function()
      {
        window.close();
      };
      
      g('screen-buttons-config').onclick = toggleConfigMode;
      
      g('screen-title-reload').onclick = self.reloadWidget;
      
      g('config-form').onsubmit = function()
      {
        toggleConfigMode();
        return false;
      };
      
      g('panel-flip').onclick = function()
      {
        if (this.hasClass('down'))
        {
          g('panel-viewing').style.display = 'block';
          g('panel-editing').style.display = 'none';
          this.removeClass('down')
        }
        else
        {
          g('panel-viewing').style.display = 'none';
          g('panel-editing').style.display = 'block';
          this.addClass('down')
        }
        self.resizeWindow();
      };
      
      g('screen-buttons-help').onclick = function()
      {
        widget.openURL(g_helpURL);
      };
      
      g('desktop-error-reload').onclick = self.reloadWidget;
      g('desktop-error-cancel').onclick = function()
      {
        g('desktop').removeClass('error');
      }
      
      g('rotate').onclick = function()
      {
        this.toggleClass('down')
        Control.rotateDevice();
      }
      
      g('screen-tools-close').onclick = Control.closeWidget;
      g('screen-tools-dock').onclick = self.dockWidget;
      g('screen-showInfo').onclick = function()
      {
        settings.showInfo = !settings.showInfo;
        settings.save();
        
        this.toggleClass('down');
        g('info').style.display = this.hasClass('down') ? 'block' : 'none';
        
        self.resizeWindow();
      };
      
      g('info-storage-clear').onclick = function()
      {
        emulator.clearPreferences();
        self.updateStatus();
      };
      g('info-storage-post').onclick = function()
      {
        var str = "";
        var pref = Control.device.widget.preferences;
        for (var i in pref)
        {
          if (typeof pref[i] == 'string')
          {
            str += i + ' = ' + pref[i] + '\n';
          }
        }
        opera.postError('Widget Preferences:\n'+str);
      };
      
      g('desktop-colors').onclick = function(evt)
      {
        if (evt.target instanceof HTMLButtonElement)
        {
          var col = window.getComputedStyle(evt.target, '').backgroundColor;
          g('screen').style.backgroundColor = col;
          widget.setPreferenceForKey(col, 'desktopColor');
          
          // make sure not to have black text on black background
          g('desktop-widgets').style.color = col == '#000000' ? '#eee' : '';
        }
      }
      
      var col = widget.preferenceForKey('desktopColor');
      
      g('screen').style.backgroundColor = col || '#69A7A6';
      
      if (col == '#000000')
      {
        g('desktop-widgets').style.color = '#eee';
      }
      
      
      setTimeout(initCustomPlugins, 500);// todo: make this stronger
      
    },
    
    createPropertiesList: function(settings)
    {
      var ul = document.createElement('ul');
      ul.setAttribute('class','propertylist');
      var li = null;
      var span = null;
      
      var arr = [];
      arr.push('Media: "'+settings.media+'"');
      arr.push('Screen: '+settings.screen.join(' x '));
      arr.push('Storage space: ' +  settings.storage.toBytes());
      
      settings.rotatable && arr.push('Rotatable');
      
      arr.push('Dock: ' + (!settings.dock ? 'Not enabled' : settings.dock.join(' x ')));
      
      settings.chrome && arr.push('Chrome: ' + settings.chrome.join(', '));
      
      settings.useragent && arr.push('User Agent: '+settings.useragent)
      
      for (var i=0; i<arr.length; i++)
      {
        li = document.createElement('li');
        span = document.createElement('span');
        span.appendChild(document.createTextNode(arr[i]));
        li.appendChild(span);
        ul.appendChild(li);
      }
      
      return ul;
    },
    
    createPluginsList: function(settings)
    {
      var ul = document.createElement('ul');
      ul.setAttribute('class','propertylist');
      var li = null;
      var span = null;
      var plugins = settings.plugins;
      
      if (!plugins.length)
      {
        return document.createTextNode('None enabled');
      }
      
      for (var i=0; i<plugins.length; i++)
      {
        li = document.createElement('li');
        span = document.createElement('span');
        span.appendChild(document.createTextNode(plugins[i]));
        li.appendChild(span);
        ul.appendChild(li);
      }
      
      return ul;
    },
    
    devicesUpdated : function()
    {
      updateTitle();
    },
    
    /**
     * Updates the ui for the new device's screen size etc.
     *
     * @param settings {Array} The deb
     *
     */
    
    loadDevice : function(device, callback)
    {
      g('rotate').style.display = device.rotatable ? 'block' : 'none';
      g('rotate').removeClass('down');
      
      editing.focusDevice(Control.getCurrentDevice());
      
      g('panel-properties').innerHTML = '';
      g('panel-properties').appendChild(self.createPropertiesList(device));
      
      g('panel-plugins').innerHTML = '';
      g('panel-plugins').appendChild(self.createPluginsList(device));
      
      var screen = g('screen');
      
      var ANIMATION_TIME = 300;
      var startTime = new Date();      
      var startWidth = screen.offsetWidth;
      var startHeight = screen.offsetHeight;
      var width = Math.max(device.screen[0], 200);
      var height = Math.max(device.screen[1], 200)
      var interval = 0;
      
      if (Control.config.animations && startWidth &&
          (width != startWidth || height != startHeight) ) // do animation
      {
        applyGlobalDisable(true);
        self.resizeWindow.apply(self, getScreenSize(width, height)); // ensure screen is big enough
        interval = setInterval(nextFrame, 1);
      }
      else // no animations
      {
        setTimeout(done, 1); // ensure asynchronouse callback
      }
      
      function setSize(width, height)
      {
        g('screen').style.width = Math.floor(width) + 'px';
        g('screen').style.height = Math.floor(height) + 'px';
      }
      
      function nextFrame()
      {
        var progress = (new Date() - startTime) / ANIMATION_TIME;
        
        if (progress > 0 && progress < 1)
        {                  
          progress = 1-Math.pow(1-progress, 3); // smoother animation
        
          setSize(startWidth*(1-progress) + width*progress,
                  startHeight*(1-progress) + height*progress);
        }
        else // end of animation
        {
          setSize(width, height);
          clearInterval(interval);
          applyGlobalDisable(false);
          done();
        }
      }
      
      function done()
      {        
        setSize(width, height);
        
        // we must make special provisions for screens smaller than 200px
        g('screen-content').style.borderLeftWidth =   Math.max(0, Math.floor((200-device.screen[0])/2)) + 'px';
        g('screen-content').style.borderRightWidth =  Math.max(0, Math.ceil((200-device.screen[0])/2)) + 'px';
        g('screen-content').style.borderTopWidth =    Math.max(0, Math.floor((200-device.screen[1])/2)) + 'px';
        g('screen-content').style.borderBottomWidth = Math.max(0, Math.ceil((200-device.screen[1])/2)) + 'px';
        
        g('info').style.display = 'block'; // make sure we can read offsetWidth
        
        callback();
      }

    },
    
    /**
     * Performs UI adjustments to make the screen size a specific size.
     *
     * @param width {Number} Pixels
     * @param height {Number} Pixels
     */
    
    setScreenSize: function(width, height)
    {
      g('screen').style.width = width + 'px';
      g('screen').style.height = height + 'px';
      
      g('info').style.top = height;
      g('info').style.display = 'block'; // make sure we can read offsetWidth
    },
    
    alert: function(markup) // markup is either a string or a dom element
    {
      var winHeight = window.innerHeight;
      
      markup = createHTML(markup);
      
      g('dialogue').innerHTML = '';
      g('dialogue').appendChild(markup);
      g('dialogue').style.display = 'block';
      g('screen-blocker').style.display = 'block';
      
      document.body.addClass('dialogue');
      
      self.resizeWindow(500,170);
      
      applyGlobalDisable(true, g('dialogue'));
      
      // focus the submit button
      var input = g('dialogue').getElementsByTagName('input');
      for (var i=0; i<input.length; i++)
      {
        if (input[i].type.toUpperCase() == 'TEXT')
        {
          input[i].focus();
          input[i].select();
          return;
        }
      }
      for (var i=0; i<input.length; i++)
      {
        if (input[i].type.toUpperCase() == 'SUBMIT')
        {
          input[i].focus();
          return;
        }
      }
    },
    
    alertClose: function()
    {
      g('dialogue').innerHTML = '';
      g('dialogue').style.display = '';
      g('screen-blocker').style.display = '';
      self.resizeWindow();
      applyGlobalDisable(false);
      self.resizeWindow();
      document.body.removeClass('dialogue');
      return false;
    },
    
    failWidget: function(type)
    {
      Control.closeWidget();
      g('desktop').addClass('error');
      g('desktop-error').className = type;
      g('desktop-error-config-path').innerText = 'widgets/'+Control.device.widget.path+'/config.xml';
      var count = 0;
      var interval = setInterval(function()
      {
        if (count++>4) { clearInterval(interval); }
        g('desktop-error')[count%2?'addClass':'removeClass']('flash');
      },200);
    },
    
    setIcon: function(src)
    {
      var e_icon = g('info-icon');
      var DEFAULT = 'emulator_files/img/widget-icon.png';
      
      if (!src)
      {
        e_icon.setAttribute('src', DEFAULT);
      }
      
      src = 'widget/'+src;
      
      var pic = new Image();
      pic.onerror = function()
      {
        e_icon.setAttribute('src', DEFAULT);
      };
      pic.onload = function()
      {
        e_icon.setAttribute('src', src);
      };
      pic.src = src;
    },
    
    showPlugin: (function()
    {
      function onclick()
      {
        this.parentNode.toggleClass('collapsed');
        self.resizeWindow();
      }
      return function(name, frag)
      {
        if (!frag)
        {
          return;
        }
        var html = document.createElement('div');
        html.setAttribute('class', 'plugin');
        var ele = document.createElement('h4');
        ele.appendChild(document.createTextNode(name));
        ele.addEventListener('click', onclick, false);
        html.appendChild(ele);
        ele = document.createElement('div');
        ele.appendChild(frag);
        html.appendChild(ele);
        g('plugin-ui').appendChild(html);
      }
    })(),
    
    resizeWindow : function(minW,minH)
    {
      var size = getScreenSize();
      
      var width = size[0];
      var height = size[1];
      
      if (width < minW) width = minW;
      if (height< minH) height= minH;
      
      if (knownWidth != width || knownHeight != height)
      {
        window.resizeTo(knownWidth = width, knownHeight = height);
      }
      else
      {
        window.resizeBy(0, 0);
      }
    },
    /* this should only be called by Control */
    closeWidget : function()
    {
      g('frameWrapper').innerHTML = '';
      g('screen-content').style.display = '';
      g('desktop').style.display = 'block';
      scrollbars.fix();
      self.updateStatus();
    },
    
    reloadWidget : function()
    {
      if (Control.device.widget.path)
      {
        Control.closeWidget();
        Control.loadWidget(Control.device.widget.path);
      }
      else
      {
        if (Control.lastWidget)
        {
          Control.loadWidget(Control.lastWidget);
        }
      }
      return false;
    },
    
    dockWidget : function()
    {
      if (Control.device.widget.loaded)
      {
        emulator.toggleDocked();
      }
    },
    
    updateStatus : function()
    {      
      if (Control.device.settings.loaded && Control.device.widget.opened)
      {
        if (settings.showInfo)
        {
          var storage = 100*Control.device.widget.storage/Control.device.settings.storage;
          storage = storage>0&&storage<1 ? '< 1%' : Math.floor(storage)+'%';
          
          g('info-mode').innerText = Control.device.widget.mode;
          g('info-size').innerText = Control.device.widget.width + ' x ' + Control.device.widget.height;
          g('info-position').innerText = (Control.device.widget.mode=='docked' ? 'NA' : (Control.device.widget.left + ', ' + Control.device.widget.top));
          g('info-storage').innerText = storage
          
          g('info-storage-post').style.display = //
          g('info-storage-clear').style.display = Control.device.widget.storage ? '' : 'none';
          
          g('info').style.display = //
          g('screen-tools').style.display = 'block';
          g('screen-showInfo').addClass('down');
        }
        else
        {
          g('screen-showInfo').removeClass('down');
          g('info').style.display = 'none'          
        }
        g('screen-tools').style.display = 'block';
        g('screen-showInfo').style.display = 'block';
      }
      else
      {
        g('info').style.display = //
        g('screen-tools').style.display = //
        g('screen-showInfo').style.display = 'none';
      }
      self.resizeWindow();
    }
  };
  
  
})();