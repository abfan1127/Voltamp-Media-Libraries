<?php 

include('./VoltampMediaRSSFeed.php');

// first variable is the xml feed
// second variable is Strict mode. If strict mode is true, then if the criteria tag does not exist,
// it removes the entry from the output list. if the strict mode is false, then it allows an entry
// with a missing criteria tag through. 

$xml_feed = new VoltampMediaRSSFeed('http://www.worldwiderv.com/irv/feeds/google-base.xml',false);

// acceptable relations are '==', '!=' ,'<', '<=', '>', '>='

$xml_feed->add_criteria('g:condition','==','Used');
$xml_feed->add_criteria('g:year','>','2000');
$xml_feed->add_criteria('g:year','<=','2011');
$xml_feed->add_criteria('g:weight','<',5000);

$xml_feed->process();

echo $xml_feed->rss_feed_title() . '<br />';
echo $xml_feed->rss_feed_description() . '<br />';
echo $xml_feed->rss_feed_url() . '<br />';

foreach($xml_feed->rss_feed_items() as $cnt => $item){
	foreach($item as $tag => $val){
		echo '&nbsp;&nbsp;' . $tag . ': ' . $val . '<br />';
	}
	echo '<br />';
}


