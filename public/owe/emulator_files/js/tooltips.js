var tooltips = (function(){
  
  var tooltip = null;
  var timeout = 0;
  var ele = null;
  var active = null;
  
  function mouseover(e)
  {
    if (timeout)
    {
      clearTimeout(timeout);
    }
    else
    {
      if (active)
      {
        hide();
      }
    }
    active = this;
    tooltip.innerText = active.getAttribute('tooltip');
    timeout = setTimeout(showTooltip, 400);
    active.addEventListener('mouseout', tooFast, false);
    document.addEventListener('mousemove', mousemove, false);
    mousemove(e);
  }
  
  function tooFast()
  {
    clearTimeout(timeout);
    timeout = 0;
    active.removeEventListener('mouseout', tooFast, false);
    document.removeEventListener('mousemove', mousemove, false);
  }
  
  function showTooltip()
  {
    timeout = 0;
    
    tooltip.style.visibility = 'visible';
    
    active.removeEventListener('mouseout', tooFast, false);
    
    document.addEventListener('mousedown', hide, false);
    document.addEventListener('mouseout', hide, false);
    
    active.addEventListener('mouseout', hide, false);
  }
  
  function mousemove(e)
  {
    var x = e.clientX+13;
    var y = e.clientY+5;
    if (window.innerWidth - tooltip.offsetWidth < x)
    {
      x = e.clientX - tooltip.offsetWidth - 5;
    }
    if (window.innerHeight - tooltip.offsetHeight < y)
    {
      y = window.innerHeight - tooltip.offsetHeight;
    }
    tooltip.style.top = y + 'px';
    tooltip.style.left = x + 'px';
  }
  
  function hide()
  {
    if (!active) { return; }
    
    tooltip.style.visibility = 'hidden';
    
    document.removeEventListener('mousedown', hide, false);
    document.removeEventListener('mouseout', hide, false);
    
    active.removeEventListener('mouseout', hide, false);
    
    document.removeEventListener('mousemove', mousemove, false);
    
    active = null;
  }
  
  return {
    init: function()
    {
      tooltip = document.createElement('div');
      tooltip.setAttribute('id', 'tooltip');
      document.body.appendChild(tooltip);
      
      ele = document.getElementsByClassName('tooltip'); // live list
    },
    crawl: function()
    {
      // go backwards to avoid dynamic list being harmful
      for (var i=ele.length-1; i>=0; i--)
      {
        ele[i].addEventListener('mouseover', mouseover, false);
        ele[i].removeClass('tooltip');
      }
    }
  }
})();