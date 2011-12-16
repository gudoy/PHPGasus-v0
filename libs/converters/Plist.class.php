<?php
/**
* 
* PHP Plist Parser Class
* Version 0.4.1
*
* Copyright (C) 2006 Matsuda Shota
* http://sgssweb.com/
* admin@sgssweb.com
*
* ------------------------------------------------------------------------
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
* ------------------------------------------------------------------------
*
*/

/*
2006-9-21 (changes by Jim Myhrberg <zynode@gmail.com>)
	- fixed integer handling
	- fixed key value errors in array to xml conversion
	- added human readable xml output option for array to xml conversion
2006-5-7
	- fixed the process of formatDate() method
	- added some getter/setter methods
2005-10-6
	- fixed return behavior of parse() method
2005-4-23
	- Recording note started.
*/


class Plist
{
	// string url
	public $url; 
	// DomDocument doc
	public $doc; 
	// string dateFormat
	public $dateFormat; 
	// integer timeZone
	public $timeZone; 
	// object dataArray
	public $dataArray; 
	
	// Plist()
	// Plist(string url)
	// Plist(string url, string dateFormat)
	// Plist(string url, string dateFormat, int timeZone)
	function Plist($url = "", $dateFormat = null, $timeZone = 0) {
		$this->setURL($url);
		$this->setDateFormat($dateFormat);
		$this->setTimeZone($timeZone);
	}
	
	// void parse();
	// void parse(string exp);
	function parse($exp = null) {
		if ($exp) {
			$this->_parseExpression($exp);
		}
		else {
			$this->_parseFile($this->url);
		}
	}
	
	// string formatDate(string date, string format)
	// string formatDate(string date, string format, int offsetHour)
	// string formatDate(string date, string format, int offsetHour, int offsetMinute)
	function formatDate($date, $format = null, $offsetHour = 0, $offsetMinute = 0) {
		if (!$format) {
			return $date;
		}
		
		// split into day notation and time notation by "T"
		$d = array();
		preg_match_all('/([\d.,:\-W]+)(?:T([\d.,:\-+WZ]*))?/', $date, $d);
		$dayNotation = $d[1][0];
		$timeNotation = $d[2][0];
		
		// extract year, month and day
		$days = array();
		preg_match_all('/^(\d{2})(?:\-?(\d{2}))?(?:\-?(\d{2}))?(?:\-?(\d{2}))?$/', $dayNotation, $days);
		if (count($days[0]) > 0) {
			$year = $days[1][0] * 100 + $days[2][0];
			$month = $days[3][0] + 0;
			$day = $days[4][0] + 0;
		}
		else {
			$year = $month = $day = 0;
		}
		
		// extract hour, minute andd second
		$times = array();
		preg_match_all('/^(\d{2})(?:[,.](\d+)(?=[+\-Z]|$))?(?:\:?(\d{2})(?:[,.](\d+)(?=[+\-Z]|$))?)?(?:\:?(\d{2})(?:[,.](\d+)(?=[+\-Z]|$))?)?(.*)/', $timeNotation, $times);
		if (count($times[0]) > 0) {
			
			$offsets = array();
			preg_match_all('/([+-])(\d{2})(?:\:?(\d{2}))?/', $times[7][0], $offsets);
			
			if (count($offsets[0]) > 0) {
				$offsetHour += $offsets[2][0] * ($offsets[1][0] == "-"? -1 : 1);
				$offsetMinute += $offsets[3][0] * ($offsets[1][0] == "-"? -1 : 1);
			}
			
			$hour = $times[1][0] + $offsetHour;
			$minute = ("0.".$times[2][0]) * 60 + $times[3][0] + $offsetMinute;
			$second = ("0.".$times[4][0]) * 60 + $times[5][0] + 0;
		}
		else {
			$hour = $minute = $second = 0;
		}
		
		return date($format, mktime($hour, $minute, $second, $month, $day, $year));
	}
	
	// string convertIntoPlist(array &array)
	function convertIntoPlist(&$array, $human=false) {
		$nl = ($human) ? "\n" : "";
		$exp = "<?xml version=\"1.0\" encoding=\"UTF-8\"?".">".$nl."<!DOCTYPE plist PUBLIC \"-//Apple Computer//DTD PLIST 1.0//EN\" \"http://www.apple.com/DTDs/PropertyList-1.0.dtd\">".$nl."<plist version=\"1.0\">".$nl;
		$exp .= $this->_parseArray($array, $human);
		$exp .= "</plist>";
		return $exp;
	}
	
