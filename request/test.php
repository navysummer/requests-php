<?php
require "./Request.php";
$url = "http://txqq789.com";
$request = new Request\Request();
//$res = $request->get($url);
//$res->cookies;
//var_dump($_COOKIE);
$login_url = $url.'/server/login.aspx?name=72205&pass=xia990722';
$res = $request->get($login_url);
$cookies = $res["response"]["cookies"];
//print('---------');
//var_dump($cookies);
//print('---------');
$home_url = $url.'/home/my_home.aspx';
//$headers = explode(';',$cookies);
$res = $request->get($home_url,null,$cookies);
var_dump($res);
