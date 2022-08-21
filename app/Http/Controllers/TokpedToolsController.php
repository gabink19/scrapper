<?php
namespace App\Http\Controllers;

use App\Helper\Guzzle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helper\ValidatorHelper;

class TokpedToolsController extends Controller
{
	public function index()
	{
        return view('scrapform');
	}

	public function etalase(Request $request)
	{
		$datareq = $request->all();
		try {
			$validator = Validator::make($datareq, [
				'url_toko' => 'required|url',
			],$this->messageValidator());
	        ValidatorHelper::validate($validator);
		} catch (\Exception $e) {
        	return redirect('/tokped/');
		}

		$data = self::getSellerCategory($request['url_toko']);
        return view('listetalase',['list'=>$data]);
	}

    public function scrapPerProduct($url='https://www.tokopedia.com/eseskomputer/mouse-razer-orochi-v2-wireless-hyperspeed-mouse-gaming-black') 
    {
    	$response 	= [];
        $content 	= self::file_get_html($url);
        $mainUrl 	= 'https://www.tokopedia.com/';
        $response['nama_produk']	= ($content->find('h1[data-testid="lblPDPDetailProductName"]',0)->plaintext ?? '');
        $response['harga_produk']	= ($content->find('div[data-testid="lblPDPDetailProductPrice"]',0)->plaintext ?? '');
        $response['stok_produk']	= ($content->find('p[data-testid="stock-label"]',0)->plaintext ?? '');
        if ($response['stok_produk']=='') {
        	$response['stok_produk'] = ($content->find('div[data-testid="divVarContainerBody"] p b',0)->plaintext ?? '');
        }
        $response['deskripsi']		= ($content->find('div[data-testid="lblPDPDescriptionProduk"]',0)->plaintext ?? '');
        $response['url_gambar']		= [];
        $listimage					= $content->find('div[data-testid="PDPImageThumbnail"]');
        foreach ($listimage as $key => $image) {
        	$urlImage = ($image->find('div img',1)->src ?? '');
        	if (strpos($urlImage, 'images.tokopedia.net') !== false) {
				$response['url_gambar'][$key] = str_replace("100-square", "500-square", $urlImage) ;
			}
        }
        $infoprod	= $content->find('ul[data-testid="lblPDPInfoProduk"] li');
        foreach ($infoprod as $info) {
        	if (strpos($info->plaintext, 'Kondisi:') !== false) {
        		$kondisi = ($info->plaintext ?? '');
        		$response['kondisi'] = str_replace("Kondisi:  ", "", $kondisi) ;
        	}
        	if (strpos($info->plaintext, 'Berat') !== false) {
        		$berat = ($info->plaintext ?? '');
        		$berat = str_replace("Berat Satuan:  ", "", $berat) ;
        		$response['berat']	=  str_replace(" kg ", "", $berat) ;
        	}
        	if (strpos($info->plaintext, 'Kategori') !== false) {
        		$kategori = ($info->plaintext ?? '');
        		$response['kategori'] = str_replace("Kategori:  ", "", $kategori) ;
        	}
        	if (strpos($info->plaintext, 'Etalase') !== false) {
        		$etalase = ($info->plaintext ?? '');
        		$response['etalase'] = str_replace("Etalase:  ", "", $etalase) ;
        	}
        }

        return $response;
    }

    public function getListProduct($url='https://www.tokopedia.com/eseskomputer/etalase/motherboard-amd', $sort='paling_sesuai') 
    {
    	$response = [];
    	$response['items'] = [];
    	$i = 0;
    	$result = true;
    	$page = 1;
        while (true) {
    		$count 		= 0;
    		$fullUrl	= $url."/page/".$page."?perpage=200&sort=".self::tokpedSortArray($sort);
        	$content 	= self::file_get_html($fullUrl);
	        $mainUrl 	= 'https://www.tokopedia.com/';
	        $listprod 	= $content->find('div[data-testid="master-product-card"] div div div a');
	        \Log::channel('debugging')->info("Hit : ".$fullUrl);
			foreach($listprod as $list) {
		        if (!$list->hasAttribute('title') && strpos($list->plaintext, 'Habis') === false) {
					$response['items'][$i]['product_url'] = $mainUrl.$list->href;
					$response['items'][$i]['status'] = $list->plaintext;
					$i++;
					$count++;
		        }
	        }
	        $page++;
	        if ($count==0) {
	        	$result = false;
	            \Log::channel('debugging')->info("Count : ".$count);
	            \Log::channel('debugging')->info("Hit : Done.");
	        	break;
	        }
            \Log::channel('debugging')->info("Count : ".$count);
            \Log::channel('debugging')->info("Hit : Next.");
        }
    	$response['total'] = $i;
        return $response;
    }

    public function getSellerCategory($url) 
    {
    	$response = [];
    	$i = 0;
    	$url = str_replace('/product', '', $url).'/product';
        $content = self::file_get_html($url);
        $mainUrl = 'https://www.tokopedia.com/';
		foreach($content->find('div div div ul li a') as $list) {
			$name = ($list->innertext ?? '');
			$url_category = ($mainUrl.$list->href ?? '');
			if (strpos($name, 'PROMO</div>') === false) {
				$response[$i]['category_name'] = $name;
				$response[$i]['category_url'] = $url_category;
				$response[$i]['total_product'] = 0;
				if ($name != 'Semua Produk') {
					// $response[$i]['total_product'] = ($this->getListProduct($url_category)['total'] ?? 0);
				}
				$i++;
			}
        }
        return $response;
    }

    public function getListProductCurl($url='https://www.tokopedia.com/eseskomputer/product', $sort='paling_sesuai') 
    {
    	$response = [];
    	$response['items'] = [];
    	$i = 0;
    	$result = true;
    	$page = 1;
        while (true) {
    		$count 		= 0;
    		$fullUrl	= $url."/page/".$page."?perpage=10&sort=".self::tokpedSortArray($sort);
    		$check = Guzzle::getCurl($fullUrl);
    		echo "<pre>";print_r($check); echo "</pre>";die();
    		die();
        	$content 	= self::file_get_html($fullUrl);
	        $mainUrl 	= 'https://www.tokopedia.com/';
	        $listprod 	= $content->find('div[data-testid="master-product-card"] div div div a');
	        \Log::channel('debugging')->info("Hit : ".$fullUrl);
			foreach($listprod as $list) {
		        if (!$list->hasAttribute('title') && strpos($list->plaintext, 'Habis') === false) {
					$response['items'][$i]['product_url'] = $mainUrl.$list->href;
					$response['items'][$i]['status'] = $list->plaintext;
					$i++;
					$count++;
		        }
	        }
	        $page++;
	        if ($count==0) {
	        	$result = false;
	            \Log::channel('debugging')->info("Count : ".$count);
	            \Log::channel('debugging')->info("Hit : Done.");
	        	break;
	        }
            \Log::channel('debugging')->info("Count : ".$count);
            \Log::channel('debugging')->info("Hit : Next.");
        }
    	$response['total'] = $i;
        return $response;
    }
}