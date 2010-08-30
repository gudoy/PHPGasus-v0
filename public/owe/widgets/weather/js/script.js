
var DAYSOFWEEK = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

// id's of a few intereseting places. They might look weird if your font or
// editor doesn't like unicode. The urls are still perfectly valid though.
var g_placeList =
[
    {name: "Linköping", id: "Sverige/Östergötland/Linköping"},
    {name: "Oslo", id: "Norge/Oslo/Oslo/Oslo"},
    {name: "Wrocław", id: "Polen/Nedre_Schlesia/Wrocław"},
    {name: "Beijing", id: "Kina/Beijing/Beijing"},
    {name: "Chandigarh", id: "India/Chandigarh/Chandīgarh"},
    {name: "Tokyo", id: "Japan/Tokyo/Tokyo"},
    {name: "Seoul", id: "Sør-Korea/Seoul/Seoul"},
    {name: "Prague", id: "Tsjekkia/Praha/Praha"},
    {name: "Mountain view", id: "USA/California/Mountain_View"},
    {name: "San Diego", id: "USA/California/San_Diego"}
]

/**
 * @type YrWeatherSource
 */
var g_forecastSource = null;

/**
 * Event handler for when a forecast has been loaded by the
 * forecast source
 */
function onForecast(evt)
{
    var f = evt.forecast;
    
    var city = document.getElementById("cityname");
    city.textContent = f.name;
    
    var e = document.getElementById("forecastboxes");
    while (e.firstChild) { e.removeChild(e.firstChild )} // reset previous content of the div

    e.appendChild(renderMainForecast(f));
    e.appendChild(document.createElement("hr"));
    e.appendChild(renderMiniForecasts(f));
    
    var a = document.createElement("a");
    var opener = function(evt) {
        evt.preventDefault();
        widget.openURL(this.href)
        return false;
    }

    a.href=f.overviewurl || "http://www.yr.no" ;
    a.textContent = "Forecast from yr.no";
    a.addEventListener("click", opener, false);
    e.appendChild(a);

    var micro = document.getElementById("micro");
    while (micro.firstChild) { micro.removeChild(micro.firstChild )} // reset previous content of the div
    micro.appendChild(renderMicroForecast(f));

    showForecast();
}

function onError(evt)
{
    showError();
}

function onRetryButton()
{
    if (g_forecastSource)
    {
        g_forecastSource.update();
    } else {
        showPlaceList();
    }
}

/**
 * Event handler for when the forecast source starts a request
 */
function onStartRequest()
{
    showLoading(true);
}

/**
 * Event handler for when the forecast source finishes a request
 */
function onFinishRequest()
{
    showLoading(false);
}

/**
 * Event handler for when the user clicks on a place in the place list
 */
function onPlaceSelect(evt)
{
    var id = this.placeid;

    // save the place so we'll use it the next time we start
    widget.setPreferenceForKey(id, "placeid")

    selectPlace(id);
}

/**
 * Create a new foreacst source object and start fetching data from it.
 * @param {String} id The id of the place to get a forecast
 */
function selectPlace(id)
{
    // create a new forecast source and add the appropriate event listeners
    g_forecastSource = new YrWeatherSource(id);
    g_forecastSource.addEventListener("startrequest", onStartRequest);
    g_forecastSource.addEventListener("finishrequest", onFinishRequest);
    g_forecastSource.addEventListener("forecast", onForecast);
    g_forecastSource.addEventListener("error", onError);

    // let's grab the first lump of data now.
    g_forecastSource.update();
}

/**
 * Grab the forecast that is most interesting.
 * if day == today make it the next block coming up
 * if not make it as close to noon as possible
 */
function getMostRelevantForecast(day)
{
    for (var n=0, f; f=day[n]; n++)
    {
        if (f.from.getHours()>=12)
        {
            return f;
        }
    }

    return day[0];
}

/**
 * Render the list of places
 * @param {object[]} places The list of places to display This is an array with objects with a name and and id attribute
 */
function renderPlaceList(places)
{
    var rows = document.createDocumentFragment();
    for (var n=0; n<places.length; n+=2)
    {
        var row = document.createElement("tr");
        
        var td1 = document.createElement("td");
        td1.textContent = places[n].name;
        td1.placeid = places[n].id;
        td1.addEventListener("click", onPlaceSelect, false);
        row.appendChild(td1)
        
        if (places[n+1])
        {
            var td2 = document.createElement("td");
            td2.textContent = places[n+1].name;
            td2.placeid = places[n+1].id;
            td2.addEventListener("click", onPlaceSelect, false);
            row.appendChild(td2)
        }
        
        rows.appendChild(row);
    }
    return rows;
}

/**
 * Render a forecast into a container element
 * @param
 *
 */
function renderMainForecast(forecast)
{
    var day = forecast.days[0];
    var forecastDiv = document.createElement("div");
    forecastDiv.id = "mainforecastbox"
    var relevant = getMostRelevantForecast(day, true);

    // weather image
    var img = document.createElement("img");
    img.src = getWeatherImage(relevant.symbol, true);
    forecastDiv.appendChild(img);

    // day
    var h2 = document.createElement("h2");
    h2.textContent = 'Today';
    forecastDiv.appendChild(h2);

    if (day.max!=undefined && day.min!=undefined)
    {
        forecastDiv.appendChild(document.createTextNode(day.min + String.fromCharCode(0xb0) + 'C ' + "-" + ' ' + day.max + String.fromCharCode(0xb0) + 'C'));
        forecastDiv.appendChild(document.createElement("br"));
    }

    if (relevant.windSpeed)
    {
        forecastDiv.appendChild(document.createTextNode(getWindSpeedName(relevant.windSpeed)));
    }
    else
    {
        forecastDiv.appendChild(document.createTextNode("Calm"));
        
    }

    forecastDiv.appendChild(document.createElement("br"));


    if (relevant.precipitation)
    {
        forecastDiv.appendChild(document.createTextNode("Precipitation: " + relevant.precipitation + "mm"));
    }
    else
    {
        forecastDiv.appendChild(document.createTextNode("Dry"));
    }

    return forecastDiv;
}

