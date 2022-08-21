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
        $url        = urlencode($url);
        $user_agent = "Googlebot/2.1 (http://www.googlebot.com/bot.html)";
        $client = new \GuzzleHttp\Client([
            'verify' => false,
            'timeout' => 5, // Response timeout
            'connect_timeout' => 5, // Connection timeout
            'peer' => false
        ]);
        $client->request('GET', $url, [
                'headers' => ['User-Agent' => $user_agent, 'Accept-Encoding' => 'gzip'],
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10, // Connection timeout
        ]);
        return $client->getBody()->getContents();
    }

    public function deleteGuzzleRequest($url)
    {
        
    }

}