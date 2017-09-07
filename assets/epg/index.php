<?php
include(__DIR__ . '/settings.php');

$mysqli = new mysqli(SQLHOST, SQLUSER, SQLPASS, SQLBASE);
$mysqli->set_charset("utf8");

$channel_id = 1;

if($stmt = $mysqli->prepare('SELECT `start`, `stop`, `category`, `title`, `desc` FROM  `programme` WHERE `channel_id` = ?;'))
{
	$stmt->bind_param('s', $channel_id);
	$stmt->execute();
	$stmt->bind_result($start, $stop, $category, $title, $desc);
	printf('<table>');
	
	while($stmt->fetch()) {
		printf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>', $start, $stop, $title, $desc, $category);
	}
}	
	printf('</table>');

?>
