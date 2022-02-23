<?php

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
    SELECT name, content FROM guestbook;
SQL;

$res = pg_query($db, $query);
$mesg = array();

if(!$res) {
    echo pg_last_error($db);
    pg_close($db);
    exit;
}

while($row = pg_fetch_row($res)){
    $mesg[] = array("name" => "$row[0]", "content" => "$row[1]");
}

pg_close($db);

echo json_encode($mesg);

?>