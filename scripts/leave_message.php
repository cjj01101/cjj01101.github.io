<?php

$name=$_POST["name"];
$content=$_POST["message"];

$file="../data/message.xml";
$doc = new DOMDocument();
$doc->load($file);

$root=$doc->documentElement;
$message=$doc->createElement("message");
$root->appendChild($message);

$mname=$doc->createElement("name",$name);
$mcont=$doc->createElement("content",$content);
$message->appendChild($mname);
$message->appendChild($mcont);

$doc->save($file);

echo "欢迎".$name."！留言成功，内容为：".$content;

?>
