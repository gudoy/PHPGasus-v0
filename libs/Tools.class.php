<?php

class Tools
{
    
    static function deaccentize($str)
    {
        $charsTable = array(
            'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
            'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
            'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
            'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
            'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
        );
        return strtr($str,$charsTable);
    }
    
    
    static function generateUniqueID($options = array())
    {
        // Get passed options or default them
        $o          = array_merge(array(
            'length'            => 8,
            //'check'           => true,
            'resource'          => null,
            'field'             => null,
            'preventNumsOnly'   => true,
            'preventAlphaOnly'  => false, // TODO
        ), $options);
        
        $alpha      = 'abcdefghjkmnpqrstuvwxyz';    // all letters except i,o,l (prevent reading confusions)
        $num        = '23456789';                   // all numerics except 1 (prevent reading confusions)
        $wref       = '';
        while ( strlen($wref) < $o['length'] )
        {
            $wref .= mt_rand(1,2) === 1 ? $alpha[mt_rand(1, 23)-1] : $num[mt_rand(0, 7)];
        }
        
        // Prevents id having numerics only to prevent conflict with ids in database on "smart searchs" ( retrieve(array('by' => 'id,uid', 'value' => $value)) 
        if ( $o['preventNumsOnly'] && is_numeric($wref) ) { Tools::generateUniqueID($o); }
        
        // TODO: check if resource & resource field exist in datamodel
        if ( !empty($o['resource']) && !empty($o['resource'])  )
        {
            $cName      = 'C' . ucfirst($o['resource']);
            $ctrl       = new $cName();
            $isUnique   = $ctrl->retrieve(array('by' => $o['field'], 'values' => $wref, 'mode' => 'count'));
            
            if ( !empty($isUnique) || ($o['preventNumsOnly'] && is_numeric($wref)) ) { Tools::generateUniqueID($o); }
        }
        
        return $wref;
    }


