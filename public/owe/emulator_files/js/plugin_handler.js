/**
 * Handles loading and execution of plugins
 * @constructor;
 */

var plugin_handler = (function()
{
    var plugins = [];
    
    if (!window.widget) { return; }
    
    /**
     * PluginArgument
     * 
     * Creates a blank 'plugin' object to be passed to
     * the plugin as an argument.
     *
     * Has special methods and constants for plugin use.
     *
     * @constructor
     */
    function PluginArgument(plugin)
    {
        this.window = window;
        this.onunload = null;
        this.configXML = Control.device.widget.configXML;
        this.JSPlugins = Control.device.widget.JSPlugins;
        this.path = plugin.path;
        
        this.chrome = [
            g('screen-chrome-top'),
            g('screen-chrome-right'),
            g('screen-chrome-bottom'),
            g('screen-chrome-left')
        ];
        
        /* internal */
        this._eventListeners = {};
    }
    
    PluginArgument.prototype = {
        
        /**
         * Creates a question for the user
         *
         * @param title {String} The title of the question
         * @param text {String} The body message
         * @param checkbox {String} If set, then a label to apply to a checkbox question
         * @param callback {String} The callback, gets called with 'true' or 'false'
         */
        
        prompt: function(title, text, checkbox, callback)
        {
            title = (title || 'Prompt') + '';
            text = (text || '') + '';
            checkbox = (checkbox || '') + '';
            emulator.prompt(title, text, checkbox, callback);
        },

        /**
         * Creates a question for the user
         *
         * @param title {String} The title of the alert
         * @param text {String} The body message
         * @param callback {String} Optional callback
         */
        
        alert: function(title, text, callback)
        {
            title = (title || 'Alert') + '';
            text = (text || '') + '';
            emulator.alert(title, text, callback);
        },
        
        /**
         * Causes the widget to go into docked mode if it is not already
         * @returns {Boolean} Whether or not the widget is docked after the operation
         */
        
        dock: function()
        {
            if (Control.device.widget.mode !='docked')
            {
                emulator.toggleDocked();
            }
            return (Control.device.widget.mode =='docked');
        },
        
        /**
         * Undocks the widget if it is in docked mode
         */
        
        undock: function()
        {
            if (Control.device.widget.mode =='docked')
            {
                emulator.toggleDocked();
            }
        },

        /**
         * Chooses the default widget-mode for the widget.
         * Normally it is 'widget', but for some devices
         * it will be 'application'.
         *
         * @param mode {String} The name of the new default mode
         */
        
        setDefaultWidgetMode: function(mode)
        {
            if (!mode || !(mode += ''))
            {
                throw new Error('Invalid "mode" attribute.');
            }
            emulator.setDefaultWidgetMode(mode);
        },
        
        /** following code taken from Runeh's eventMixin.js */
        
        /**
        * Add an event listener for a given event.
        * @argument name {String} The name of the event to register
        * @argument callback {Function} The function to call when event is raised
        */
        addEventListener: function(name, callback) {
            if (name in this._eventListeners) {
                this._eventListeners[name].push(callback);
            } else {
                this._eventListeners[name] = [callback]
            }
        },
        /**
         * Remove a listener for a given event.
         * @argument name {String} The name of the event to register
         * @argument callback {Function} The function to call when event is raised
         */
        removeEventListener: function(name, callback) {
            if (!(name in this._eventListeners)) {return};
            var listeners = this._eventListeners[name];
            for (var n=listeners.length-1, e; e=listeners[n]; n--) {
                if (e==callback) { listeners.splice(n, 1); }
            }
        },
        /**
         * Dispatch an event
         * Internal
         */
        _dispatchEvent: function(name, payload) {
            if (!(name in this._eventListeners)) { return }
            var handlers = this._eventListeners[name];
            for (var n=0, e; e=handlers[n]; n++) {
                e(payload);
            }
        }
    };
    
    function Plugin(name, callback)
    {       
        var self = this;
        
        this.name = '';
        this.xml = null;
        this.scope = null;
        this.vars = [];
        this.path = 'plugins/'+name+'/';
        this.script = "";
        
        loadXML(this.path+'plugin.xml', function(xml)
        {
            self.name = xml.firstChild.getAttribute('name');
            self.xml = xml.firstChild.cloneNode(true)
            self.loadScripts(callback);
        });
    }
    
    /**
     * This preloads the <script> elements.
     * They must be ready before the widget runs.
     *
     * @param callback Function called once all scripts have loaded
     */
    
    Plugin.prototype.loadScripts = function(callback)
    {
        var self = this;
        var script = this.xml.getElementsByTagName('script');
        var ele = null;
        var scriptsLoaded = 0;
        
        if (!script.length)
        {
            callback();
            return;
        }
        
        for (var i=0; i<script.length; i++)
        {
            if (script[i].parentNode == this.xml) // must be child of <plugin>
            {
                loadText(this.path+script[i].getAttribute('src'),function(str)
                {
                    if (str)
                    {
                        self.script += str + ';';
                    }
                    
                    scriptsLoaded++;
                    if (scriptsLoaded == script.length)
                    {
                        callback();
                    }
                });
            }
        }
    }
    
    var scopes = {}; // key is the plugin name, value is a function
    
    (function()
    {
        /**
         * Escapes a variable name
         *
         * <p>This will transfor a variable name described as a string
         * to an identifier literal, so for example:</p>
         *
         * <ul>
         *  <li>"foo" -> "\u0066\u006f\u006f"
         *  <li>"foo bar" -> "\u0066\u006f\u006f\u0020\u0062\u0061\u0072"
         * </ul>
         *
         * <p>This is because scope functions use eval() to set and retrieve
         * variables, and if there is a nasty character in there, such
         * as a quote, then an error would have been thrown.</p>
         *
         * <p>It will not escape the '.' character though, since that is
         * used to refer to properties.</p>
         *
         * @param str {String} The variable name to escape
         * @returns {String} The escaped variable name
         */
        var escapeVar = (function()
        {
            var regexp = /[^.]/g;
            function funct(str)
            {
                str = str.charCodeAt(0).toString(16);
                while (str.length<4) str = '0'+str;
                return '\\u'+str;
            }
            return function(varName)
            {
                return varName.replace(regexp, funct);
            }
        })();
        
        function createInput(ele, defaultValue)
        {
            // type is case insensitive, default to string
            var type = (ele.getAttribute('type')||'string').toLowerCase()
            var input = null;
            
            // disposable vars:
            var i=0;
            var options = null;
            var tempEle = null;
            
            switch (type)
            {
                case 'string' :
                    input = document.createElement('input');
                    input.setAttribute('type','text');
                    input.setAttribute('value', defaultValue || '');
                    break;
                
                case 'output' :
                    input = document.createElement('output');
                    input.setAttribute('value', defaultValue+'');
                    break;
                
                case 'number' :
                    input = document.createElement('input');
                    input.setAttribute('type','number');
                    input.setAttribute('value', defaultValue ? Number(defaultValue).toString() : '0')
                    break;
                
                case 'boolean' :
                    input = document.createElement('input');
                    input.setAttribute('type', 'checkbox');
                    if (defaultValue=='true')
                    {
                        input.setAttribute('checked','checked');
                    }
                    break;
                
                case 'date' :
                    input = document.createElement('input');
                    input.setAttribute('type','date');
                    break;
                
                case 'select' :
                    input = document.createElement('select');
                    options = ele.getElementsByTagName('option');
                    for (i=0; i<options.length; i++)
                    {
                        tempEle = document.createElement('option');
                        tempEle.setAttribute('_varType', (options[i].getAttribute('type')||'string').toLowerCase());
                        tempEle.setAttribute('value',options[i].getAttribute('value'));
                        tempEle.appendChild(
                            document.createTextNode(
                                options[i].getAttribute('title') ||
                                options[i].getAttribute('value')));
                        if (options[i].getAttribute('selected') == 'selected')
                        {
                            tempEle.setAttribute('selected','selected');
                        }
                        input.appendChild(tempEle);
                    }
                    break;
                
                default :
                    throw new Error('Unknown variable type in plugin');
            }
            
            input.setAttribute('_varType', type);
            
            return input;
        }
        
        function showVarValue(input)
        {
            var value = null;
            var i=0;
            
            try {
                value = scopes[input.getAttribute('_plugin')].eval(input.getAttribute('_varName'));
            }
            catch(e)
            {
                value = null;
            }
            
            switch (input.getAttribute('_varType'))
            {
                case 'boolean' :
                    value = !!value;
                    if (input.checked != value)
                    {
                        input.checked = value;
                        variableHandler.varChanged(input);
                    }
                    break;
                default :
                    if (input.value !== value)
                    {
                        input.value = value;
                        variableHandler.varChanged(input);
                    }
                    break;
            }
        }
        
        function resolveInputValue(ele)
        {
            var value = null;
            
            switch (ele.getAttribute('_varType'))
            {
                case 'string' :
                    value = ele.value;
                    break;
                case 'number' :
                    value = Number(ele.value);
                    ele.value = value || 0;
                    break;
                case 'date' :
                    // todo
                    break;
                case 'boolean' :
                    value = ele.checked;
                    break;
                case 'select' :
                    ele = ele.options[ele.options.selectedIndex];
                    switch (ele.getAttribute('_varType'))
                    {
                        case 'string' :
                            value = ele.value;
                            break;
                        case 'number' :
                            value = Number(ele.value);
                            break;
                        case 'date' :
                        case 'boolean' :
                        case 'select' :
                            throw new Error('<option> element cannot have types: "string", "boolean", "select".'); // this should never throw
                            break;
                        default :
                            throw new Error('Unknown varType: '+ele.getAttribute('_varType')); // this should never throw
                    }  
                    break;
                default :
                    throw new Error('Unknown varType: '+ele.getAttribute('_varType')); // this should never throw
            }
            return value;
        }
        
        function stringifyValue(value)
        {
            switch (typeof value)
            {
                case 'string' :
                    value = '"'+value.replace('\\','\\\\').replace(/\"/,'\\"')+'"';
                    break;
                case 'number' :
                    value = Number(value).toString();
                    break;
                case 'date' :
                    // todo
                    break;
                case 'boolean' :
                    value = value + '';
                    break;
                default :
                    throw new Error('Unknown varType'); // this should never throw
            }
            return value;
        }
        
        var variableHandler = new function()
        {
            var self = this;
            var savedValues = widget.preferenceForKey('pluginValues') || {};
            
            if (typeof savedValues == 'string')
            {
                savedValues = {};
            }
            function focusEvent(e)
            {
                this.setAttribute('_oldValue',this.value);
            }
            function keyEvent(e)
            {
                switch (e.keyCode)
                {
                    case 13 : // enter-key, set the value
                        changeEvent.call(this);
                        this.setAttribute('_oldValue',this.value);
                        this.select();
                        break;
                    case 27 : // escape, return to previous value
                        this.value = this.getAttribute('_oldValue');
                        this.select();
                        break;
                }
            }
            function changeEvent(e)
            {
                var varName = this.getAttribute('_varName');
                var varType = this.getAttribute('_varType');
                var pluginName = this.getAttribute('_plugin');
                var onChange = this.getAttribute('_onchange');
                var valueString = stringifyValue(resolveInputValue(this));
                
                try {
                    scopes[pluginName].eval(escapeVar(varName)+'='+valueString);
                }
                catch(e)
                {
                    throw new Error('variable could not be set');
                    return;
                }
                
                self.varChanged(this);
                
                if (onChange)
                {
                    try {
                        scopes[pluginName].eval(escapeVar(onChange)+'('+valueString+')');
                    }
                    catch(e)
                    {
                        throw new Error('error thrown in variable onchange event handler');
                    }
                }
            }
            this.varChanged = function(ele)
            {
                var varName = ele.getAttribute('_varName');
                var pluginName = ele.getAttribute('_plugin');
                var value = null;
                
                try {
                    value = scopes[pluginName].eval(varName);
                }
                catch(e) {
                    return;
                }
                
                if (!savedValues[pluginName])
                {
                    savedValues[pluginName] = {};
                }
                // store the new value as a preference:
                if (savedValues[pluginName][varName] != value)
                {
                    savedValues[pluginName][varName] = value;
                    widget.setPreferenceForKey(savedValues, 'pluginValues');
                }
            }
            function loadSavedValue(input, pluginName, varName)
            {
                var value = null;
                if ( savedValues[pluginName] && savedValues[pluginName].hasOwnProperty(varName))
                {
                    
                    try {
                        value = savedValues[pluginName][varName];
                        
                        scopes[input.getAttribute('_plugin')].eval(escapeVar(varName)+'='+stringifyValue(value));
                    }
                    catch (e)
                    {
                        opera.postError(e);
                        return false;
                    }
                    
                    switch (input.getAttribute('_varType'))
                    {
                        case 'boolean' :
                            input.checked = !!value;
                            break;
                        default :
                            input.value = value;
                            break;
                        
                    }
                    
                    return true;
                }
                return false;
            }
            
            this.create = function(ele, pluginName, plugin)
            {
                var label = document.createElement('label');
                var span = document.createElement('span');
                
                var input = createInput(ele);
                var varName = ele.getAttribute('name');
                // set personal attributes so that they can
                // share the same event handlers
                input.setAttribute('_varName', varName);
                
                input.setAttribute('_plugin', pluginName);
                
                if (ele.getAttribute('onchange'))
                {
                    input.setAttribute('_onchange', ele.getAttribute('onchange'));
                }
                
                input.onfocus = focusEvent;
                input.onkeypress = keyEvent;
                input.onchange = changeEvent;
                
                plugin.vars.push(input);
                
                if (ele.getAttribute('remember') !='true' ||
                    !loadSavedValue(input, pluginName, varName))
                {
                    showVarValue(input); // show it's current value
                }
                
                span.appendChild(document.createTextNode(ele.getAttribute('title')));
                label.appendChild(span);
                label.appendChild(input);
                
                return label;
            };
        };
        
        /**
         * Creates form for a function
         *
         * Constructs all elements and
         * event handlers.
         *
         * @param ele {Element} The SVG element describing the function
         * @returns The form element
         */
        
        var createFunction = (function()
        {
            function submitEvent(e)
            {
                var functionName = this.getAttribute('_functionName');
                
                var args = this.getElementsByClassName('arg');
                var arr = [];
                
                for (var i=0; i<args.length; i++)
                {
                    arr[i] = stringifyValue(resolveInputValue(args[i]));
                }
                
                try
                {
                    scopes[this.getAttribute('_plugin')].eval(escapeVar(functionName)+'('+arr.join(',')+')');
                }
                catch (err)
                {
                    opera.postError(err);
                    throw new Error('Failed to call the function: ');
                    
                }
                
                e.preventDefault();
                return false;
            }
            
            function createArg(arg)
            {
                var label = document.createElement('label');
                var span = document.createElement('span');
                
                var input = createInput(arg, arg.getAttribute('value'));
                
                input.setAttribute('class', 'arg'); // so we can search for them
                
                span.appendChild(document.createTextNode(arg.getAttribute('title')));
                
                label.appendChild(span);
                label.appendChild(input);
                
                return label;
            }
            
            /*
             The function below is the function 'createFunction'
             (Currying technique)
            */
            
            return function(ele, pluginName)
            {
                var form = document.createElement('form');
                var fieldset = document.createElement('fieldset');
                var legend = document.createElement('legend');
                var submit = document.createElement('input');
                var input = null;
                
                var args = ele.getElementsByTagName('arg');
                var i=0;
                
                legend.appendChild(document.createTextNode(ele.getAttribute('title')));
                fieldset.appendChild(legend);
                
                for (i=0; i<args.length; i++)
                {
                    fieldset.appendChild(createArg(args[i]));
                }
                
                submit.type = 'submit';
                submit.value = ele.getAttribute('button');
                
                form.setAttribute('_plugin', pluginName);
                form.setAttribute('_functionName', ele.getAttribute('name'));
                
                fieldset.appendChild(submit);
                form.appendChild(fieldset);
                
                form.onsubmit = submitEvent;
                
                return form;
            }
        })();
        
        Plugin.prototype.poll = function()
        {
            for (var i=0; i<this.vars.length; i++)
            {
                showVarValue(this.vars[i]);
            }
        };
    
        
        Plugin.prototype.start =  function(win)
        {            
            // peacekeeping
            if (this.xml.tagName.toLowerCase() != 'plugin') { throw 'Must have <plugin> tag'; }
            
            var pluginName = this.xml.getAttribute('name');
            var str = '';
            
            var scope = scopes[pluginName] = {
                styles: [],
                eval: null,
                arg: null
            };
            
            /*
             Import the <style>
             */
            var style = this.xml.getElementsByTagName('style');
            var ele = null;
            for (var i=0; i<style.length; i++)
            {
                if (style[i].parentNode == this.xml) // must be child of <plugin>
                {
                    ele = document.createElement('style');
                    ele.innerHTML = '@import "'+this.path+style[i].getAttribute('src')+'";';
                    document.getElementsByTagName('head')[0].appendChild(ele);
                    scope.styles.push(ele);
                }
            }
            
            var funct = new win.Function('plugin',this.script + ';return function(){return eval(arguments[0])}');
            var arg = new PluginArgument(this);          
            
            scope.eval = funct(arg);
            scope.arg = arg;
            
            /*
              Explaining the logic above: Using Function() instead of eval()
              allows the variables in the script to be local, rather than global.
              
              The return value of the function, which is wrapping eval() is essential,
              as it is the only way to get acess to that scope, to be able to read
              and write those local variables.
            */
            
            var html = document.createDocumentFragment();
            var ui = this.xml.getElementsByTagName('ui')[0];
            
            if (ui)
            {
                
                var elements = ui.getElementsByTagName('*');
                var count = 0;
                
                for (var i=0; i<elements.length; i++)
                {
                    str = elements[i].getAttribute('jsplugin');
                    
                    if (str && !Control.device.widget.JSPlugins[str])
                    {
                        continue; // jsplugin required for this variable
                    }
                    
                    switch (elements[i].tagName)
                    {
                        case 'var' :
                            count++;
                            html.appendChild(variableHandler.create(elements[i], pluginName, this));
                            break;
                        
                        case 'function' :
                            count++;
                            html.appendChild(createFunction(elements[i], pluginName));
                            break;
                    }
                }
                
            }
            
            scope.arg._dispatchEvent('load', null);
            if (scope.arg.onload)
            {
                scope.arg.onload();
            }
            
            return count ? html : null;
        };
    })();
    
    function resolveObject(win, namespace)
    {
        if (!namespace) { return {} }
        
        var arr = namespace.split('.');
        for (var i=0; i<arr.length; i++)
        {
            if (!arr[i]) { throw 'Invalid namespace for <object>'}
            if (!win[arr[i]])
            {
                win[arr[i]] = {};
            }
            win = win[arr[i]];
        }
        return win;
    }
    
    var poller = (function()
    {
        var interval = 0;
        
        var self = {
            start: function()
            {
                if (interval)
                {
                    return; // already started, no need to start again
                }
                interval = setInterval(loop, 500);
            },
            stop: function()
            {
                if (!interval)
                {
                    return; // already stopped, no need to stop again
                }
                clearInterval(interval);
                interval = 0;
            }
        }
        
        function loop()
        {
            for (var i=0; i<plugins.length; i++)
            {
                plugins[i].poll();
            }
        }
        
        return self;
    })();
    
    return {
        
        /**
         * @param callback Function called once all plugins are loaded
         */
        
        init: function(callback)
        {
            for (var i=0; i<g_plugins.length; i++)
            {
                plugins.push(new Plugin(g_plugins[i], onload));
            }
            
            var loadedPlugins = 0;
            function onload()
            {
                loadedPlugins++
                if (loadedPlugins == g_plugins.length)
                {
                    callback();
                }
            }
        },
        
        getNames: function()
        {
            var arr = [];
            for (var i=0; i<plugins.length; i++)
            {
                arr[i] = plugins[i].name;
            }
            return arr;
        },
        
        startPlugin: function(name, frameWindow)
        {
            var plugin = null;
            for (var i=0; i<plugins.length; i++)
            {
                if (plugins[i].name == name)
                {
                    plugin = plugins[i].start(frameWindow);
                    
                    poller.start();
                    return plugin;
                }
            }
            throw new Error('Plugin not found: '+name);
            return null;
        },
        
        closeAllPlugins: function()
        {
          var scope;
          var i;
          var j;
          for (i=0; i<Control.device.plugins.length; i++)
          {
            scope = scopes[Control.device.plugins[i]];
            if (!scope) { continue; }
            
            // dispatch unload event
            try
            {
                scope.arg._dispatchEvent('unload', null);
                if (scope.arg.onunload)
                {
                    scope.arg.onunload();
                }
            }
            catch(err)
            {
                opera.postError(err);
            }
            
            // remove the styles that were added
            for (j=0; j<scope.styles.length; j++)
            {
                scope.styles[j].parentNode.removeChild(scope.styles[j]);
            }
          }
          poller.stop();
        }
    }
    
})();
