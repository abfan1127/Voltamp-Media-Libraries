<?php 

include('./VoltampMediaRSSFeed.php');

// first variable is the xml feed
// second variable is Strict mode. If strict mode is true, then if the criteria tag does not exist,
// it removes the entry from the output list. if the strict mode is false, then it allows an entry
// with a missing criteria tag through. 

//$xml_feed = new VoltampMediaRSSFeed('http://www.worldwiderv.com/irv/feeds/rss.xml',false);
$xml_feed = new VoltampMediaRSSFeed('http://localhost/factor1/xml_rv/test.xml',false);


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
	/*foreach($item as $tag => $val){
		if(is_array($val)) {
			echo '&nbsp;&nbsp;' . $tag . ': ';
			 foreach($val as $key => $val2){
			 	echo $key . ':: ' . $val2 . ' | ';
			 } 
			 echo '<br />';
		} else {
			echo '&nbsp;&nbsp;' . $tag . ': ' . $val . '<br />';
		}
	}//*/
	?>
	<div>
		<p><b>title:</b> <?php echo $item['title']; ?></p>
		<p><b>description:</b> <?php echo $item['description']; ?></p>
		<p><b>link:</b> <a href="<?php echo $item['link'];?>"><?php echo $item['link']; ?></a></p>
		<p><b>enclosure:</b> <img src="<?php echo $item['enclosure']['url'];?>" /></p>
		<p><b>pubDate:</b> <?php echo $item['pubDate']; ?></p>
	</div>
	
	<?php
	
	echo '<br />';
}


