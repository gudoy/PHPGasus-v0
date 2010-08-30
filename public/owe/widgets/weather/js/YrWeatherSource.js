/**
 * Weather source class that queries yr about weather data
 *
 * Supports the following events:
 * forecast
 * error
 * startrequest
 * finishrequest
 */
function YrWeatherSource(loc) {
    EventMixin(this); // REQUIRES THE EVENT MIXIN CLASS TO BE AVAIALBE!
    this._locationId = loc;
    this._conn = null;
    this._baseUrl = "http://www.yr.no/place/";
    var self = this;

    /**
     * Try to update the forecast data
     */
    this.update = function() {
        if (this._conn) {return} // request in progress.
        this._doForecastRequest();
    }
    
    /**
     *
     * @private
     */
    this._doForecastRequest = function() {
        var url = this._baseUrl + this._locationId + "/forecast.xml";
        
        var conn = new XMLHttpRequest();
        conn.open("GET", url);
        conn.onreadystatechange = function() {
            if (conn.readyState != 4) {return}
            if (conn.status == 200) { self._onSuccess() }
            else { self._onError() }
        }
        this._dispatchEvent("startrequest");
        this._conn = conn;
        conn.send(null);
    }
    
    /**
     *
     * @private
     */
    this._onError = function() {
        delete this._conn;
        this._conn = null;
        this._dispatchEvent("finishrequest");
        this._dispatchEvent("error");
    }
    
    /**
     *
     * @private
     */
    this._onSuccess = function() {
        this._dispatchEvent("finishrequest");
        var doc = this._conn.responseXML;
        var data = this._parseResponse(doc);
        this._dispatchEvent("forecast", {forecast: data});
    }
    
    /**
     *
     * @private
     */
    this._parseResponse = function(xml) {
        var ret = {}
        var locTags = xml.getElementsByTagName("location");
        if (locTags.length) {
            var location = locTags[0];
            for (var n=0, e; e=location.childNodes[n]; n++) {
                switch (e.nodeName) {
                    case "name":
                        ret.name = e.textContent;
                        break;
                    case "type":
                        ret.type = e.textContent;
                        break;
                }
            }
        }

        var linktags = xml.getElementsByTagName("link");
        for (var n=0, e; e=linktags[n]; n++) {
            var id = e.getAttribute("id");
            if (id && id=="overview") {
                ret.overviewurl = e.getAttribute("url");
                break;
            }
        }
        
        var days = {};
        var timeEles = xml.getElementsByTagName("time");
        for (var n=0, e; e=timeEles[n]; n++) {
            var cur = this._parseForecastBlock(e);
            var key = "day_" + cur.from.getDate();
            if (!(key in days)) {
                days[key] = [];
                var d = new Date(cur.from);
                d.setHours(0);
                d.setMinutes(0);
                d.setSeconds(0);
                days[key].date = d;
            }
            days[key].push(cur);
            // fixme: make sure it's sorted by period or assume it?
        }
        
        var daylist = [];
        for (key in days) {
            daylist.push(days[key]);
        }

        daylist = daylist.sort(function(a, b) {
            if (a.date > b.date) {return 1 }
            else if (a.date < b.date) { return -1 }
            else { return 0 } }
            )
        
        ret.days = daylist;
        
        // set max and min
        for (var n=0, day; day = ret.days[n]; n++) {
            var max = -9999;
            var min = 9999;
            for (var i=0, cur; cur=day[i]; i++) {
                if (cur.temperature==null || cur.temperature == undefined) { continue }
                max = Math.max(max, cur.temperature);
                min = Math.min(min, cur.temperature);
            }
            if (max==-9999 || min==9999) { continue }
            day.max = max;
            day.min = min;
        }
        
        return ret;

    }
    
    this._parseDate = function(str) {
        var d = new Date(str);
        var timestr = str.split("T").pop();
        var parts = timestr.split(":");
        d.setHours(parseInt(parts[0]))
        d.setMinutes(parseInt(parts[1]))
        return d;
    }
    
    /**
     * Grab data from a forecast block. Return as a dict.
     * @private
     */
    this._parseForecastBlock = function(block) {
        var ret = {};
        ret.from = this._parseDate(block.getAttribute("from"));
        ret.to = this._parseDate(block.getAttribute("to"));
        ret.period = parseInt(block.getAttribute("period"));
        for (var n=0, e; e=block.childNodes[n]; n++) {
            switch (e.nodeName) {
                case "symbol":
                    ret.symbol = parseInt(e.getAttribute("number"), 10);
                    break;
                case "precipitation":
                    ret.precipitation = parseFloat(e.getAttribute("value"));
                    break;
                case "windDirection":
                    ret.windDirection = Math.round(parseFloat(e.getAttribute("deg")));
                    break;
                case "windSpeed":
                    ret.windSpeed = parseFloat(e.getAttribute("mps"));
                    break;
                case "temperature":
                    ret.temperature = parseFloat(e.getAttribute("value"));
                    break;
            }
        }
        return ret;

    }
    
}
    