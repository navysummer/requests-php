<?php
require "./Request.php";
$url = "http://txqq789.com";
$request = new Request\Request();
//$res = $request->get($url);
//$res->cookies;
//var_dump($_COOKIE);
$login_url = $url.'/server/login.aspx?name=72205&pass=xia990722';
$res = $request->get($login_url);
var_dump($res);