var editing = (function()
{
  var self;
  
  function selectByValue(sel, value)
  {
    for (var i=0; i<sel.options.length; i++)
    {
      if (sel[i].value == value)
      {
        sel.options.selectedIndex = i;
        return;
      }
    }
    sel.options.selectedIndex = 0;
  }
  
  var buttons = null;
  function getDeviceButtonById(id)
  {
    if (!buttons) { buttons = g('panel-devices').getElementsByTagName('button'); }
    
    for (var i=0; i<buttons.length; i++)
    {
      if (buttons[i].getAttribute('uniqueId') == id)
      {
        return buttons[i];
      }
    }
    return null;
  }
  
  function disableButtonsWhileCreatingDevice()
  {
    var avoid = [
      g('editing-custom'),
      g('screen-buttons-help'),
      g('screen-buttons-close'),
      g('plugin-ui'),
      g('desktop')
    ];
    applyGlobalDisable(true, avoid);
  }
  
  
  var focusedDevice = null;
  var panelDeviceThis;
  
  
  function createButtonHTML(settings)
  {
    return createHTML([
      ['button',
        'type','button',
        'uniqueId', settings.id,
        'onclick', deviceOnclick,
        ['span',settings.title]
      ]
    ]);
  };
  
  function deviceOnclick(evt)
  {
    if (this==panelDeviceThis || this.disabled) return;
    
    var id = this.getAttribute('uniqueId');
    
    for (var i=0; i<Control.devices.length; i++)
    {
      if (Control.devices[i].id == id)
      {
        self.focusDevice(Control.devices[i]);
        return;
      }
    }
  }
  
  var editForm = (function()
  {
    var doNotSubmit = false;
    
    function restrict()
    {
      var str = ''
      var el = null;
      var err = false;
      
      el = g('custom-name');
      el.value = el.value.replace(/^\s+|\s+$/g,'').replace(/\s{2,}/g,' ');
      if (!el.value)
      {
        el.value = newDeviceString(focusedDevice);
        highlightInput(el);
        err = true;
      }
      
      el = g('custom-screen-x');
      str = (parseInt(el.value.filterNumbers())||0).toRange(64,800);
      if (el.value != str)
      {
        highlightInput(el);
        el.value = str;
        err = true;
      }
    
      el = g('custom-screen-y');
      str = (parseInt(el.value.filterNumbers())||0).toRange(64,800);
      if (el.value != str)
      {
        highlightInput(el);
        el.value = str;
        err = true;
      }
      
      if (g('custom-dock').checked)
      {
        el = g('custom-dock-x');
        str = (parseInt(el.value.filterNumbers())||0).toRange(16,400);
        if (el.value != str)
        {
          highlightInput(el);
          el.value = str;
          err = true;
        }
        
        el = g('custom-dock-y');
        str = (parseInt(el.value.filterNumbers())||0).toRange(16,400);
        if (el.value != str)
        {
          highlightInput(el);
          el.value = str;
          err = true;
        }
      }
      
      return err;
    };
    
    var highlightInput = (function()
    {
      var interval = 0;
      var count = 0;
      var ele = null;
      
      function cleanup()
      {
        clearInterval(interval);
        interval = 0;
        ele.removeEventListener('focus', cleanup, false);
        ele.style.color = '';
        ele = null;
        doNotSubmit = false;
      }
      function loop()
      {
        if (count < 12) { doNotSubmit = false; }
        if (--count<1)
        {
          cleanup();
        }
        else
        {
          ele.style.color = '#' + count.toString(16) + 0 + 0;
        }
      }
      
      return function(ele_new)
      {
        if (interval)
        {
          cleanup();
        }
        
        ele = ele_new;
        count = 16;
        interval = setInterval(loop, 200);
        
        ele.style.color = 'red';
        ele.addEventListener('focus', cleanup, false);
        doNotSubmit = true;
      }
    })();
    
    function ok()
    {
      var uniqueId = g('custom-id').value;
      var title = g('custom-name').value;
      var media = g('custom-media').options.selectedIndex || 0;
      var screen = [
        parseInt(g('custom-screen-x').value),
        parseInt(g('custom-screen-y').value)
      ];
      var storage = g('custom-storage').options.selectedIndex || 0;
      var rotatable = g('custom-rotatable').checked;
      
      var dock = g('custom-dock').checked && [
        parseInt(g('custom-dock-x').value),
        parseInt(g('custom-dock-y').value)
      ];
      
      var chrome = g('custom-chrome').checked && [
        parseInt(g('custom-chrome-top').value),
        parseInt(g('custom-chrome-right').value),
        parseInt(g('custom-chrome-bottom').value),
        parseInt(g('custom-chrome-left').value)
      ];
      
      // this is the only field not to be saved since its prefilled with default userAgent
      var useragent = g('custom-useragent').options.selectedIndex ?
                        g('custom-useragent-text').value : null;
      
      if (restrict() || doNotSubmit)
      {
        doNotSubmit = false;
        g('editing-ok').focus();
        return false;
      }
      
      /* restrict the dock, screen and chrome values from causing overlap */
      
      dock[0] = dock ? Math.max(16, Math.min(dock[0] || 48, screen[0])) : 0;
      dock[1] = dock ? Math.max(16, Math.min(dock[1] || 48, screen[1])) : 0;
      
      if (chrome && ( chrome[1] + chrome[3] + dock[0] > screen[0] ))
      {
        chrome[1] = chrome[3] = 0;
      }
      if (chrome && ( chrome[0] + chrome[2] + dock[1]> screen[1] ))
      {
        chrome[0] = chrome[2] = 0;
      }
      
      /* save the form values for next time */
      
      widget.setPreferenceForKey(''+media,'custom-media');
      widget.setPreferenceForKey(screen[0],'custom-screen-x');
      widget.setPreferenceForKey(screen[1],'custom-screen-y');
      widget.setPreferenceForKey(''+storage,'custom-storage');
      
      widget.setPreferenceForKey( dock ? '1' : '', 'custom-dock');
      if (dock)
      {
        widget.setPreferenceForKey(dock[0], 'custom-dock-x');
        widget.setPreferenceForKey(dock[1], 'custom-dock-y');
      }
      
      widget.setPreferenceForKey( chrome ? '1' : '', 'custom-chrome');
      if (chrome)
      {
        widget.setPreferenceForKey(chrome[0], 'custom-chrome-top');
        widget.setPreferenceForKey(chrome[1], 'custom-chrome-right');
        widget.setPreferenceForKey(chrome[2], 'custom-chrome-bottom');
        widget.setPreferenceForKey(chrome[3], 'custom-chrome-left');
      }
      
      media = g('custom-media').options[media].value;
      
      storage = parseInt(g('custom-storage').options[storage].value);
      
      if (chrome && !chrome[0] && !chrome[1] && !chrome[2] && !chrome[3])
      {
        chrome = null;
      }
      
      var plugins = [];
      var pluginNames = plugin_handler.getNames();
      var checkboxes = g('custom-plugins').getElementsByTagName('input');
      for (var i=0; i<checkboxes.length; i++)
      {
        if (checkboxes[i].checked)
        {
          plugins.push(pluginNames[i]);
        }
      }
      
      var settings = {
        id: uniqueId,
        title: title,
        media: media,
        screen: screen,
        storage: storage,
        rotatable: rotatable,
        dock: dock || null,
        chrome: chrome,
        useragent: useragent,
        plugins: plugins
      };
      
      g('editing-new').appendChild(g('editing-custom'));
      g('editing-new').style.display = 'none';
      
      Control.saveDevice(settings);
      
      cleanup();
      
      return false;
    }
      
    function cancel()
    {
      g('editing-new').appendChild(g('editing-custom'));
      g('editing-new').style.display = 'none';
      cleanup();
    }
    function cleanup(createLists)
    {
      
      g('editing-properties').innerHTML = '';
      g('editing-properties').appendChild(ui.createPropertiesList(focusedDevice));
      
      g('editing-plugins').innerHTML = '';
      g('editing-plugins').appendChild(ui.createPluginsList(focusedDevice));
      
      window.applyGlobalDisable(false);
      
      ui.resizeWindow();
    }
    
    return {
      init: function()
      {
        g('custom-name').onchange = restrict;
        g('custom-screen-x').onchange = restrict;
        g('custom-screen-y').onchange = restrict;
        g('custom-dock-x').onchange = restrict;
        g('custom-dock-y').onchange = restrict;
        
        g('editing-custom').onsubmit = ok;
        g('editing-cancel').onclick = cancel;
      },
      createNew : function()
      {        
        if (g('editing-new').style.display == 'block')
        {
          cancel();
          return;
        }
        
        g('editing-new').style.display = 'block';
        g('editing-new').appendChild(g('editing-custom'));
        
        g('custom-id').value = '';
        
        var e_name = g('custom-name');        
        e_name.value = newDeviceString();
        e_name.focus()
        e_name.select();
        
        g('custom-media').options.selectedIndex = parseInt(widget.preferenceForKey('custom-media')) || 0;
        g('custom-screen-x').value = widget.preferenceForKey('custom-screen-x') || '640';
        g('custom-screen-y').value = widget.preferenceForKey('custom-screen-y') || '480';
        g('custom-storage').options.selectedIndex = parseInt(widget.preferenceForKey('custom-storage')) || 0;
        
        var dock = !!widget.preferenceForKey('custom-dock');
        g('custom-dock').checked = dock;
        g('custom-dock-x').value = widget.preferenceForKey('custom-dock-x') || '48';
        g('custom-dock-y').value = widget.preferenceForKey('custom-dock-y') || '48';
        g('custom-dock-x').disabled = //
        g('custom-dock-y').disabled = !dock;
        
        g('custom-rotatable').checked = false;
        
        var chrome = !!widget.preferenceForKey('custom-chrome');
        g('custom-chrome').checked = chrome;
        g('custom-chrome-top').value    = widget.preferenceForKey('custom-chrome-top') || '0';
        g('custom-chrome-right').value  = widget.preferenceForKey('custom-chrome-right') || '0';
        g('custom-chrome-bottom').value = widget.preferenceForKey('custom-chrome-bottom') || '0';
        g('custom-chrome-left').value   = widget.preferenceForKey('custom-chrome-left') || '0';
        g('custom-chrome-top').disabled = //
        g('custom-chrome-right').disabled = //
        g('custom-chrome-bottom').disabled = //
        g('custom-chrome-left').disabled = !chrome;
        
        var checkboxes = g('custom-plugins').getElementsByTagName('input');
        for (var i=0; i<checkboxes.length; i++)
        {
          checkboxes[i].checked = false;
        }
        
        disableButtonsWhileCreatingDevice();
        
        g('device-new').disabled = false;
        
        g('custom-properties').style.display = 'block';
        g('custom-plugins').style.display = 'block';
        
        ui.resizeWindow();
      },
      prepare: function(settings)
      {
        g('custom-id').value = settings.id;
        
        var ele = g('custom-name');
        ele.value = settings.title;
        ele.focus()
        ele.select();
        
        selectByValue(g('custom-media'), settings.media);
        g('custom-screen-x').value = settings.screen[0];
        g('custom-screen-y').value = settings.screen[1];
        selectByValue(g('custom-storage'), settings.storage);
        
        g('custom-rotatable').checked = !!settings.rotatable;
        
        g('custom-dock').checked = !!settings.dock;
        g('custom-dock-x').value = settings.dock && settings.dock[0] || '48';
        g('custom-dock-y').value = settings.dock && settings.dock[1] || '48';
        g('custom-dock-x').disabled = //
        g('custom-dock-y').disabled = !settings.dock;
        
        g('custom-chrome').checked = !!settings.chrome;
        g('custom-chrome-top')   .value = settings.chrome && settings.chrome[0] || '0';
        g('custom-chrome-right') .value = settings.chrome && settings.chrome[1] || '0';
        g('custom-chrome-bottom').value = settings.chrome && settings.chrome[2] || '0';
        g('custom-chrome-left')  .value = settings.chrome && settings.chrome[3] || '0';
        g('custom-chrome-top')   .disabled = //
        g('custom-chrome-right') .disabled = //
        g('custom-chrome-bottom').disabled = //
        g('custom-chrome-left')  .disabled = !settings.chrome;
        
        g('custom-useragent').options.selectedIndex = settings.useragent ? 1 : 0;
        ele = g('custom-useragent-text')
        ele.value = settings.useragent || navigator.userAgent;
        ele.disabled = !settings.useragent;
        ele.setAttribute('data-lastvalue', ele.value);
        
        /* apply element values */
        var checkboxes = g('custom-plugins').getElementsByTagName('input');
        var plugins = plugin_handler.getNames();
        for (var i=0; i<checkboxes.length; i++)
        {
          checkboxes[i].checked = focusedDevice.plugins.contains(plugins[i]);
        }
      }
    }
  })();
  
  // create string like "New Device 3", so first
  // find out what number to place at the end:
  function newDeviceString(avoid)
  {
    var count = 0;
    var i;
    do {
      count++;
      title = "New Device" + (count>1 ? ' '+count : '');
      for (i=0; i<Control.devices.length; i++)
      {
        if ( Control.devices[i] != avoid && Control.devices[i].title == title )
        {
          break;
        }
      }
    } while (i < Control.devices.length)
    return title;
  }
  
  
  return self =
  {
    init: function()
    {
      editForm.init();
      
      self.generateDevices();
      
      g('device-new').onclick = editForm.createNew;
      
      g('custom-dock').onchange = function()
      {
        g('custom-dock-x').disabled = !this.checked;
        g('custom-dock-y').disabled = !this.checked;
      };
      
      g('custom-chrome').onchange = function()
      {
        g('custom-chrome-top')   .disabled = !this.checked;
        g('custom-chrome-right') .disabled = !this.checked;
        g('custom-chrome-bottom').disabled = !this.checked;
        g('custom-chrome-left')  .disabled = !this.checked;
      };
      
      g('custom-useragent').onchange = function()
      {
         var text = g('custom-useragent-text');
         
         if (this.options.selectedIndex) // custom
         {
           text.value = text.getAttribute('data-lastvalue');
           text.disabled = false;
         }
         else // default
         {
           text.setAttribute('data-lastvalue', text.value);
           text.disabled = true;
           text.value = navigator.userAgent;
         }
      };
     
      g('device-delete').onclick = self.deleteDevice;
      g('editing-properties-start').onclick = self.editProperties;
      g('editing-plugins-start').onclick = self.editPlugins;
    },
    
    deleteDevice: (function()
    {      
      // create local scope to avoid closure
      function onsubmit()
      {
        cancel();
        Control.deleteDevice(focusedDevice);
        return false;
      }
      
      function cancel()
      {
        g('editing-device').style.display = 'block';
        g('editing-delete').style.display = 'none'
        
        window.applyGlobalDisable(false);
        document.removeEventListener('keypress', keyPress, false);
      }
      
      function keyPress(evt)
      {
        if (evt.keyCode == 27) { cancel(); } // escape
      }
      
      return function()
      {
        g('editing-device').style.display = 'none';
        g('editing-delete').style.display = 'block';
        
        g('editing-delete-name').innerText = focusedDevice.title;
        
        
        window.applyGlobalDisable(true, g('editing-delete'));
        
        g('editing-delete').onsubmit = onsubmit;
        g('editing-delete-cancel').onclick = cancel;
        g('editing-delete-ok').focus();
        
        document.addEventListener('keypress', keyPress, false);
      }
    })(),
    
    editProperties: function()
    {
      var settings = focusedDevice;
      
      /* show the form */
      
      g('editing-properties').innerHTML = '';
      g('editing-properties').appendChild(g('editing-custom'));
      
      g('custom-properties').style.display = 'block';
      g('custom-plugins').style.display = 'none';
      
      ui.resizeWindow();
      
      /* disable other elements */
      
      disableButtonsWhileCreatingDevice();
      
      /* apply element values */
      
      editForm.prepare(settings);
    },
    
    editPlugins: function()
    {
      var settings = focusedDevice;
      
      /* show the form */
      
      g('editing-plugins').innerHTML = '';
      g('editing-plugins').appendChild(g('editing-custom'));
      
      g('custom-properties').style.display = 'none';
      g('custom-plugins').style.display = 'block';
      
      ui.resizeWindow();
      
      /* disable other elements */
      
      disableButtonsWhileCreatingDevice();
      
      /* apply element values */
      
      editForm.prepare(settings);
    },
    
    generateDevices : function()
    {
      var frag = document.createDocumentFragment();
      
      for (var i=0; i<Control.devices.length; i++)
      {
        frag.appendChild(createButtonHTML(Control.devices[i]));
      }
      
      g('panel-devices').appendChild(frag);
      
      tooltips.crawl();
      ui.resizeWindow();
    },
    
    loadDevice: function(settings)
    {
      var eles = g('panel-devices').getElementsByClassName('chosen');
      for (var i=eles.length-1; i>=0; i--)
      {
        eles[i].removeClass('chosen');
      }
      getDeviceButtonById(settings.id).addClass('chosen');
    },
    
    focusDevice: function(settings) // if (!e) then blur nothing, if (e==-1) then focus last buttong, else focus e
    {
      var ele = settings && getDeviceButtonById(settings.id);
      
      g('editing-area').style.display = 'block';
      g('editing-new').style.display = 'none';    
      
      focusedDevice = settings;
      
      if (ele)
      {
        if (panelDeviceThis)
        {
          panelDeviceThis.removeClass('expanded');
        }
        ele.addClass('expanded');
        ele.parentNode.insertBefore(g('editing-area'), ele);
        ele.parentNode.insertBefore(ele, g('editing-area')); // doing this twice is equiv. to insertAfter
        
        var device = Control.getDeviceById(ele.getAttribute('uniqueId'));
        
        g('editing-properties').innerHTML = '';
        g('editing-properties').appendChild(ui.createPropertiesList(device));
        
        g('editing-plugins').innerHTML = '';
        g('editing-plugins').appendChild(ui.createPluginsList(device));
      }
      else // blur buttons
      {
        if (panelDeviceThis)
        {
          panelDeviceThis.removeClass('expanded');
        }
        g('editing-area').style.display = 'none';
      }
      panelDeviceThis = ele;
      
      ui.resizeWindow();
    },
    
    updateDevice : function(settings)
    {
      getDeviceButtonById(settings.id).getElementsByTagName('span')[0].innerText = settings.title;
    },
    
    addDevice : function(settings)
    {
      g('panel-devices').appendChild(createButtonHTML(settings));
      ui.resizeWindow();
      g('device-delete').disabled = (Control.devices.length < 2);
    },
    
    removeDevice : function(i)
    {
      var button = g('panel-devices').getElementsByTagName('button')[i];
      button.parentNode.removeChild(button);
    }
  }
})();