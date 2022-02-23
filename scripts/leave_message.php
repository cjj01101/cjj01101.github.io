<?php

header('Content-Type:application/json; charset=utf-8');

$name=$_POST["name"];
$content=$_POST["message"];

$ret = array("message" => "");

if(empty($name) || empty($content)){
    $ret["message"] = "名字和留言均不能为空";
    die(json_encode($ret));
}

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

$ret["message"] = "欢迎{$name}！留言成功，内容为：{$content}";
echo json_encode($ret);
?>
