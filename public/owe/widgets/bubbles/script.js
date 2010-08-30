/*
 *  $ alias of document.getElementById
 */
function $(id)
{
    return document.getElementById(id);
}
/*
 *  Function bind
 */
Function.prototype.bind = function( object )
{
    var method = this;
    return function ()
    {
        return method.apply( object, arguments );
    }
}

/*
 *  on resize, fix the window size
 */
widget.addEventListener
(
    'resolution',
    function(/* resolution callback */)
    {


    },
    false
)
/*
  * Event handler for when the widget mode changes
  */
widget.addEventListener
(
    "widgetmodechange",
    function(/* widgetmodechange callback */)
    {
        if (this.widgetMode=="docked")
        {
            document.getElementById("dock").style.display = "block";
            document.getElementById("widgetWrapper").style.display = "none";
        }
        else
        {
            document.getElementById("dock").style.display = "none";
            document.getElementById("widgetWrapper").style.display = "block";
        }
    },
    false
)
/*
 *  on load, instanciate a new BubbleGame
 */
window.addEventListener
(
    'load',
    function(/* load callback */)
    {
        if (screen.availHeight + screen.availWidth <= 800)
        {
            window.moveTo(0, 0);
            window.resizeTo(screen.availWidth, screen.availHeight);
            window.scrollTo(0,0);
        }
        if( 'undefined'==typeof(widget) )widget = {setPreferenceForKey:function(){},preferenceForKey:function(){}};
        new BubbleGame( 'gameArea', 'newGame', 'score', 'hiScore' );
    },
    false
)

/*
 *  BubbleGame
 */
