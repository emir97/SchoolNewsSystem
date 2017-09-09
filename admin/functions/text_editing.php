<?php
function restrictText($text){
	
	return $text;
}
function restrictOnLimitedWords($word, $limit){
	$word = wordwrap($word, $limit, "<br/>");
	return $word;
}


?>