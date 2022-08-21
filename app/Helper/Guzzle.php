<?php

namespace App\Helper;

class Guzzle {

    public static function reqWithGuzzle($url)
    {
    	$response = [];
	    $response['is_error'] = true;
        $user_agent = "Googlebot/2.1 (http://www.googlebot.com/bot.html)";
		$client   = new \GuzzleHttp\Client();
    	try {
			$request = $client->get($url, [
    			'timeout' => 10, // Response timeout
    			'connect_timeout' => 10, // Connection timeout
    		]);
			$statusCode = $request->getStatusCode();
	        $body     	= $request->getBody()->getContents();
	        if ($statusCode==302) {
	        	throw new Exception('site offline');
	        }
	        $response['is_error'] = false;
	        $response['body'] 	  = $body;
	    } catch (\GuzzleHttp\Exception\RequestException $e) {
            $msg = $e->getMessage();
	        $response['is_error'] = true;
	        $response['body'] 	  = $msg;
        }
	    return $response;
    }

    public static function postGuzzleRequest($url, $data)
    {
        $client     = new \GuzzleHttp\Client();
        $response   = $client->request('POST', $url, [ 'json' => $data ]);
        $body       = $response->getBody()->getContents();
        return json_decode($body);
    }

    public function getCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Googlebot/2.1 (http://www.googlebot.com/bot.html)");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/html'));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); //timeout in seconds
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    public function deleteGuzzleRequest($url)
    {
        
    }

}