function BubbleGame( gameAreaId, newGameId, scoreId, hiScoreId )
{
    var that        = this,
        width       = 0,
        height      = 0,
        tiles       = 0,
        score       = 0,
        map         = [],
        colors      = ['#e00','#fa0','#766','#0cf','#a0f','#3b0'];

    /*
     *  displayScore
     */
    function displayScore()
    {
        hScore.textContent      = score;
        hHiScore.textContent    = hiScore;
        $('dock').textContent   = "Score: " + score;
    }

    /*
     *  newGame
     */
    this.newGame = function()
    {
        score       = 0;
        width       = 0;
        height      = 0;
        map.length  = 0;
        tiles       = 0;

        displayScore();

        hGameArea.className = '';
        hGameArea.innerHTML = '';

        hGameArea.addEventListener( 'mouseover',    hoverTile.bind(this), false );
        hGameArea.addEventListener( 'mouseout',     hoverTile.bind(this), false );

        var isFull  = false;
        while( !isFull )
        {
            var newTile = document.createElement('span'),
                color   = colors[Math.random()*colors.length|0];

            map.push( color );
            newTile.setAttribute( 'style', 'background-color:'+ color );
            newTile.setAttribute( 'data-index', tiles );
            hGameArea.appendChild( newTile );

            newTile.addEventListener( 'click', clickTile.bind(this), false );

            //newTile.onclick= clickTile.bind(this);
            if( !newTile.offsetTop )
            {
                width++;
            }

            isFull = !(++tiles%width) && (newTile.offsetTop+newTile.offsetHeight*2)>=gameArea.clientHeight;
        }
        height = hGameArea.childNodes.length/width;
    }

    /*
     *  floodFill
     */
    function floodFill( x,y )
    {
        var color       = map[ x+y*width ],
            floodMap    = new Array(map.length),
            n           = 0;

        if( color  )
        {
            n = (function( x, y )
            {
                var i = x+y*width;
                if( map[i]==color && !floodMap[i] )
                {
                    //  mark tile
                    floodMap[i] = 1;

                    //  recurse
                    var n = 1;
                    if( x>0 )       n += arguments.callee( x-1, y );
                    if( x<width-1 ) n += arguments.callee( x+1, y );
                    if( y>0 )       n += arguments.callee( x, y-1 );
                    if( y<height-1) n += arguments.callee( x, y+1 );
                    return n;
                }
                return 0;
            })( x, y );
        }

        return {'map':floodMap,'tiles':n}
    }

    /*
     *  hoverTile
     */
    function hoverTile(event)
    {
        var src     = event.srcElement;
        if( src==event.currentTarget || event.type=='mouseout' )
        {
            var hoverMap    = new Array(map.length);
        }
        else
        {
            var index   = src.getAttribute('data-index')|0,
                color   = map[index],
                x       = index%width,
                y       = index/width|0,
                hoverMap= floodFill( x, y ).map;
        }

        //  update tiles' display
        var node    = hGameArea.firstChild,
            i       = 0;
        while( node )
        {
            var className   = hoverMap[i++]?'hover':'';
            if( node.className != className )
            {
                node.className = className;
            }
            node = node.nextSibling;
        }
    }

    /*
     *  clickTile
     */
    function clickTile(event)
    {
        var src     = event.srcElement,
            index   = src.getAttribute('data-index')|0,
            x       = index%width,
            y       = index/width|0,
            flood   = floodFill( x, y );


        //  a single|empty tile -> exit
        if( flood.tiles<2 )
        {
            return true;
        }

        //  clear tiles
        for( var i=flood.map.length;i--; )
        {
            if( flood.map[i] )
                map[i]  = 0;
        }

        //  decrease tiles count
        tiles -= flood.tiles;

        //  update score
        score+= (1+flood.tiles)*flood.tiles;


        if( tiles )
        {
            //  waterfall
            var cols=[]
            for( var x=0; x<width; x++ )
            {
                var i = map.length-width+x,
                    n = height-1;
                while( i>=width )
                {
                    var j = i-width;
                    if( !map[i] )
                    {
                        while( j>=0 && !map[j])j-=width;
                        if( j>=0 )
                        {
                            map[i] = map[j];
                            map[j] = 0;
                        }
                    }
                    if( !map[i] )
                    {
                        n--;
                    }
                    i -= width;
                }
                cols.push(n)
            }

            //  collapse empty columns
            for( var xx=0; xx<width*width; xx++ )
            {
                var x = xx%(width-1);
                if( cols[x] )
                    continue;
                var i = x;
                while( i<map.length )
                {
                    map[i]  = map[i+1];
                    map[i+1]= 0;
                    i       += width;
                }
                cols[x]     = cols[x+1];
                cols[x+1]   = 0;
            }

            //  FAIL ?
            var fail    = true,
                i       = map.length;
            while( fail && i-- )
            {
                if( color = map[i] )
                {
                    var x = i%width,
                        y = i-x;

                    if( fail && x>0 && color==map[i-1] )            fail = false;
                    if( fail && x<width-1 && color==map[i+1] )      fail = false;
                    if( fail && y>0 && color==map[i-width] )        fail = false;
                    if( fail && y<height-1 && color==map[i+width] ) fail = false;
                }
            }
            if( fail )
            {
                hGameArea.className = 'fail';
            }
        }

        //  update tiles' display
        var node    = hGameArea.firstChild,
            i       = 0;
        while( node )
        {
            var color = map[i++];
            if( color )
            {
                color = 'background-color:'+ color;
                if( node.getAttribute( 'style' )!=color )
                {
                    node.setAttribute( 'style', color );
                }
            }
            else if( node.hasAttribute( 'style' ) )
            {
                node.removeAttribute( 'style' );
            }
            node.className  = '';
            node = node.nextSibling;
        }

        //  WIN!?
        if( !tiles )
        {
            score   += 100; //  ULTRA WIN!
            hGameArea.className = 'win';
        }

        //  update score display and hiScore
        displayScore();

        if( isGameOver && score>hiScore )
        {
            widget.setPreferenceForKey( hiScore = score, 'hiScore' );
        }

        return true;
    }


    //  initialize
    var hScore      = $(scoreId),
        hGameArea   = $(gameAreaId),
        hHiScore    = $(hiScoreId);

    hiScore = widget.preferenceForKey('hiScore')||0;

    $(newGameId).addEventListener
    (
        'click',
        this.newGame.bind(this),
        false
    );


    this.newGame();
}
