/**
 *
 * @fileoverview
 *
 * <p>Handles the listing of available widgets in the 'widgets' directory</p>
 * 
 */



var manager = new function()
{
    var widgets = [];
    
    function Widget(path)
    {
        this.path = path;
        this.button = document.createElement('button');
        this.configXML = null;
        this.nextWidget = null; // next widget in list
        
        var ele;
        
        this.button.setAttribute('type', 'button');
        this.button.setAttribute('data-path', path);
        this.button.addEventListener('click',buttonClick,false);
        
        ele = document.createElement('img');
        ele.src = 'emulator_files/img/wgt_icon.png';
        this.button.appendChild(ele);
        
        ele = document.createElement('span'); // blank for now
        this.button.appendChild(ele);
        
        ele = document.createElement('small');
        ele.innerText = path;
        this.button.appendChild(ele);
    }
    Widget.prototype =
    {
        /**
            Loads the configXML file of the widget.
            @param doNext {Boolean} If true then does the next widget in the lest after
        */
        analyse: function(doNext)
        {
            var self = this;
            loadXML('widgets/'+this.path+'/config.xml', function(xml)
            {
                self.configXML = xml;
                self.analyseCallback(doNext);
            });
        },
        /**
        *  Sets widget's name and icon in the list
        */
        analyseCallback: function(doNext)
        {
            // find out the widgetName
            var name = 'No Name';
            try {
                name = this.configXML.getElementsByTagName('widgetname')[0].firstChild.nodeValue;
            }
            catch(ignore){}
            
            this.button.getElementsByTagName('span')[0].innerText = name;
            
            // set the icon if it is specified
            try {
                this.button.getElementsByTagName('img')[0].src =
                    'widgets/'+this.path+'/'+
                    this.configXML.getElementsByTagName('icon')[0].firstChild.nodeValue;
            }
            catch(ignore){}
            
            // iterate over remaining widgets
            if (doNext && this.nextWidget)
            {
              this.nextWidget.analyse(true);
            }
        }
    };
    
    this.init = function()
    {
        this.listWidgets();
    };
    
    this.listWidgets = function()
    {
        var frag = document.createDocumentFragment();
        var widget = null;
        
        for (var i=0; i<g_widgets.length; i++)
        {
            widget = new Widget(g_widgets[i]);
            
            frag.appendChild(widget.button);
            
            widgets[i] = widget;
            
            if (i>0)
            {
                widgets[i-1].nextWidget = widget;
            }
        }
        g('desktop-widgets-list').innerHTML = '';
        g('desktop-widgets-list').appendChild(frag);
        
        if (widgets[0])
        {
          widgets[0].analyse(true); // 'true' will iterate over all widgets
        }
    }
    
    this.updateWidget = function(path, configXML)
    {
        for (var i=0; i<widgets.length; i++)
        {
            if (widgets[i].path == path)
            {
                widgets[i].configXML = configXML;
                widgets[i].analyseCallback(false);
            }
        }
    }
    
    function buttonClick()
    {
        Control.loadWidget(this.getAttribute('data-path'))
    }
}