<?php
/*
Plugin Name: Selective RSS
Plugin URI: http://techno-geeks.org/selective-rss
Description: Simple Plugin that allows you to embed RSS feed items into Pages or Posts. Optionally allows you to choose how many items to display and allows you to limit items to ones that contain certain words in the titles.
Version: 0.1.1b
Author: Jesse R. Adams (DualTech Services, Inc.)
Author URI: http://www.dual-tech.com/about-dualtech-services/
*/

require_once('rss_lib.php');

function filter_feeds($content) {
	$limit = null;
	$filter = array();
	$url = null;
	$persistDuration = 7;
	$persist = false;

	$inputTag = preg_match_all('/\[srss (.+)\]/', $content, $tags);
	foreach ($tags[1] as $tagNum => $tag) {
		$rawArgs = explode(',', $tag);
		
		// Parse the arguments
		foreach($rawArgs as $input) {
			preg_match_all('/^(url|filter|limit|persist|persist_duration)=(.+)$/i', $input, $matches);
			$key = $matches[1][0];
			$value = $matches[2][0];
	
			if ($key == 'filter') {
				$filter = explode(';', $value);
			} else {
				$$key = $value;	
			}
		}
	
		if ($url) {
			$xml = parseRSS($url);
			$items = extractRSSItems($xml, $limit, $filter);
			
			if ($persist && $persistDuration > 0) {
				$feedStore = WP_CONTENT_DIR . '/plugins/selective-rss/persistent/' . md5($url);
				
				// Fetch stored items
				$persistentItems = array();
				if (file_exists($feedStore)) {
					$contents = file_get_contents($feedStore);
					if (strlen($contents) !== 0) {
						$persistentArray = unserialize($contents);
						if (count($persistentArray) !== 0) {
							$persistentItems = $persistentArray;
						}
					}
				} else {
					// Attempt to create
					$fp = fopen($feedStore, 'w');
					fclose($fp);				
				}
				
				$today = date('Ymd');
				if (count($persistentItems) !== 0) {
					// Prune old items
					foreach ($persistentItems as $key => $item) {
						$expiration = (int)$item['persistUntil'];
						$today = (int)$today;
						if ($expiration <= $today) {
							unset($persistentItems[$key]);
						}
					}
				}

				if (count($items) !== 0) {
					// Add new items
					$persistUntil = date('Ymd', strtotime('+' . $persist_duration . ' day'));
					foreach ($items as $key => $item) {
						$feedId = md5($item['link']);
						if (!array_key_exists($feedId, $persistentItems)) {
							$item['persistUntil'] = $persistUntil;
							$persistentItems[$feedId] = $item;
						}
					}
				}
				
				$sortedArray = array();
				foreach ($persistentItems as $key => $item) {
					$sortedArray[date('YmdHis', strtotime($item['pubdate']))][] = $item;
				}
				krsort($sortedArray);	
				
				if ($limit) {
					$count = 1;
					foreach ($sortedArray as $date => $itemArray) {
						foreach ($itemArray as $key => $item) {
							if ($count > $limit) {
								unset($sortedArray[$date][$key]);
							}
							
							$count++;
						}
					}
				}
				
				// Save items
				$fp = fopen($feedStore, 'w');
				fwrite($fp, serialize($persistentItems));
				fclose($fp);			
				
				$items = $sortedArray;
			}
	
			// Build the HTML to display
			if (count($items) !== 0) {
				$htmlToAdd = '';
				foreach ($items as $date => $itemArray) {
					foreach ($itemArray as $item) {
						$htmlToAdd .= '<a href="' . $item['link'] . '" target="new">' . $item['title'] . '</a>' . "<br/>\n";
						$htmlToAdd .= $item['description'] . "<br/><br/>\n";
					}
				}
			} else {
				$htmlToAdd = 'There are new items at this time.';
			}
			$content = preg_replace('/' . preg_quote($tags[0][$tagNum], '/') . '/', $htmlToAdd, $content);
		}
	}
	return $content;
}

add_filter('the_content', 'filter_feeds');
?>
