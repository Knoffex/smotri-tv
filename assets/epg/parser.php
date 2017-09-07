<?php
include(__DIR__ . '/settings.php');
$mysqli = new mysqli(SQLHOST, SQLUSER, SQLPASS, SQLBASE);
$mysqli->set_charset("utf8");

//invalid xml file
$xmlfile = __DIR__ . '/xmltv.xml';

$xmlDoc = new DOMDocument();
$xmlDoc->load($xmlfile);

$x = $xmlDoc->documentElement;

// Парсинг списка каналов и их иконок
$searchNode = $xmlDoc->getElementsByTagName("channel");
#$mysqli->begin_transaction();
foreach( $searchNode as $searchNode ) 
{
	$id = $searchNode->getAttribute('id');
	
	$xmlChannel = $searchNode->getElementsByTagName("display-name"); 
	if($xmlChannel->length > 0)
	{
    	$channelName = $xmlChannel->item(0)->nodeValue;
    } else {
    	$channelName = '';
    }
	
	$xmlIcon = $searchNode->getElementsByTagName("icon"); 
	if($xmlIcon->length > 0)
	{
    	$icon = $xmlIcon->item(0)->getAttribute('src');
    } else {
    	$icon = '';
    }
	#echo "$id $channelName $icon\n";
	
	if($stmt = $mysqli->prepare("REPLACE INTO `channels` (`id`, `name`, `icon`) VALUES (?, ?, ?);"))
	{
		 $stmt->bind_param("sss", $id, $channelName, $icon);
		 $stmt->execute();
		 $stmt->close();
	}
}
$mysqli->commit();


// Парсинг программы передач
$searchNode = $xmlDoc->getElementsByTagName("programme");
$mysqli->begin_transaction();
foreach( $searchNode as $searchNode ) 
{
	$channelId = $searchNode->getAttribute('channel');
	$start = DateTime::createFromFormat('YmdHis O', $searchNode->getAttribute('start'));
	$stop = DateTime::createFromFormat('YmdHis O', $searchNode->getAttribute('stop'));
	
	$xmlTitle = $searchNode->getElementsByTagName("title"); 
	if($xmlTitle->length > 0)
	{
    	$title = $xmlTitle->item(0)->nodeValue;
    } else {
    	$title = '';
    }
	
	$xmlDesc = $searchNode->getElementsByTagName("desc"); 
	if($xmlDesc->length > 0)
	{
    	$desc = $xmlDesc->item(0)->nodeValue;
    } else {
    	$desc = '';
    }
    
    $xmlCategory = $searchNode->getElementsByTagName("category"); 
	if($xmlCategory->length > 0)
	{
    	$category = $xmlCategory->item(0)->nodeValue;
    } else {
    	$category = '';
    }
    $startFormat = $start->format('Y-m-d H:i:s');
    $stopFormat = $stop->format('Y-m-d H:i:s');
	#echo "$channelId $startFormat $stopFormat $category $title $desc\n";
	
	if($stmt = $mysqli->prepare("REPLACE INTO `programme` (`channel_id`, `start`, `stop`, `category`, `title`, `desc`) VALUES (?, ?, ?, ?, ?, ?);"))
	{
		 $stmt->bind_param("ssssss", $channelId, $startFormat, $stopFormat, $category, $title, $desc);
		 $stmt->execute();
		 $stmt->close();
	}
}
$mysqli->commit();

$mysqli->close();
?>
