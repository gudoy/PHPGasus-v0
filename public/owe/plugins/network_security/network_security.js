/**
 * @fileoverview
 * 
 * <p>This is a plugin to handle the new Widget Security model introduced in Opera 10.</p>
 *
 * <p>At the moment it only checks for somethingin a 'network' attribute, nothing more.</p>
 */

var network = false;

var ERROR_MESSAGE = 'Network access is not permitted.\n\
A new security model in Opera 10 requires a network opt-in, older widgets should be updated to be forward compatible with this.\n\
This can enabled by setting a network="public" attribute on the widget element in your config.xml file.';

analyseSecurity();

function analyseSecurity()
{
  try {
    if (plugin.configXML.getAttribute('network'))
    {
      network = true;
    }
  }
  catch(ignore){}
}

/* restrict links */
  
window.document.addEventListener('click', function(evt)
{
  if (evt.target instanceof window.HTMLAnchorElement)
  {
    if (!network)
    {
      opera.postError(ERROR_MESSAGE);
      evt.preventDefault();
    }
  }
}, false);

/* restrict form submit */

window.document.addEventListener('submit', function(evt)
{
  if (!network)
  {
    opera.postError(ERROR_MESSAGE);
    evt.preventDefault();
  }
}, false);

/* restrict openURL */

var widget_openURL = widget.openURL;

widget.openURL = function()
{
  if (network)
  {
    widget_openURL.apply(widget, arguments);
  }
  else
  {
    opera.postError(ERROR_MESSAGE);
  }
}

/* restrict window.open */

var window_open = window.open;

window.open = function()
{
  if (network)
  {
    window_open.apply(window, arguments);
  }
  else
  {
    opera.postError(ERROR_MESSAGE);
  }
}

/* restrict xhr */

function READONLY()
{
    throw new Error('Attempted to set a readonly property.');
}

var window_XMLHttpRequest = XMLHttpRequest;

window.XMLHttpRequest = function()
{
  var self = this;
  
  this.__xhr = new window_XMLHttpRequest();
  this.__url = "";
  
  // getters and setters defined here instead of in the protype
  // so that XHR.hasOwnProperty(..) returns true for them
  
  this.__defineGetter__("readyState", function(){ return self.__xhr.readyState; });
  this.__defineSetter__("readyState", READONLY);
  
  this.__defineGetter__("responseText", function(){ return self.__xhr.responseText; });
  this.__defineSetter__("responseText", READONLY);
  
  this.__defineGetter__("responseXML", function(){ return self.__xhr.responseXML; });
  this.__defineSetter__("responseXML", READONLY);
  
  this.__defineGetter__("status", function(){ return self.__xhr.status; });
  this.__defineSetter__("status", READONLY);
  
  this.__defineGetter__("statusText", function(){ return self.__xhr.statusText; });
  this.__defineSetter__("statusText", READONLY);
  
  this.__xhr.onreadystatechange = function()
  {
    self.onreadystatechange && self.onreadystatechange();
  }
}

window.XMLHttpRequest.prototype =
{
  open: function()
  {
    this.__url = arguments[1]+'';
    this.__xhr.open.apply(this.__xhr, arguments);
  },
  send: function()
  {
    if (!network)
    {
      throw new Error(ERROR_MESSAGE);
    }
    this.__xhr.send.apply(this.__xhr, arguments);
  },
  abort: function()
  {    
    this.__xhr.abort.apply(this.__xhr, arguments);
  },
  getAllResponseHeaders: function()
  {
    this.__xhr.getAllResponseHeaders.apply(this.__xhr, arguments);
  },
  getResponseHeader: function()
  {
    this.__xhr.getResponseHeader.apply(this.__xhr, arguments);
  },
  setRequestHeader: function()
  {
    this.__xhr.setRequestHeader.apply(this.__xhr, arguments);
  },
  overrideMimeType: function()
  {
    this.__xhr.overrideMimeType.apply(this.__xhr, arguments);
  }
};

/* */