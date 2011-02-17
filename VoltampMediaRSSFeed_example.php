<?php 

include('./VoltampMediaRSSFeed.php');

$xml_feed = new VoltampMediaRSSFeed('http://www.worldwiderv.com/irv/feeds/google-base.xml');

echo $xml_feed->rss_feed_title() . '<br />';
echo $xml_feed->rss_feed_description() . '<br />';
echo $xml_feed->rss_feed_url() . '<br />';

foreach($xml_feed->rss_feed_items() as $cnt => $item){
	foreach($item as $tag => $val){
		echo '&nbsp;&nbsp;' . $tag . ': ' . $val . '<br />';
	}
	echo '<br />';
}


