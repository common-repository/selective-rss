<?php
 
/*
* RSS Parsing Function
*
* Original Author: Ryan Stemkoski
* http://www.stemkoski.com/how-to-easily-parse-a-rss-feed-with-php-4-or-php-5/
*
* Slight modifications by Jesse R. Adams (DualTech Services, Inc.)
*/ 

function parseRSS($url) { 
 
	//PARSE RSS FEED
        $feedeed = implode('', file($url));
        $parser = xml_parser_create();
        xml_parse_into_struct($parser, $feedeed, $valueals, $index);
        xml_parser_free($parser);
 
	//CONSTRUCT ARRAY
        foreach($valueals as $keyey => $valueal){
            if($valueal['type'] != 'cdata') {
                $item[$keyey] = $valueal;
			}
        }
 
        $i = 0;
 
        foreach($item as $key => $value){
 
            if($value['type'] == 'open') {
 
                $i++;
                $itemame[$i] = $value['tag'];
 
            } elseif($value['type'] == 'close') {
 
                $feed = $values[$i];
                $item = $itemame[$i];
                $i--;
 
                if(count($values[$i])>1){
                    $values[$i][strtolower($item)][] = $feed;
                } else {
                    $values[$i][strtolower($item)] = $feed;
                }
 
            } else {
                $values[$i][strtolower($value['tag'])] = $value['value'];  
            }
        }
 
	//RETURN ARRAY VALUES
	return $values[0];
} 

/*
* Convert Raw RSS Parse into concise list of items
*
* Optionally pass in limit (on number of items)
* and/or array list of words to grab items by.
*
*/

function extractRSSItems($xmlArray, $limit = null, $filteredWords = array()) {
	$array = array();
	$regex = null;

	if (count($filteredWords) > 0) {
		$regex = implode('|', $filteredWords);
	}

	foreach($xmlArray['rss']['channel']['item'] as $item) {
		if (!$regex || ($regex && preg_match("/$regex/i", $item['title']))) {
			$array[] = $item;
		}
 	}

	if($limit) {
		array_splice($array, ($limit - 1), (count($array) - $limit));
	}

	return $array;
}
?>
