/*
 Generic tool functions in this js file
*/

// shortcut to get element by id
function g(e){
  return document.getElementById(e);
}

String.prototype.safe = function()
{
  return this.replace(/\&/, '&amp;').replace(/\</, '&lt;').replace(/\>/, '&gt;');
};

// very hacky back way of reading and xml file
// once XHR is enabled within widgets this should be replaced
function loadXML(path, callback)
{
  var iframe = document.createElement('iframe');
  try {
    iframe.onload = function()
    {
      callback(this.contentDocument);
      iframe.parentNode.removeChild(iframe);
      delete iframe;
    };
    iframe.setAttribute('style','position:absolute;visibility:hidden;width:1px;height:1px;top:-100px;left:-100px;overflow:hidden;');
    iframe.setAttribute('src', path);
    document.body.appendChild(iframe);
  }
  catch(e)
  {
    callback(null);
  }
}

function loadText(path, callback)
{
  loadXML(path, function(doc)
  {
    try
    {
      callback(doc.document.documentElement.text);
    }
    catch(err)
    {
      callback(null);
    }
  });
}

Array.prototype.contains = function(obj)
{
  for (var i=this.length-1; i>=0; i--)
  {
    if (this[i]==obj) { return true; }
  }
  return false;
}

// sorts then removes duplicates from an array
Array.prototype.removeDuplicates = function()
{
  this.sort();
  for (var i=this.length-1; i>0; i--)
  {
    if (this[i]==this[i-1])
    {
      this.splice(i,1);
    }
  }
}

// this takes a number and returns something like '5 megabytes';
Number.prototype.toBytes = function()
{
  var n = this;
  var i = 0;
  while (n >= 1024 && i<3)
  {
    i++;
    n/=1024;
  }
  return n+' '+['B','KB','MB','GB'][i];
};

if (!Element.prototype.getElementsByClassName)
{
    document.getElementsByClassName = 
    Element.prototype.getElementsByClassName = function(className)
    {
      if (!className || className.match(/\s/)) throw Error("Invalid className");
      
      var arr    = [];
      var ele    = this.getElementsByTagName('*');
      var i      = ele.length;
      var regexp = new RegExp('\\b'+className+'\\b');
      
      while (i-->0)
      {
        if (regexp.test(ele[i].className))
        {
          arr[arr.length] = ele[i];
        }
      }
      return arr;
    }
}


Element.prototype.addClass = function(className)
{
  if (!this.className)
  {
    this.className = className;
    return true;
  }
  else
  {
    if( (' '+this.className+' ').indexOf(' '+className+' ') < 0 )
    {
      this.className += ' '+className;
      return true;
    }
  }
  return false;
};
Element.prototype.removeClass = function(name)
{
  var re = new RegExp(name+' ?| ?'+name);
  if( re.test(this.className) )
  {
    this.className = this.className.replace(re, '');
    return true;
  }
  return false;
};
Element.prototype.hasClass = function(className)
{
  return (' '+this.className+' ').indexOf(' '+className+' ') >= 0;
}
Element.prototype.toggleClass = function(className)
{
  return this.hasClass(className) ? this.removeClass(className) && false : this.addClass(className) || true;  
};
Element.prototype.insertAfter = function(ele1, ele2)
{
  this.insertBefore(ele1, ele2);
  this.insertBefore(ele2, ele1);
}

// returns the same string with only integers present
String.prototype.filterNumbers = function()
{
  var str='', allow = {0:1,1:1,2:1,3:1,4:1,5:1,6:1,7:1,8:1,9:1};
  for (var i=0; i<this.length; i++)
  {
    if (allow[this[i]])
    {
      str+=this[i];
    }
  }
  return str;
}

Number.prototype.toRange = function(min,max)
{
  return this<min?min:this>max?max:this;
}

Node.prototype.hasChild = function(e)
{
  while (e && e != this) e = e.parentNode;
  return !!e;
}

Node.prototype.appendTemplate = function(markup, replace)
{
  if (replace)
  {
    this.innerHTML = '';
  }
  return this.appendChild(createHTML(markup));
}

function createHTML(markup)
{
  var i=0, element=null;

  if (markup[0] instanceof Array) // markup can be an array of elements, in which case we return a documentFragment containing them
  {
    element = document.createDocumentFragment();
  }
  else
  {
    element = document.createElement(markup[0]); // otherwise we return an actual element

    for (i=1; i<markup.length; i+=2) // as long as the we keep getting key-value pairs we keep adding them
    {
      if (markup[i]==null || markup[i+1]==null || (markup[i] instanceof Array) || (markup[i+1] instanceof Array))
      {
        break;
      }
      if (typeof markup[i+1] != 'string')
      {
        element[markup[i]] = markup[i+1];
      }
      else
      {
        if (markup[i]=='class')
        {
          element.className = markup[i+1];
        }
        else
        {
          element.setAttribute(markup[i], markup[i+1]);
        }
      }
    }
  }
  
  // then add the children
  for (; i<markup.length; i++)
  {

    element.appendChild( markup[i] instanceof Node ? markup[i] : markup[i] instanceof Array ? createHTML(markup[i]) : document.createTextNode(markup[i]) );
  }
  
  return element;
};

/**
 * Wrap the preference storage so that we can store any object
 * instead of only strings.
 */

if (window.widget) (function(){
	
  var setPref = widget.setPreferenceForKey;
  var getPref = widget.preferenceForKey;
  
  widget.setPreferenceForKey = function(pref, key)
  {
    return setPref.call(widget, JSON.stringify(pref), key);
  };
  
  widget.preferenceForKey = function(key)
  {
    var pref = getPref.call(widget, key);
    if (!pref) return null;
    try {
      pref = JSON.parse('{"pref":'+pref+'}').pref;
      // using a property like this is necessary in case the
      // preference was a straight number or string (JSON.parse will
      // only parse Objects and Arrays)
    }
    catch (e)
    {
      opera.postError('Error parsing JSON preference: '+e);
      return null;
    }
    return pref;
  };
  
})();

/* global disable */
// if yes==true then disables all input elements
// if yes==false then undo the operations of above statement
// if avoid is specified then the operation 

(function(){

    var disabled = false;

    window.applyGlobalDisable = function(yes, avoid, root)
    {
      if ( !!yes == !!disabled ) return;
      
      if (!root) { root = document; }
      
      if (yes) {
        
        disabled = [];
        
        var e = [
          root.getElementsByTagName('input'),
          root.getElementsByTagName('select'),
          root.getElementsByTagName('textarea'),
          root.getElementsByTagName('button')
        ];
    
        var i,j,k;
    
        for (i=0; i<e.length; i++)
        {
          for (j=0; j<e[i].length; j++)
          {
            if (e[i][j].disabled) { continue; }
            if (avoid)
            {
              if (avoid instanceof Array)
              {
                for (k=0; k<avoid.length; k++)
                {
                  if (avoid[k].hasChild(e[i][j])) { break; }
                }
                if (k<avoid.length) { continue; }
              }
              else
              {
                if (avoid.hasChild(e[i][j])) { continue; }
              }
            }
            disabled[disabled.length] = e[i][j];
            e[i][j].disabled = true;
          }
      }
      }
      else
      {
        for (var i=disabled.length-1; i>=0; i--) disabled[i].disabled = false;
        disabled = false;
      }
    }

})();
