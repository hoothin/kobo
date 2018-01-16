<?php
$rssUrl="https://www.g-cores.com/rss";
$rssArticle="https://www.g-cores.com/articles/";
empty($_GET['type']) && $_GET['type'] = '';
empty($_GET['id']) && $_GET['id'] = '';
$type = $_GET['type'];
$id = $_GET['id'];
//header('HTTP/1.1 304 Not Modified');  
$html=file_get_contents("compress.zlib://".$rssUrl);
switch($type){
	case "html":
		header("Content-Type:text/html;charset=utf-8");
		$html=preg_replace('/[\x00-\x1F\x7F]/',"",$html);
		$xml_array=simplexml_load_string($html);
	    foreach($xml_array as $tmp){
	    	foreach($tmp as $tmpItem){
		    	if($tmpItem->link==$rssArticle.$id){

					echo '
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>
        '.$tmpItem->title.'
    </title>
</head>
<body class="body-fixedNavbar">
    <div class="wrapper">
        <div id="j_story_18185" class="story">
            <div class="story_elem story_elem-text ">'.$tmpItem->description.'<a href=https://www.g-cores.com/articles/'.$id.'>原文</a>'.'</div>
        </div>
    </div>
</body>

</html>';
		    		return;
		    	}
		    }
	    } 
		break;
	default:
		header("Content-Type:text/xml;charset=utf-8");
		echo preg_replace('/<link>https:\/\/www\.g\-cores\.com\/articles\/(\d+)/i',"<link>".'http:'.($_SERVER["HTTPS"] == 'on'?'s':'').'//'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER['PHP_SELF']."?type=html&amp;id=$1",$html);
		break;
}
?>