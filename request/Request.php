<?php


namespace Request;


class Request
{
    /**
     * @var false|resource
     */
    private $ch,$res,$cookies;

    function __construct(){
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_HEADER, true);
        curl_setopt($this->ch, CURLINFO_HEADER_OUT, true);
    }
    function exec($headers=null, $cookies=null, $proxy= null, $allow_redirects=true,$json=false){
        if($proxy){
            curl_setopt($this->ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt($this->ch, CURLOPT_PROXY, $proxy);
        }
        if(!$allow_redirects){
            curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
        }
        if($headers and !$cookies){
            curl_setopt($this->ch,CURLOPT_HTTPHEADER,$headers);
            $output = curl_exec($this->ch);

        }elseif ($cookies and !$headers){
            curl_setopt($this->ch,CURLOPT_COOKIE,$cookies);
            $output = curl_exec($this->ch);

        }elseif (!$headers and !$cookies){
            $output = curl_exec($this->ch);
        }else{
            curl_setopt($this->ch,CURLOPT_HTTPHEADER,$headers);
            curl_setopt($this->ch,CURLOPT_COOKIE,$cookies);
            $output = curl_exec($this->ch);
        }
        if($json){
            $output = json_decode($output,true);
        }
        $info = curl_getinfo($this->ch);
        list($header, $body) = explode("\r\n\r\n", $output);
        preg_match("/Set\-cookie:([^\r\n]*)/i", $header, $matches);
        $result = array(
            'response'=>array(
                'cookies'=>$matches[1],
                'body'=>$body,
                'header'=>$header,
                'status_code'=>$info["http_code"]
            ),
            'request'=>$info
        );
        return $result;
    }
    function get($url,$headers=null, $cookies=null, $proxy= null, $allow_redirects=true,$json=false){
        curl_setopt($this->ch, CURLOPT_URL, $url);
        $this->res = $this->exec($headers, $cookies, $proxy, $allow_redirects,$json);
        return $this->res;

    }
    function post($url, $data,$headers=null, $cookies=null, $proxy= null, $allow_redirects=true,$json=false){
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        $this->res = $this->exec($headers, $cookies, $proxy, $allow_redirects,$json);
        return $this->res;
    }
    function put($url, $data,$headers=null, $cookies=null, $proxy= null, $allow_redirects=true,$json=false){
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST,"PUT");
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        $this->res = $this->exec($headers, $cookies, $proxy, $allow_redirects,$json);
        return $this->res;
    }
    function patch($url, $data=null,$headers=null, $cookies=null, $proxy= null, $allow_redirects=true,$json=false){
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST,"PATCH");
        if($data){
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        }
        $this->res = $this->exec($headers, $cookies, $proxy, $allow_redirects,$json);
        return $this->res;
    }
    function delete($url, $data=null,$headers=null, $cookies=null, $proxy= null, $allow_redirects=true,$json=false)
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        if ($data) {
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        }
        $this->res = $this->exec($headers, $cookies, $proxy, $allow_redirects,$json);
        return $this->res;
    }
    function __destruct(){
        curl_close($this->ch);
    }

}