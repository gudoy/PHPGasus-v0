/*
/* Essentials
*/
if(opera) if(!opera.postError) opera.postError = function(){} // In some weird device doesnt support opera.postError
function $(ele) { return document.getElementById(ele); } // shortcut for gebi()
/*
/* Globals
*/
var g_micro_debug = false; // set to true if you want a button to help switch between modes
var g_micro_manual_size_control = false; // set to false if resizeTo is managed by device
var g_widgetModeId = "container"; //check your html
var g_microModeId = "micro"; //check your html
var g_startAs = "widget"; // 'docked' or anything else, preferably 'widget'
var g_onModeChange; //called when the mode changes. helps when widgets need to update something on change.
/*
/* Mode switcher
*/
function g_switchMode(event, mode){
	if(event)
	{
		mode = event.widgetMode;
	}
	if(!mode) // check if a mode is provided, if not, use widget.mode
	{
		mode = widget.mode;
	}
	if(g_micro_debug)  { opera.postError("changing mode to " + mode); }
	if(mode && 'docked' == mode) // The widget will go into docked mode only if he mode is 'docked'
	{
		g_micro_manual_size_control ? g_setSize(52, 52):'';
		$(g_widgetModeId).style.display = "none";
		$(g_microModeId).style.display = "block";
	} else {
		g_micro_manual_size_control ? g_setSize(240, 264, false):'';
		$(g_widgetModeId).style.display = "block";
		$(g_microModeId).style.display = "none";
	}
	if(g_onModeChange)
	{
		g_onModeChange();
		if(g_micro_debug) opera.postError("calling callback");
	}
}
/*
/* Resizer (not required for devices that dont support resizeTo and moveTo)
*/
function g_setSize(width, height, fullscreen)
{
	if(fullscreen) //if forced to fullscreen, ignore the height and width values
	{
		window.moveTo(0, 0);
		window.resizeTo(screen.availWidth, screen.availHeight);
	} else { //resize to the given height and width values
		window.resizeTo(width, height);
	}
}
/*
/* Adding the WidgetModeChangeEvent
*/
window.addEventListener("load", function()
	{
		if(!$(g_widgetModeId) || !$(g_microModeId)) return; // check if the elements concernced exist
		g_switchMode(false, g_startAs); // change mode to the recommended startAs mode
		if(widget.addEventListener) {
			widget.addEventListener("widgetmodechange", g_switchMode, false); //add the modechange event listener
		}
		if(g_micro_debug == true) //on for debugging
		{
			var switcher = document.createElement("button"); //create a button for switching to docked mode
			switcher.onclick = function(){g_switchMode(false, 'docked')};
			switcher.style.background = "grey";
			switcher.style.border = "solid 1px white";
			switcher.style.position = "absolute";
			switcher.style.top = "3px";
			switcher.style.left = "3px";
			switcher.style.width = "40px";
			switcher.style.height = "20px";
			switcher.textContent = "Dock";
			$(g_widgetModeId).appendChild(switcher);
			$(g_microModeId).onclick = function(){g_switchMode(false, 'widget');}; //switch to widget mode if the widget is clicked while in micro mode.
		}
	}, false);
