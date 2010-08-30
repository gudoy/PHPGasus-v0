(function(){

    /**
     * Media is a constructor which creates a new
     * object with some default properties
     * @constructor
     */
    
    window.Media = function()
    {
        this.type = 'screen';
        this.features = {
            'width': 100,
            'height': 100,
            'device-width': 100,
            'device-height': 100,
            'device-aspect-ratio': 1,
            'color': 8,
            'color-index': 0,
            'monochrome': 0,
            'resolution': 96.4, // dpi
            'scan': 'progressive', // string "progressive" or "interlace"
            'grid': 0, // integer (0 or 1)
            '-o-widget-mode': 'widget' // string: 'widget', 'docked', 'fullscreen', 'application'
        };
    };
    
    /*
      this regexp will match:
      (aaaa)
      (aaaa: bbbb)
      (min-aaaa: bbbb)
      (max-aaaa: bbbb)
      ...
    */
    var regexp_mediaFeature = /\(\s*(min-|max-)?([a-z\-]+)(\s*:\s*([a-z0-9\.\-\/]+))?\s*\)/;
    
    Media.prototype = {
        
        /**
         * Evaluates a media-query for the media object
         *
         * @param {String} str The media-query-text to match
         * @return {Boolean} Whether or not the query passed
         */
        
        query : function(str)
        {
            if (!str) { return true; }
            
            // media queries are case insensitive
            str = str.toLowerCase();
            // remove leading/trailing whitespace
            str = str.replace(/^\s+/,'').replace(/\s+$/,'');
            
            // counters
            var i = 0;
            var j = 0;
            
            // and split at commas (and surrounding whitespace)
            var query_list = str.split(/\s*,\s*/);
            var expression_list = null;
            var expression = "";
            var negate = false; // whether or not we found a 'not' keyword
            
            var feature = '';
            var value = '';
            var valueType = '';
            
            var res = null; // results of reg
            
            // loop through each comma-separated query (the OR selector)
            for ( i = 0; i < query_list.length; i++ )
            {
                str = query_list[i];
                
                // look for leading expression modifiers: (not and only)
                if ( str.indexOf('not ')==0 )
                {
                    negate = true;
                    str = str.substr(4,str.length-4);
                }
                else
                {
                    negate = false;
                    if ( str.indexOf('only ')==0 )
                    {
                        str = str.substr(5, str.length-5);
                    }
                }
                
                // split by 'and' (plus excess whitespace)
                expression_list = str.split(/\s*\band\b\s*/g);
                
                // if we complete this loop, then success, the query matches
                for (j = 0; j < expression_list.length; j++)
                {
                    expression = expression_list[j];
                    
                    // media-type match?
                    if ( expression == 'all' || expression == this.type )
                    {
                        continue;
                    }
                    
                    // possible media-feature match?
                    res = regexp_mediaFeature.exec(expression);
                    
                    if ( res && this.features.hasOwnProperty(res[2]) )
                    {
                        feature = res[2];
                        value = res[4];
                        
                        if (feature == '-o-widget-mode')
                        {
                            if (!value || this.features[feature] == value)
                            {
                                // having no value corresponds to a check
                                // for 'widgetMode' support, which we do
                                continue;
                            }
                        }
                        
                        // res[4] is the value of the feature queried (not necessarily specified)
                        if (value)
                        {
                            
                            value = resolveValue(feature, value, this);
                            
                            // is the value sensible?
                            if ( isNaN(value) )
                            {
                                break;
                            }
                            
                            // features that only apply to specific media types:
                            if (feature == 'scan' && this.type != 'tv')
                            {
                                break;
                            }
                            
                            // res[1] is the prefix to the feature, can be 'min-' or 'max-' or null
                            if (res[1])
                            {
                                // features that cannot have min/max prefixes should evaluate false:
                                if (feature == 'scan' || feature == 'grid')
                                {
                                    break;
                                }
                                
                                if ( res[1]=='min-')
                                {
                                    if ( value <= this.features[feature] )
                                    {
                                        continue;
                                    }
                                }
                                else // must be 'max-'
                                {
                                    if (  value >= this.features[feature] )
                                    {
                                        continue;
                                    }                            
                                }
                            }
                            else // must exactly match the value
                            {
                                if ( value == this.features[feature] )
                                {
                                    continue;
                                }
                            }
                        }
                        else // no value specified, check if >=0 I suppose?
                        {
                            if (this.features[feature])
                            {
                                continue;
                            }
                        }
                    }
                    
                    // we affirmed nothing, so expression failed
                    break;
    
                }
                
                // nothing wrong with the expression list?
                if ( negate != (j==expression_list.length) )
                {
                    return true;
                }
                
            }
            
            // no successful queries
            return false;
        },
        
        /**
         * Evaluates a the media object against all document's stylesheets
         *
         * @param {Object} document The document object to crawl
         */
        
        matchDocument : function(document)
        {
            
            var i = 0;
            var sheets = []; // stack
            var sheet = null;
            var item = null;
            var str = "";
            
            // make a duplicate as the sheets array can grow later
            for (i=0; i<document.styleSheets.length; i++)
            {
                sheets[i] = document.styleSheets.item(i);
            }
            
    
            while ( (sheet = sheets.pop() ) )
            {
                item = sheet.media;
                
                // we do this to make sure not to cause unnecessary alterations (which may cause reflows)
                if (!item.oldMediaText) { item.oldMediaText = item.mediaText; }
                str = this.query(item.oldMediaText) ? 'all' : 'not all';
                if ( str != item.mediaText ) { item.mediaText = str; }
                
                for (i=0; i<sheet.cssRules.length; i++)
                {
                    item = sheet.cssRules.item(i);
                    switch (item.type)
                    {
                        case CSSRule.IMPORT_RULE : // 3
                            sheets.push(item.styleSheet);
                            // don't break, continue to next case
                            
                        case CSSRule.MEDIA_RULE : // 4
                            if (!item.media.oldMediaText) { item.media.oldMediaText = item.media.mediaText; }
                            str = this.query(item.media.oldMediaText) ? 'all' : 'not all';
                            if ( str != item.media.mediaText ) { item.media.mediaText = str; }
                            break;
                    }
                }
            }
        }
    };
    
    /*
     this regexp looks for dddduu
     where dd are dddd is a number (with a possible decimal)
     and uu is a unit string
    */
    var regexp_resolveUnit = /^(\d+(\.\d+)?)(\D*)$/;
    /*
     This looks for
     aaa/bbb
     Where aaa and bbb are integers of anylength
    */
    var regexp_resolveRatio = /^(\d+)\/(\d+)$/;

    function resolveValue(feature, str, self)
    {
        var res = null; // res means result, result of regexp
        var value = 0;
        
        switch (feature)
        {
            // <integer> / <integer>
            case 'device-aspect-ratio' :
                
                res = regexp_resolveRatio.exec(str);
                if (!res) { break; }
                return parseInt(res[1]) / parseInt(res[2]);
            
            // <length>
            case 'width':
            case 'height':
            case 'device-width':
            case 'device-height':
                // return the number of pixels the length represents
                
                res = regexp_resolveUnit.exec(str);
                
                if (!res) { break; }
                
                value = parseFloat(res[1]);
                
                switch (res[3]) // check the unit-type
                {
                    // pixels
                    case 'px' : return value;
                        
                    // em, NEED BETTER SOLUTION
                    case 'em' : return value*16;
                        
                    // ex, NEED BETTER SOLUTION
                    case 'ex' : return value*16;
                        
                    // inches
                    case 'in' : return value*self.features.resolution;
                        
                    // points (1/72 of an inch)
                    case 'pt' : return value*self.features.resolution/72;
                        
                    // picas (12 points)
                    case 'pc' : return value*self.features.resolution/6;
                        
                    // centimetres (1/2.54 of an inch)
                    case 'cm' : return value*self.features.resolution/2.54;
                        
                    // millimetres (1/10 of a centimetre)
                    case 'mm' : return value*self.features.resolution/25.4;
                }
                break;
            
            // <integer>
            case 'color':
            case 'color-index':
            case 'monochrome':
            case 'grid':
                if (/\D/.test(str)) // non-integer values cause failure
                {
                    break;
                }
                else
                {
                    return parseInt(str);
                }
            
            case 'resolution':
                res = regexp_resolveUnit.exec(str);
                if (!res) { break; }
                
                value = parseFloat(res[1]);
                switch (res[3]) // check the unit-type
                {
                    case 'dpi'  : return value;
                    case 'dpcm' : return value*2.54; // conversion from in to cm
                }
        }
        
        return NaN;
    }
    
})();