function renderMiniForecasts(forecast)
{
    var df = document.createDocumentFragment();
    for (var n=1; n<=3 && forecast.days[n]; n++)
    {
        var block = document.createElement("div");
        block.className = "smallforecast";
        var day = forecast.days[n];
        var relevant = getMostRelevantForecast(day, true);
        var icon = document.createElement("img");
        icon.src = getWeatherImage(relevant.symbol, true);
        
        block.appendChild(icon);
        block.appendChild(document.createElement("br"));
        block.appendChild(document.createTextNode(1 == n ? 'Tomorrow' : DAYSOFWEEK[day.date.getDay()]));
        block.appendChild(document.createElement("br"));
        block.appendChild(document.createTextNode(day.min + String.fromCharCode(0xb0) + 'C ' + "-" + ' ' + day.max + String.fromCharCode(0xb0) + 'C'));
        df.appendChild(block);
    }
    return df;
}

/** 
 * Render microwidget version of todays forecast
 *
 */
function renderMicroForecast(forecast)
{
    var day = forecast.days[0];
    var forecastDiv = document.createDocumentFragment();
    var relevant = getMostRelevantForecast(day, true);

    // weather image
    var img = document.createElement("img");
    img.src = getWeatherImage(relevant.symbol, true);
    forecastDiv.appendChild(img);

    return forecastDiv;    
}

function getWindSpeedName(speed)
{
    if(!speed) { return "Calm" }
    
    if      (speed <= 0.2)  { return "Calm"}
    else if (speed <= 1.5)  { return "Light air"}
    else if (speed <= 3.3)  { return "Light breeze"}
    else if (speed <= 5.4)  { return "Gentle breeze"}
    else if (speed <= 7.9)  { return "Moderat breeze"}
    else if (speed <= 10.7) { return "Fresh breeze"}
    else if (speed <= 13.8) { return "Strong breeze"}
    else if (speed <= 17.1) { return "Moderate gale"}
    else if (speed <= 20.7) { return "Fresh gale"}
    else if (speed <= 24.4) { return "Strong gale"}
    else if (speed <= 28.4) { return "Storm"}
    else if (speed <= 32.6) { return "Violent storm"}
    else                    { return "Hurricane"}
}

/**
 * Get the appropriate symbol image based on the "base" symbol and
 * time of day
 */
function getWeatherImage(symbol, day)
{
    var str = day ? 'd' : 'n';
    return 'img/' + ({
        1: '01'+str,
        2: '02'+str,
        3: '03'+str,
        4: '04',
        5: '05'+str,
        6: '06'+str,
        7: '07'+str,
        8: '08'+str,
        9: '09',
       10: '10',
       11: '11',
       12: '12',
       13: '13',
       14: '14',
       15: '15',
       16: '16',
       17: '17',
       18: '18',
       19: '19'
    }[symbol] || '00') + '.png';
}

/**
 * Show/hide the loading indicator.
 * @param {boolean} bShow if true, show the indicator. If false, don't.
 */
function showLoading(bShow)
{
    document.getElementById('loading').style.display = bShow ? 'block' : 'none';
}

/**
 * Show the place list view
 */
function showPlaceList()
{
    var e1 = document.getElementById("placediv");
    var e2 = document.getElementById("forecastdiv");
    var e3 = document.getElementById("errordiv");
    e3.style.display = "none";
    e2.style.display = "none";
    e1.style.display = "block";
    document.getElementById('btnLocation').style.display = 'none';
}

/**
 * Show the forecast view
 */
function showForecast()
{
    var e1 = document.getElementById("placediv");
    var e2 = document.getElementById("forecastdiv");
    var e3 = document.getElementById("errordiv");
    e3.style.display = "none";
    e2.style.display = "block";
    e1.style.display = "none";
    document.getElementById('btnLocation').style.display = 'block';
}

function showError()
{
    var e1 = document.getElementById("placediv");
    var e2 = document.getElementById("forecastdiv");
    var e3 = document.getElementById("errordiv");
    e3.style.display = "block";
    e2.style.display = "none";
    e1.style.display = "none";
    document.getElementById('btnLocation').style.display = 'none';
}

/***
 * Initialize widget.
 *
 */
function init()
{
    if (screen.availHeight + screen.availWidth <= 600)
    {
        window.moveTo(0, 0);
        window.resizeTo(screen.availWidth, screen.availHeight);
        window.scrollTo(0,0);
    }
    
    // set up event handlers:
    var e = document.getElementById("placetable");
    e.appendChild(renderPlaceList(g_placeList));

    var e = document.getElementById("retrybutton");
    e.addEventListener("click", onRetryButton, false);

    // load a previously used location or use the default
    var previousLocation = widget.preferenceForKey("placeid") || g_placeList[0].id;
    selectPlace(previousLocation);
}

// start everything
window.onload = init;
