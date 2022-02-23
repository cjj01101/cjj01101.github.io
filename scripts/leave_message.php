<?php

header('Content-Type:application/json; charset=utf-8');

$name=$_POST["name"];
$content=$_POST["message"];

$retval = array("message" => "");

if(empty($name) || empty($content)){
    $retval["message"] = "名字和留言均不能为空";
    die(json_encode($retval));
}

$url="postgres://manlvbqpkpdqli:1f690c90d16fd894dde0b412539f556b8cf329f08b167e20c01c202ac9b868df@ec2-52-0-114-209.compute-1.amazonaws.com:5432/de4hhuagqnmkd2";
$dbinfo = parse_url($url);

$host = "host=$dbinfo[host]";
$port = "port=$dbinfo[port]";
$dbname = "dbname=".substr($dbinfo["path"],1);
$user = "user=$dbinfo[user]";
$password = "password=$dbinfo[pass]";

$db = pg_connect("$host $port $dbname $user $password")
or die("Connection Failed.");

$query = <<<SQL
    INSERT INTO guestbook (name, content) VALUES ('$name', '$content');
SQL;

$ret = pg_query($db, $query);

if(!$ret) {
    echo pg_last_error($db);
    pg_close($db);
    exit;
}

pg_close($db);

$retval["message"] = "欢迎{$name}！留言成功，内容为：{$content}";
echo json_encode($retval);
?>