	// string _parseArray(array &array)
	function _parseArray(&$array, $human=false, $nIndent=false) {
		$src = "";
		$nl = ($human) ? "\n" : '';
		$indentChar = "\t";
		$baseIndent = '';
		$indent = '';
		if ($human) {
			$nIndent = ($nIndent === false) ? 1 : $nIndent+1;
			for ($i = 0; $i < $nIndent-1; $i++) $baseIndent .= $indentChar;
			for ($i = 0; $i < $nIndent; $i++) $indent .= $indentChar;
		}
		
		// check type
		$type = "array";
		$count = 0;
		$keys = array_keys($array);
		sort ($keys);
		while (list($key, $value) = each($keys)) {
			if (gettype($value) != "integer" || $key != $count) {
				$type = "dict";
				break;
			}			
			$count ++;
		}

		// 27-04-11: fix by gudoy: for associative arrays whose keys can be integers
		// We check that the first key of the array is 0 and that the last key is {array length - 1}
		// Remains 1 case of false positive for arrays like this (0 => 'foo', 'bar' => 'foobar', 2 => 'baz')
		reset($array);
		$type = ( $type !== 'dict' && key($array) == 0 && isset($array[count($array) - 1]) ) ? $type : 'dict';
		
		$exp = '';
		$exp .= ($type == 'dict') ? $baseIndent."<".$type.">".$nl : $nl.$baseIndent."<".$type.">".$nl;
		reset($array);
		while (list($i,) = each($array)) {
			$exp .= ($type == 'dict' && gettype($array[$i])!== 'NULL') ? $indent.'<key>'.$i.'</key>' : $indent;
			//$exp .= ($type == 'dict') ? $indent.'<key>'.$i.'</key>' : $indent;
//var_dump(gettype($array[$i]));
			switch (gettype($array[$i]))
			{
				#case "NULL":  
				#	$exp .= "<null> </null>".$nl;
				#	break;
				// collections
				case "object": 
					$array[$i] = (Array) $array[$i];
					$exp .= $this->_parseArray($array[$i], $human, $nIndent); 
					break;
				case "array":
					$exp .= $this->_parseArray($array[$i], $human, $nIndent); 
					break;
				// primitive types
				case "string": 
					//$exp .= "<string>" . ( !empty($array[$i]) ? $array[$i] : ' ') . "</string>".$nl; 
					//$exp .= "<string>" . $array[$i] . "</string>".$nl;
					// Fix alexis for special chars replacement
					$exp .= "<string>" . str_replace(array("&", "<", '>', "'", '"'), array('&amp;', '&lt;','&gt;', '&apos;', '&quot;'), $array[$i]) . "</string>".$nl;
					break;
				// numerical primitives
				case "boolean":
					if($array[$i]) {
						$exp .= "<true />".$nl;
					}
					else {
						$exp .= "<false />".$nl;
					}
					break;
				case "integer": 
					$exp .= "<integer>".$array[$i]."</integer>".$nl; 
					break;
				case "double": 
					//$oldLocale 	= setlocale(LC_ALL, NULL);
					//$lc 		= setlocale(LC_ALL, "en_US.utf8");
					//$exp .= "<real>".$array[$i]."</real>".$nl;
					$exp .= "<real>".str_replace(',','.',$array[$i])."</real>".$nl;
					//$exp .= "<real>".number_format($array[$i], 10, '.')."</real>".$nl;
					//setlocale(LC_ALL, $oldLocale);
					break;
				default:
			}
		}
		$exp .= $baseIndent."</".$type.">".$nl;
		
		return $exp;
	}
	
	// void _parseFile(string url)
	function _parseFile($url) {
		$this->doc =& domxml_open_file($url);
		if (!$this->doc) {
			return false;
		}
		// first node
		$root =& $this->doc->document_element();
		// store
		$this->dataArray =& $this->_parseNode($root);
	}
	
	// void _parseExpression(string exp)
	function _parseExpression($exp)
	{
		$this->doc =& domxml_open_mem($exp);
		if (!$this->doc) {
			return false;
		}
		// first node
		$root =& $this->doc->document_element();
		// store
		$this->dataArray =& $this->_parseNode($root);
	}
	
	// array &_parseNode(DomNode &node)
	function &_parseNode(&$node) {
		$childrenArray = array();
		$currentNode =& $node->first_child();
		$n = 0;
		
		while ($currentNode) {
			if ($currentNode->node_name() != "#text"){
				// found a key
				if ($currentNode->node_name() == "key") {
					$currentKey =& $currentNode->get_content();
				}
				// found the value for the current key
				else {
					$childrenArray[(isset($currentKey)? $currentKey : $n)] =& $this->_getValue($currentNode);
				}
				$n ++;
			}
			// advance to the next node
			$currentNode =& $currentNode->next_sibling();
		}
		return $childrenArray;
	}
	
	// mixed &_getValue(DomNode &node)
	function &_getValue(&$node) {
		switch ($node->node_name()) {
			// collections
			case "array":
			case "dict": 
				return $this->_parseNode($node);
			// primitive types
			case "date": 
				return $this->formatDate($node->get_content(), $this->dateFormat, $this->timeZone);
			case "string":
			case "data": 
				return (string) $node->get_content();
			// numerical primitives
			case "true": 
				return true;
			case "false": 
				return false;
			case "real":
			case "integer":
				if (preg_match("/^[0-9]+$/", $node->get_content())) {
					return (integer) $node->get_content();
				}
				else {
					return (float) $node->get_content();
				}
			default:
		}
	}
	
	// array &getData()
	function &getData() {
		return $this->dataArray[0];
	}
	// void setDateFormat(string dateFormat)
	function setDateFormat($dateFormat) {
		$this->dateFormat = $dateFormat;
	}
	// void setTimeZone(int timeZone)
	function setTimeZone($timeZone) {
		$this->timeZone = $timeZone;
	}
	// void setURL(string url)
	function setURL($url) {
		$this->url = $url;
	}
}

?>