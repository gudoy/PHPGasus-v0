var Control = (function()
{
    if (!window.widget) { return{}; } // return an object so that default_devices.js doesn't throw an error
    
    var self = null; // return object
    
    var lastDevice = widget.preferenceForKey('lastDevice');
    var preferences = widget.preferenceForKey('preferences') || {};
    
    return self = {
        
        devices: widget.preferenceForKey('devices'),
        devicesDefault: null, // set by index.html
        lastWidget: widget.preferenceForKey('lastWidget'),
        
        config : {
            showOutline: widget.preferenceForKey('config-showOutline') !='0',
            animations:  widget.preferenceForKey('config-animations')  !='0'
        },
        
        init: function()
        {
            if (!self.devices)
            {
                self.loadDefaultDevices();
            }
            
            // remove preferences of old widgets
            var culled = false;
            for (var i in preferences)
            {
                if (!g_widgets.contains(i))
                {
                    delete preferences[i];
                    culled = true;
                }
            }
            culled && self.savePreferences();
            
            
            emulator.init();
            scrollbars.init();
            ui.init();
            editing.init();
            manager.init();
            plugin_handler.init(self.loadLastDevice);
        },
        
        device: { // units are all integer pixels unless specified
            settings: {
              loaded: false
              /*
                title: (string: name of device)
                storage: (int: bytes available for preferences)
                loaded: (boolean: whether or not the device is loaded)
                toolbars: [0,0,0,0], (ints of padding of space for toolbars (eg plugins))
                rotated: false;
              */
            },
            media: {
                /* type etc */
            },
            screen: {
              /* width, height, availWidth, availHeight */
            },
            widget: {
              /*
              width,
              height,
              top,
              left,
              opened (boolean: step1 - if widget iframe has been added to dom),
              began (boolean: step2 - once the index.html file loads),
              loaded (boolean: step3 - if widget's onload event has fired),
              preferences (object),
              storage (int bytes used),
              mode,
              dockable,
              dockX,
              dockY,
              configXML
              */
            },
            plugins: []
        },
        
        loadDefaultDevices: function()
        {
            self.devices = self.devicesDefault.concat();
            for (var i=0; i<self.devices.length; i++)
            {
                self.devices[i].id = i+''; // need unique id strings
            }
        },
        
        savePreferences: function()
        {
            preferences[self.device.widget.path] = self.device.widget.preferences;
            
            widget.setPreferenceForKey(preferences,'preferences');
        },
        
        getCurrentDevice : function()
        {
            var settings = null;
            for (var i=0; i<self.devices.length; i++)
            {
              if (self.devices[i].id == lastDevice) { break; }
            }
            return (i==self.devices.length) ? null : self.devices[i];
        },
        
        getIndex : function()
        {
          for (var i=0; i<this.devices.length; i++)
          {
            if (this.devices[i].id == lastDevice)
            {
              return i;
            }
          }
          throw new Error('Unknown device index');
        },
        
        getDeviceById : function(id)
        {
            var index = self.getDeviceIndexById(id);
            return id < 0 ? null : self.devices[index];
        },
        
        getDeviceIndexById : function(id)
        {
            for (var i=0; i<self.devices.length; i++)
            {
                if (self.devices[i].id == id)
                {
                    return i;
                }
            }
            return -1;
        },
        
        loadDevice : function(device)
        {
            ui.loadDevice(device, function()
            {  
                emulator.loadDevice(device, false);
                ui.devicesUpdated();
                if (self.lastWidget)
                {
                    self.loadWidget(self.lastWidget);
                }
                else
                {
                    g('desktop').style.display = 'block';
                }
            });
            lastDevice = device.id;
            widget.setPreferenceForKey(lastDevice, 'lastDevice'); // remember last device
            editing.loadDevice(device);
        },
        
        loadLastDevice : function()
        {
            if (!self.devices.length) { return; }
            
            // load last device
            for (var i=0; i<self.devices.length; i++)
            {
                if (self.devices[i].id == lastDevice)
                {
                    break;
                }
            }
            if (i==self.devices.length) // load the first device since we can't find the last
            {
                i = 0;
                lastDevice = self.devices[0].id;
            }
            self.loadDevice(self.devices[i]);
        },
        
        rotateDevice: function()
        {
          self.device.rotated = !self.device.rotated;
          
          emulator.rotate();
        },
        
        deleteDevice: function(settings)
        {
          // find the device to delete
          for (var i=0; i<self.devices.length; i++)
          {
            if( self.devices[i] == settings)
            {
              break;
            }
          }
          
          if (i==self.devices.length) { return; }
          
          if (self.devices.length<2)
          {
            // restore defaults when nothing left
            self.loadDefaultDevices();
            
            editing.removeDevice(i);
            
            editing.generateDevices();
            self.loadDevice(self.devices[0]);
          }
          else
          {
            self.devices.splice(i,1);
            editing.removeDevice(i);
            
            if (settings.id == lastDevice)
            {
              self.loadDevice(self.devices[Math.min(self.devices.length-1, i)]);
            }
            else
            {
              editing.focusDevice(self.devices[Math.min(self.devices.length-1, i)]);
              ui.devicesUpdated();
            }
          }
          
          widget.setPreferenceForKey(self.devices,'devices');
        },
        
        /**
         * Callback function for opera.io.filesystem.browseForDirectory
         * Validates the directory as a widget
         * If it is valid then it stores it
         * 
         * @param The new directory
         */
        
        loadWidget: function(path)
        {
            if (self.device.widget.opened)
            {
                self.closeWidget();
            }
            
            // remember as last widget
            self.lastWidget = path;
            widget.setPreferenceForKey(path,'lastWidget');
            
            // load preferences
            self.device.widget.preferences = preferences[path] || {};
            
            emulator.loadWidget(path);
        },
        
        closeWidget: function()
        {
            if (!self.device.widget.opened) { return; } // not opened
            plugin_handler.closeAllPlugins();
            self.device.widget.opened = false;
            self.device.widget.began = false;
            emulator.closeWidget();
            ui.closeWidget();
            
            // forget as last widget
            self.lastWidget = '';
            widget.setPreferenceForKey('','lastWidget');
        },
        
        saveDevice : function(settings)
        {
            var index = self.getDeviceIndexById(settings.id);
            
            if (index < 0)
            {
                settings.id = 'random'+Math.random();
                self.devices.push(settings);
                editing.addDevice(settings);
            }
            else
            {
                self.devices[index] = settings;
                editing.updateDevice(settings);
            }
            
            widget.setPreferenceForKey(self.devices, 'devices');
            
            if (lastDevice == settings.id)
            {
              self.loadDevice(settings);
            }
            else
            {
              ui.devicesUpdated();
            }
        },
        
        startPlugins: function(frameWindow)
        {
            var name = '';
            var plugins = self.device.plugins;
            var frag = null;
            this.device.widget.JSPlugins = emulator.detectJSPlugins(this.device.widget.configXML);
            for (var i=0; i<plugins.length; i++)
            {
                try {
                    ui.showPlugin(plugins[i], plugin_handler.startPlugin(plugins[i], frameWindow));
                }
                catch(e)
                {
                    opera.postError(e);
                }
            }
        }
        
    };
    
})();