    static function getCurrentURL()
    {
        return 'http' . ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' :'' ) . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    }


    static function getURLParams($url = '')
    {
        $url    = !empty($url) ? Tools::getCurrentURL() : $url;
        $params = array();
    
        if ( empty($url) ){ return $params; }
        
        $urlParts   = parse_url($url);
        $query      = !empty($urlParts['query']) ? $urlParts['query'] : '';
        
        foreach ( (array) explode('&', $query) as $item)
        {
            $parts              = explode('=', $item);
            $params[$parts[0]]  = !empty($parts[1]) ? $parts[1] : null; 
        }
        
        return $params;
    }


    /* 
     * This function gets, in an URL string, the value of the param given in the function call
     * @author Guyllaume Doyer guyllaume@clicmobile.com
     * @return {String|Boolean} The value if found, otherwise false
     */
    // TODO: refactor using parse_str() ???
    static function getURLParamValue($requestedURL, $requestedParamName)
    {
        // Get start position of the param from the ?
        $markP          = strpos($requestedURL, "?");
        $requestedURL   = substr($requestedURL, $markP, strlen($requestedURL));
        $pos            = strpos($requestedURL, $requestedParamName);
        
        if ($pos != -1 && $requestedParamName != "")
        {
            // Truncate the string from this position to its end
            $tmp = substr($requestedURL, $pos);
            
            // Get end position of the param value
            if      ( strpos($tmp, "&amp;") !== false ) { $end_pos = strpos($tmp, "&amp;"); } // case where there are others params after, separated by a "&amp;"
            else if ( strpos($tmp, "&") !== false )     { $end_pos = strpos($tmp, "&"); } // case where there are others params after, separated by a "&"
            else if ( strpos($tmp, "#") !== false )     { $end_pos = strpos($tmp, "#"); } // case where there are others params after, separated by a "#"
            else                                        { $end_pos = strlen($tmp); } // case where there are no others params after
            
            // Truncate the string from 0 to the end of the param value
            $requestedParamValue = substr($tmp, strlen($requestedParamName) + 1, $end_pos);
            
            return $requestedParamValue;
        }
        else { return false; }
    }

	static function plural($singular)
	{
		return self::pluralize($singular);
	}
    
    static function pluralize($singular)
    {
        $len = strlen($singular);
        $plu = $singular;           // Default
        
        if      ( $len >= 3 && substr($singular, -2) === 'us' )     { $plu = preg_replace('/(.*)us/','$1uses', $singular); }
        else if ( $len >= 3 && substr($singular, -2) === 'ss' )     { $plu = preg_replace('/(.*)ss/','$1ses', $singular); }
        else if ( $len >= 3 && $singular[$len-1] === 'h' )          { $plu = preg_replace('/(.*)h/','$1hes', $singular); }
        else if ( $len >= 3 && $singular[$len-1] === 'y' )          { $plu = preg_replace('/(.*)y/','$1ies', $singular); }
        else if ( $len >= 3 && $singular[$len-1] === 'o' )          { $plu = preg_replace('/(.*)o/','$1oes', $singular); }
        else if ( $len >= 3 && $singular[$len-1] === 'f' )          { $plu = preg_replace('/(.*)f/','$1ves', $singular); }
        else if ( $len >= 3 && substr($singular, -2) === 'um' )     { $plu = preg_replace('/(.*)um/','$a', $singular); }
        else if ( $len >= 2 )                                       { $plu = $singular . 's'; }
        
        return $plu;
    }


    /**
     * Remove params (and theirs values) from a string (or url)
     * 
     * @param string|array $paramNames name of a param or array of params name
     * @param string $replaceIn a string or URL in valid query format (param1=value1&param2=value2...)
     * @return string cleaned string
     */
    static function removeQueryParams($paramNames, $string)
    {
        $cleaned = $string;
        
        foreach ((array)$paramNames as $paramName)
        {
            $cleaned = preg_replace('/(.*)[&]$/', '$1', preg_replace('/(.*)' . $paramName . '[=|%3D|%3d](.*)(&|$)/U','$1', $cleaned));
        }
        
        return $cleaned;
    }
    
    
	static function singular($plural)
	{
		return self::singularize($plural);
	}
	
	
    static function singularize($plural)
    {
        $len    = strlen($plural);
        $sing   = $plural;          // Default
        
        if      ( $len >= 5 && substr($plural, -4) === 'uses' )     { $sing = preg_replace('/(.*)uses/','$1us', $plural); }
        else if ( $len >= 4 && substr($plural, -3) === 'ses' )      { $sing = preg_replace('/(.*)ses/','$1ss', $plural); }
        else if ( $len >= 4 && substr($plural, -3) === 'hes' )      { $sing = preg_replace('/(.*)hes/','$1h', $plural); }
        else if ( $len >= 4 && substr($plural, -3) === 'ies' )      { $sing = preg_replace('/(.*)ies$/','$1y', $plural); }
        else if ( $len >= 4 && substr($plural, -3) === 'oes' )      { $sing = preg_replace('/(.*)oes$/','$1o', $plural); }
        else if ( $len >= 4 && substr($plural, -3) === 'ves' )      { $sing = preg_replace('/(.*)ves$/','$1f', $plural); }
        else if ( $len >= 2 && $plural[$len-1] === 'a' )            { $sing = preg_replace('/(.*)a$/','$1um', $plural); }
        else if ( $len >= 2 && $plural[$len-1] === 's' )            { $sing = preg_replace('/(.*)s$/','$1', $plural); }
        
        return $sing;
    }


	static function slug($string)
	{
		return self::slugify($string);
	}
	

    // function found on http://forum.webrankinfo.com/fonctions-pour-creer-slug-seo-friendly-url-t99376.html
    // TODO: rename to humanize() ???? cf: http://codeigniter.com/user_guide/helpers/inflector_helper.html
    static function slugify($string)
    {
        // remplace les caractères accentués par leur version non accentuée
        //$id = strtr($string,'ŠŽšžŸÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåçèéêëìíîïñòóôõöøùúûüýÿ', 'SZszYAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy');
        $id = Tools::deaccentize($string);

        // remplace les caractères non standards
        $id = preg_replace(
                array('`^[^A-Za-z0-9]+`', '`[^A-Za-z0-9]+$`', '`[^A-Za-z0-9]+`' ),
                array('','','-'),
            $id);

        return $id;
    }
    
    
    static function strtolower_utf8($string)
    {
        $convert_to = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u",
            "v", "w", "x", "y", "z", "à", "á", "â", "ã", "ä", "å", "æ", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï",
            "ð", "ñ", "ò", "ó", "ô", "õ", "ö", "ø", "ù", "ú", "û", "ü", "ý", "а", "б", "в", "г", "д", "е", "ё", "ж",
            "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы",
            "ь", "э", "ю", "я"
        );
        $convert_from = array(
            "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U",
            "V", "W", "X", "Y", "Z", "À", "Á", "Â", "Ã", "Ä", "Å", "Æ", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï",
            "Ð", "Ñ", "Ò", "Ó", "Ô", "Õ", "Ö", "Ø", "Ù", "Ú", "Û", "Ü", "Ý", "А", "Б", "В", "Г", "Д", "Е", "Ё", "Ж",
            "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ъ",
            "Ь", "Э", "Ю", "Я"
        );
        
        return str_replace($convert_from, $convert_to, $string); 
    }
    
    
    static function toArray($value)
    {
        switch(gettype($value))
        {
            case 'array':       $value; break;
            case 'string':      $value = preg_split("/,+\s*/", $value); break;
            case 'object':      $value = (array) $value; break;
            case 'integer': 
            case 'double':
            case 'boolean':     $value = array($value); break;
            case 'null':        $value = array(); break;
        }
                
        //return is_array($value) ? $value : preg_split("/,+\s*/", (string) $value);
        return $value;
    }


    static function XML2Array($xml, $recursive = false, $options = array())
    {
        $o = array_merge(array(
            'type' => 'xml',
            'parent' => null,
        ), $options);
        
        $array                = !$recursive ? (array) simplexml_load_file($xml) : $xml;
        //$array              = !$recursive ? (array) simplexml_load_file($xml, 'SimpleXMLElement', LIBXML_COMPACT) : $xml;
        $fixTextNodesAttr     = defined('_XML2ARRAY_FIX_TEXT_NODES_ATTRIBUTES') && _XML2ARRAY_FIX_TEXT_NODES_ATTRIBUTES;
        $data                 = array();
        
        foreach ($array as $propName => $propVal)
        {
            if ( $o['type'] === 'rss' && $propName === 'description' )
            {
                $propVal = (string) $propVal;
            }
            
            $type               = in_array(gettype($propVal), array('object','array')) ? 'multi' : 'simple';
            
            # Fix for text nodes having attributes that are ignored
            // If the element is an object
            if ( $fixTextNodesAttr && is_object($propVal) )
            {
                $fixed = array();
            
                // Loop over its childens    
                foreach ( $propVal as $k => $v )
                {
                    // Only handle text nodes which have both @attributes and a 0 indexed property        
                    if ( ($v = (array) $v) && isset($v['@attributes']) && isset($v[0]) )
                    {
                        $fixed[$k][] = array('@attributes' => $v['@attributes'], 'text' => $v[0]);
                    }
                }
            
                $propVal = array_merge((array)$propVal, $fixed);
            } 
            # End of the fix
            
            $data[$propName]    = $type === 'multi' ? self::XML2Array((array) $propVal, true, $o + array('parent' => $propVal)) : $propVal;
        }
        
        return $data;
    }
}

?>