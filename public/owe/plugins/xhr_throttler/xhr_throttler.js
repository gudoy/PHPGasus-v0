/**
 * @fileoverview
 * 
 * <p>This is a plugin for the Widget Emulator.
 * It emulates two properties of mobiles:</p>
 *
 * <ol>
 *   <li>Slow internet connections</em><br>
 *   <li>Connections that may be online only part of the time
 * <ol>
 *
 * <p>It also outputs a handy indicator of the number of active XMLHttpRequests
 * at any given time.</p>
 */

// user variables
var speed = 0;
var is_online = true;

var originalXHR = window.XMLHttpRequest;

var waitingRequests = [];
var activeRequests = [];

plugin.onload = function()
{
  if (!window.navigator) // should never occur, but just be safe
  {
    window.navigator = {};
  }
  window.navigator.onLine = is_online;
};
window.addEventListener('load',function()
{
  if (!is_online)
  {
    askPermission();
  }
}, true);

function onOnlineChange(newValue)
{    
  if (window.navigator.onLine == newValue)
  {
    return;
  }
  
  window.navigator.onLine = newValue;
  
  var evt = window.document.createEvent('HTMLEvents');
  evt.initEvent(newValue ? 'online' : 'offline', false, false);
  window.document.dispatchEvent(evt);
  
  if (newValue == false)
  {
    while (activeRequests.length)
    {
      triggerFailedRequest(activeRequests[0]);
    }
  }
};

// requests are queued while this is true
var askingPermission = false;

function askPermission()
{
  askingPermission = true;
  
  plugin.prompt('Offline',
                'Do you want to switch to online mode?',
                null,
                askPermissionCallback);
};

function askPermissionCallback(ok)
{
  askingPermission = false;
  
  var req = null;
  
  if (ok)
  {
    is_online = true;
    while (waitingRequests.length)
    {
      req = waitingRequests[0];
      try
      {
        req.send.apply(req, req.__arguments);
      }
      catch(err)
      {
        removeFromArray(waitingRequests, req);
      }
    }
    // trigger onOnlineChange after pushing out the old requests
    // since they should have a chance to get out before any possible
    // new requests get made
    onOnlineChange(true);
  }
  else
  {
    onOnlineChange(is_online = false);
    while (waitingRequests.length)
    {
      triggerFailedRequest(waitingRequests.shift());
    }
  }
}

/* here begins the actual handlers */

window.XMLHttpRequest = function()
{
  var self = this;
  
  var sendTime;
  
  this.readyState = 0;
  this.responseText = "";
  this.responseXML = null;
  this.statusText  ="";
  
  this.__xhr = new originalXHR();
  
  this.__timeout = 0;
  this.__arguments = null; // for the send method
  
  this.send = function()
  {
    // avoid being in the queues twice
    removeFromArray(waitingRequests, this);
    removeFromArray(activeRequests, this);
    
    this.readyState = 0;
    this.responseText = "";
    this.responseXML = null;
    this.statusText  ="";
    
    clearTimeout(this.__timeout);
    
    this.__arguments = arguments;
    
    if (is_online)
    {
      activeRequests.push(this);
      sendTime = (new Date()).getTime();
      this.__xhr.send.apply(this.__xhr, arguments);
    }
    else
    {
      if (askingPermission)
      {
        waitingRequests.push(this);
      }
      else
      {
        // asynchronous
        this.__timeout = setTimeout(function(){
            triggerFailedRequest(self);
        }, 1);
      }
    }
  };
  
  this.abort = function()
  {
    if (removeFromArray(activeRequests, this))
    
    clearTimeout(this.__timeout);
    
    this.__xhr.abort.apply(this.__xhr, arguments);
  };
  
  this.__xhr.onreadystatechange = function()
  {
    if (this.readyState == 4)
    {
      if (speed)// (speed == 0) is a sentinel value which triggers "default connection speed".
      {
        var totalTime = ((new Date()).getTime() - sendTime);
        var expectTime = 1000 * (this.responseText.length + 1024) * 8 / speed; // *8 to go from bytes to bits
        if (totalTime >=0 && totalTime < expectTime) // the >=0 check is in case the user changed the system time during transaction
        {
          clearTimeout(self.__timeout);
          self.__timeout = setTimeout(function()
          {
            applyReadystatechange(self);
          }, Math.ceil(expectTime - totalTime) ); // ceil to avoid 0
          return;
        }
      }
    }
    
    // do it immediately for readystates 1,2,3 (and 4 if the xhr took a long time OR if speed is sentinel value (0) )
    applyReadystatechange(self);
  }
}

// only removes first instance
function removeFromArray(arr, item)
{
  for (var i=arr.length-1; i>=0; i--)
  {
    if (arr[i] == item)
    {
      arr.splice(i,1);
      return true;
    }
  }
  return false;
}

window.XMLHttpRequest.prototype = {
  
  open: function()
  {
    this.__xhr.open.apply(this.__xhr, arguments);
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

function applyReadystatechange(self)
{
  
  self.readyState = self.__xhr.readyState;
  
  if (self.__xhr.readyState == 4)
  {
    self.responseText = self.__xhr.responseText;
    self.status = self.__xhr.status;
    self.responseXML = self.__xhr.responseXML;
    
    removeFromArray(activeRequests, self);
  }
  
  // dispatch readystatechange event
  if (self.onreadystatechange)
  {
    self.onreadystatechange();
  }
}

function triggerFailedRequest(self)
{
  removeFromArray(activeRequests, self);
  
  clearTimeout(self.__timeout);
  
  self.responseText = "";
  self.status = 0;
  self.responseXML = null;
  self.statusText = "";
  
  self.__xhr.abort();
  
  while (self.readyState < 4)
  {
    self.readyState++;
    
    if (self.readyState != 3) // readyState 3 should not be fired
    {
      if (self.onreadystatechange)
      {
        self.onreadystatechange();
      }
    }
  }
}