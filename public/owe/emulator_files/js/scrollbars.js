// this function sets up the necessary dom nodes for the scroll-bar
// we have a custom scroll-bar to emphasize that the scroll bars appearing
// do not belong to the widget but to the operating system

var scrollbars = (function()
{
  function g(e){return document.getElementById(e);} // shortcut function
  
  var self;
  var SCALE = 15; // the width and height of the scrollbars
  
  var device; // alias for Control.device, set below
  
  // element references
  var e_iframe;
  var e_scroll;
  var e_h;
  var e_v;
  var e_left;
  var e_right;
  var e_up;
  var e_down;
  var e_hBar;
  var e_vBar;
  var e_corner;
  
  var scroll_h_enabled; //bool
  var scroll_v_enabled; //bool
  
  // the size of the draggable bar
  var bar_h_size; // int
  var bar_v_size; // int
  
  // the total length of the area that the draggable bar can go
  var space_h_size; // int
  var space_v_size; // int
  
  // the number of pixels the bar is from the start
  var scroll_h_off;
  var scroll_v_off;
  
  // the ratio between scrolled to the start (0) and the end (1)
  var scroll_h_ratio; // 0 <= float <= 1
  var scroll_v_ratio; // 0 <= float <= 1
  
  // token values that store the real scroll position
  var scrollLeft;
  var scrollTop;
  
  var mouseup = null;
  
  // this is the area after subtracting the scrollbars (the area the widget displays)
  var areaWidth; //px
  var areaHeight; //px
  
  // adjusts the bars and margins in the dom to reflect the scroll variables (i.e. applies the scroll)
  function applyScroll(h,v) // both h & v are boolean, if true then we apply scroll on that axis
  {
    if (!e_iframe) return;
    
    // first get variables in correct domain:
    scroll_h_off = Math.max(0, Math.min(space_h_size - bar_h_size, scroll_h_off)) || 0;
    scroll_v_off = Math.max(0, Math.min(space_v_size - bar_v_size, scroll_v_off)) || 0;
    
    scroll_h_ratio = Math.max(0, Math.min(1, scroll_h_ratio)) || 0;
    scroll_v_ratio = Math.max(0, Math.min(1, scroll_v_ratio)) || 0;
    
    if (h)
    {
      e_hBar.style.left = scroll_h_off + 'px';
      scrollLeft = Math.max(0,(scroll_h_ratio * (device.widget.width - areaWidth) ));
      e_iframe.style.marginLeft = (device.settings.chrome[3]-Math.round(scrollLeft)) + 'px';
    }
    if (v)
    {
      e_vBar.style.top = scroll_v_off + 'px';
      scrollTop = Math.max(0,(scroll_v_ratio * (device.widget.height - areaHeight) ));
      e_iframe.style.marginTop = (device.settings.chrome[0]-Math.round(scrollTop)) + 'px';
    }
  }
  
  // adjusts the variables to scroll by a certain number of pixels
  function scrollBy(x,y)
  {
    scroll_h_ratio += x / (device.widget.width - areaWidth);
    scroll_v_ratio += y / (device.widget.height - areaHeight);
    
    scroll_h_off = Math.round( scroll_h_ratio * (space_h_size-bar_h_size) );
    scroll_v_off = Math.round( scroll_v_ratio * (space_v_size-bar_v_size) );
    
    applyScroll(!!x, !!y);
  }
  
  // keeps scrolling by x,y until the mouseup event
  var scrollByContinually = (function()
  {
    var interval= 0;
    var timeout = 0;
    var ele;
    var x;
    var y;
    
    // a short wait before continually scrolling
    function wait()
    {
      timeout = 0;
      interval = setInterval(loop, 50);
    }
    
    function loop()
    {
      scrollBy(x,y);
    }
    
    function mouseup()
    {
      ele.removeClass('using');
      if (interval)
      {
        clearInterval(interval);
        interval = 0;
      }
      if (timeout)
      {
        clearTimeout(timeout);
        timeout = 0;
      }
      document.removeEventListener('mouseup', mouseup, false);
    }
    
    return function(x2,y2, ele2)
    {
      if (interval || timeout) { mouseup() }
      
      // store in larger scope
      ele = ele2;
      x = x2;
      y = y2;
      
      ele.addClass('using');
      timeout = setTimeout(wait, 300);
      scrollBy(x,y);
      document.addEventListener('mouseup', mouseup, false);
    };
  })();
  
  return self = {
    init: function()
    {
      device = Control.device; // alias
      
      // element references
      e_scroll = g('scroll');
      e_h = g('scroll-h');
      e_v = g('scroll-v');
      e_left = g('scroll-left');
      e_right = g('scroll-right');
      e_up = g('scroll-up');
      e_down = g('scroll-down');
      e_hBar = g('scroll-h-bar');
      e_vBar = g('scroll-v-bar');
      e_corner = g('scroll-corner');
      
      e_up.addEventListener('mousedown', function()
      {
        scrollByContinually(0, -20, this);
      }, false);
      e_down.addEventListener('mousedown', function()
      {
        scrollByContinually(0, +20, this);
      }, false);
      e_left.addEventListener('mousedown', function()
      {
        scrollByContinually(-20, 0, this);
      }, false);
      e_right.addEventListener('mousedown', function()
      {
        scrollByContinually(+20, 0, this);
      }, false);
      
      document.addEventListener('mousewheel', function(e)
      {
        var x = 0, y = e.detail*10;
        if (y < 0 ? (scroll_v_ratio <= 0) : (scroll_v_ratio >= 1))
        {
          x = y, y=0;
        }
        scrollBy(x,y);
        return false;
      }, false);
        
      document.addEventListener('keypress', function(e)
      {
        if (e.shiftKey || e.ctrlKey || e.altKey)
        {
          return;
        }
        var str = '';
        if (e.target)
        {
          str = (e.target.tagName||'').toLowerCase();
          if (str=='input' || str == 'select')
          {
            return;
          }
        }
        
        switch (e.keyCode)
        {
          case 33: // page up
            scrollBy(0, -areaHeight);
            break;
          case 34: // page down
            scrollBy(0, +areaHeight);
            break;
          case 35: // end
            scrollBy(0, +areaHeight);
            break;
          case 36: // home
            scrollBy(0, -areaHeight);
            break;
          case 37: // left
            scrollBy(-10, 0);
            break;
          case 38: // up
            scrollBy(0, -10);
            break;
          case 39: // right
            scrollBy(+10, 0);
            break;
          case 40: // down
            scrollBy(0, +10);
            break;
        }
      }, false);
    
      // dragging the horizontal bar
      e_hBar.addEventListener('mousedown', function(e)
      {
        this.className = 'using';
        var startX = e.clientX, startOff = scroll_h_off;
        document.onmousemove = function(e)
        {
          scroll_h_off = Math.max(0, Math.min(space_h_size - bar_h_size, startOff + (e.clientX - startX)));
          scroll_h_ratio = scroll_h_off / (space_h_size-bar_h_size);
          applyScroll(true, false);
        };
        document.onmouseup = mouseup = function()
        {
          document.onmousemove = null;
          document.onmouseup = mouseup = null;
          e_hBar.className = '';
        };
      }, false);
      
      // dragging the vertical bar
      e_vBar.addEventListener('mousedown', function(e)
      {
        this.className = 'using';
        var startY = e.clientY, startOff = scroll_v_off;
        document.onmousemove = function(e)
        {
          scroll_v_off = startOff + (e.clientY - startY);
          scroll_v_ratio = scroll_v_off / (space_v_size-bar_v_size);
          applyScroll(false, true);
        };
        document.onmouseup = mouseup = function()
        {
          document.onmousemove = null;
          document.onmouseup = mouseup = null;
          e_vBar.className = '';
        };
      }, false);
    },
    
    // call this whenever the screen size changes or the widget size changes to "fix" the scrollbar sizes
    fix: function(reset, frameWindow)
    {
      if (mouseup) mouseup(); // stop dragging events etc.
      
      e_iframe = g('iframe');
  
      if (reset)
      {
        scroll_h_off = 0;
        scroll_v_off = 0;
        scroll_h_ratio = 0; 
        scroll_v_ratio = 0;
        scrollTop = 0;
        scrollLeft = 0;
      }
      
      areaWidth = device.screen.availWidth;
      areaHeight = device.screen.availHeight;
      
      // if a scrollbar appears then the space for the other axis gets shortened
      
      scroll_h_enabled = false;
      scroll_v_enabled = false;
      
      if (device.widget.opened)
      {
        
        if (areaWidth < device.widget.width)
        {
          scroll_h_enabled = true;
          areaHeight -= SCALE;
        }
        
        if (areaHeight < device.widget.height)
        {
          scroll_v_enabled = true;
          areaWidth -= SCALE;
        }
        // try 'h' again because 'v' might have introduced it when it shortened the space
        if (scroll_v_enabled && !scroll_h_enabled && areaWidth < device.widget.width)
        {
          scroll_h_enabled = true;
          areaHeight -= SCALE;
        }
      }
      
      e_corner.style.display = scroll_h_enabled && scroll_v_enabled ? 'block' : 'none';
      
      space_h_size = areaWidth - SCALE*2;
      space_v_size = areaHeight - SCALE*2;
  
      bar_h_size = Math.round(space_h_size * areaWidth / device.widget.width);
      bar_v_size = Math.round(space_v_size * areaHeight / device.widget.height);
      
      if (scroll_h_enabled)
      {
        e_h.style.width = areaWidth + 'px';
        e_h.style.right  = device.settings.chrome[1]+'px';
        e_h.style.bottom = device.settings.chrome[2]+'px';
        e_h.style.left   = device.settings.chrome[3]+'px';
        scroll_h_ratio = scrollLeft / (device.widget.width - areaWidth);
        scroll_h_off = scroll_h_ratio * (space_h_size-bar_h_size);
        e_h.style.display = 'block';
        e_hBar.style.width = bar_h_size + 'px';
      }
      else
      {
        e_h.style.display = 'none';
        scroll_h_off = scroll_h_ratio = 0;
      }
  
      if (scroll_v_enabled) // scrollbars are not needed
      {
        e_v.style.height = areaHeight+'px';
        e_v.style.top    = device.settings.chrome[0]+'px';
        e_v.style.right  = device.settings.chrome[1]+'px';
        e_v.style.bottom = device.settings.chrome[2]+'px';
        scroll_v_ratio = scrollTop / (device.widget.height - areaHeight);
        scroll_v_off = scroll_v_ratio * (space_v_size-bar_v_size);
        e_v.style.display = 'block';
        e_vBar.style.height = bar_v_size + 'px';
      }
      else
      {
        e_v.style.display = 'none';
        scroll_v_off = scroll_v_ratio = 0;
      }
      
      applyScroll(true, true);
      
    }
  };
})();