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
    INSERT INTO guestbook (name, content) VALUES (:name, :content);
SQL;

$stmt = $db->prepare($query);
$stmt->bindParam(':name', $name);
$stmt->bindParam(':content', $content);

$res = $stmt->execute();

if(!$res) {
    $retval["message"] = "发生错误：".$db->errorInfo();
    echo json_encode($retval);
    $stmt = null;
    $db = null;
    exit;
}

$stmt = null;
$db = null;

$retval["message"] = "欢迎{$name}！留言成功，内容为：{$content}";
echo json_encode($retval);
?>
