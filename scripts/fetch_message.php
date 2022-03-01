<?php

$url="postgres://manlvbqpkpdqli:1f690c90d16fd894dde0b412539f556b8cf329f08b167e20c01c202ac9b868df@ec2-52-0-114-209.compute-1.amazonaws.com:5432/de4hhuagqnmkd2";
$dbinfo = parse_url($url);

$host = $dbinfo["host"];
$port = $dbinfo["port"];
$dbname = substr($dbinfo["path"],1);
$user = $dbinfo["user"];
$pass = $dbinfo["pass"];

try {
    $db = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
} catch (PDOException $e) {
    $retval["message"] = "发生错误：".$e->getMessage();
    echo json_encode($retval);
    exit;
}

$query = <<<SQL
    SELECT name, content FROM guestbook;
SQL;

$stmt = $db->prepare($query);

$res = $stmt->execute();

if(!$res) {
    $stmt = null;
    $db = null;
    exit;
}

$mesg = array();
while($row=$stmt->fetch()){
    $mesg[] = array("name" => "$row[0]", "content" => "$row[1]");
}

$stmt = null;
$db = null;

echo json_encode($mesg);

?>