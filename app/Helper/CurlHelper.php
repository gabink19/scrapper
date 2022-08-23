<?php

namespace App\Helper;

class CurlHelper 
{
    public function curlTokped($url)
    {   
        $header = array(
            'accept:text/html, text/javascript, */*; q=0.01',
            'accept-language:en-US,en;q=0.8,id;q=0.6,ms;q=0.4',
            'origin:https://www.tokopedia.com/',
            'referer:'.$url,
            'user-agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60); 
        curl_setopt($ch, CURLOPT_HEADER, false); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); //timeout in seconds
        $data = curl_exec($ch);
        curl_close($ch);

        return htmlentities($data);
    }

}