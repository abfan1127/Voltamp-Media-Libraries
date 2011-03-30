<?php 
//
// RSS Reader - Specifically for Google Base Product Feeds
//
// Author: Eric Cope
// http://voltampmedia.com
// Voltamp Media, Inc.
//
//

class VoltampMediaRSSFeed {

	private $rss_feed_url = '';
	private $rss_title = '';
	private $rss_description = '';
	private $rss_items = array();
	private $criteria_array = array();
	private $strict;

	function __construct($rss_feed_url,$strict = false)
	{
		$this->rss_feed_url = $rss_feed_url;
		$this->strict = $strict;
		return;
	}

	function process() {		
	
		$rss_feed = file_get_contents($this->rss_feed_url);
		
	    $parser = xml_parser_create();
	    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		if (!xml_parse_into_struct($parser, $rss_feed, $values, $tags)) {
        	die(sprintf("XML error: %s at line %d",
                xml_error_string(xml_get_error_code($parser)),
                xml_get_current_line_number($parser)));
    	}
    	xml_parser_free($parser);
    	    	
    	foreach($tags['item'] as $cnt => $index){
    		if(($values[$index]['type'] != 'close')){
	    		$product_array = array();
	    		$index++;
	    		while(($values[$index]['type'] != 'close') && ($values[$index]['tag'] != 'item') && ($index < max($tags['channel']))){
	    			if(array_key_exists('value',$values[$index])){
    					$product_array[$values[$index]['tag']] = $values[$index]['value'];
		    		}
	    			$index++;
	    		}
	    		$this->add_item($product_array);
	    	}
    	}
    	
    	if(count($this->criteria_array) > 0){
	    	foreach($this->rss_items as $index => $row)
	    	{
	    		foreach($this->criteria_array as $tag => $params){
	    			foreach($params as $relation => $val){
		    			if(array_key_exists($tag,$row)){
		    				switch($relation){
		    					case '==':	if(!($row[$tag] == $val)) unset($this->rss_items[$index]); break 1;
		    					case '!=':	if(!($row[$tag] != $val)) unset($this->rss_items[$index]); break 1;
		    					case '>':	if(!((double)$row[$tag] >  (double)$val)) unset($this->rss_items[$index]); break 1;
		    					case '>=':	if(!((double)$row[$tag] >= (double)$val)) unset($this->rss_items[$index]); break 1;
		    					case '<':	if(!((double)$row[$tag] <  (double)$val)) unset($this->rss_items[$index]); break 1;
		    					case '<=':	if(!((double)$row[$tag] <= (double)$val)) unset($this->rss_items[$index]); break 1;
		    				}
		    			} else {
		    				if($this->strict) unset($this->rss_items[$index]);
		    			}
		    		}
	    		}
	    	}
	    	$this->rss_items = array_values($this->rss_items);
		}    	
    	
    	var_dump($this->rss_items);
    	
    	$feed_title_done = false;
    	$feed_url_done = false;
    	$feed_description = false;
    	foreach($tags['channel'] as $cnt => $index){
    		while(!($feed_title_done && $feed_url_done && $feed_description) && ($index < max($tags['channel']))){
    			if($values[$index]['tag'] == 'title'){
    				$this->rss_feed_title($values[$index]['value']);
    				$feed_title_done = true;
    			} else if($values[$index]['tag'] == 'description'){
    				$this->rss_feed_description($values[$index]['value']);
    				$feed_description = true;
    			} else if($values[$index]['tag'] == 'link'){
    				$this->rss_feed_url($values[$index]['value']);
    				$feed_url_done = true;
    			}
    			$index++;
    		}
    	}
	}
	
	function add_criteria($tag,$relation,$val)
	{
		$this->criteria_array[$tag][$relation] = $val;
	}
	
	function add_item($item_data){
		$this->rss_items[] = $item_data;
	}
	
	function rss_feed_url($rss_feed_url = ''){
		if(strlen($rss_feed_url) > 0) $this->rss_url = $rss_feed_url;
		else return $this->rss_url;
	}
	
	function rss_feed_title($rss_feed_title = ''){
		if(strlen($rss_feed_title) > 0) $this->rss_title = $rss_feed_title;
		else return $this->rss_title;
	}
	
	function rss_feed_description($rss_feed_description = ''){
		if(strlen($rss_feed_description) > 0) $this->rss_description = $rss_feed_description;
		else return $this->rss_description;
	}
	
	function rss_feed_items($rss_feed_obj = null){
		if($rss_feed_obj !== null) {
			$this->rss_items = array();
			for($ii = 0; $ii < count($rss_feed_obj->item); $ii++){
				$this->rss_items[] = $rss_feed_obj->item[$ii];
			}
		}
		else return $this->rss_items;
	}
}