<?php
$conn = mysqli_connect('localhost', 'root', 'liuliliuli');
if (mysqli_connect_error() != null) {
	die(mysqli_connect_error());
}else{
	echo "数据库连接成功";
}

//选择数据库
mysqli_select_db($conn, 'test');
//设置字符集
mysqli_set_charset($conn, 'utf8');


$sql = "SELECT * FROM user";
mysqli_query($conn, $sql);