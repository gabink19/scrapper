<?php

namespace App\Tools;

use App\Helper\HtmlDomParser;
use Rap2hpoutre\FastExcel\FastExcel;

class Tokopedia 
{
    /**
     * Get Owner Shop ID
     * @param  String $name Name of the Owner Shop
     * @return Integer $val Owner Shop ID
     */
    public static function getOwner($url) 
    { 
        $shop_domain = explode('/', $url);
        $shop_domain = $shop_domain[3];

        // Initialize a cURL session
        $header = array(
            'accept:application/json, text/javascript, */*; q=0.01',
            'accept-language:en-US,en;q=0.8,id;q=0.6,ms;q=0.4',
            'origin:https://www.tokopedia.com',
            'referer:'.$url,
            'user-agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://ace.tokopedia.com/search/v1/shop?q=".$shop_domain."&ob=11&start=0&rows=1&full_domain=www.tokopedia.com&scheme=https&source=shop_product");
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); //timeout in seconds
        $data = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);
        if (isset($error_msg)) {
            echo "<pre>";print_r($error_msg); echo "</pre>";die();
        }

        $response = json_decode($data,1);
        foreach ($response['data'] as $key => $value) {
            $data = $value;
        }

        return $data;
    }

    public static function curlTokped($url)
    {   
        // Owner Shop ID
        $id = self::getOwnerID($url);
        // Initialize a cURL session
        $header = array(
            'accept:application/json, text/javascript, */*; q=0.01',
            'accept-language:en-US,en;q=0.8,id;q=0.6,ms;q=0.4',
            'origin:https://www.tokopedia.com',
            'referer:'.$url,
            'user-agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36'
        );
        $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, "https://ace.tokopedia.com/search/product/v3?shop_id=".$id."&rows=15000&start=0&full_domain=www.tokopedia.com&scheme=https&device=desktop&source=shop_product&ob=2");
        curl_setopt($ch, CURLOPT_URL, "https://ace.tokopedia.com/search/v1/product?shop_id=".$id['id']."&ob=2&start=0&rows=999999&full_domain=www.tokopedia.com&scheme=https&source=shop_product");
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); //timeout in seconds
        $data = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);
        if (isset($error_msg)) {
            echo "<pre>";print_r($error_msg); echo "</pre>";die();
        }
        return $data;
    }

    public static function convertToAkulaku($data,$request)
    {
        $list = $data['items'] ;
        $filename = $request['nama_file'].".xlsx";
        
        ob_end_clean();
        // // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');

        // // It will be called file.xls
        header('Content-Disposition: attachment; filename="'. $filename .'"');
        
        // // from stackoverflow
        header("Pragma: no-cache");
        header("Expires: 0");

        return (new FastExcel($list))->download($filename, function($filler) use ($request){
            $detail = $filler['detail'];
            $harga = ($detail['harga_asli']=='')?$detail['harga_produk']:$detail['harga_asli'];
            $harga = str_replace('Rp', '', $harga);
            $harga_asli= (int)str_replace('.', '', $harga);
            $mark_up = round($harga_asli*$request['mark_up'])/100;
            $harga = $harga_asli+$mark_up;
            $gambar = [];
            foreach($detail['url_gambar'] as $k => $val){
                $gambar[$k] = $val;
            }
            return [
                '*Kategori ID' => ((string)$detail['kategori'] ?? ''),
                '*Nama Produk' => ((string)$detail['nama_produk'] ?? ''),
                '*Harga（Rp）' => ((string)$harga ?? ''),
                '*Jumlah' => ((string)$request['stok'] ?? ''),
                '*Produk SKU' => (isset($detail['sku']))?(string)$detail['sku']:1,
                '*Merek' => (isset($detail['merk']))?(string)$detail['merk']:'Other',
                'Spesifikasi' => (isset($detail['spesifikasi']))?(string)$detail['spesifikasi']:'',
                '*Gambar Utama URL' => (isset($gambar[0]))?(string)$gambar[0]:'',
                'Gambar Banner 1' => (isset($gambar[1]))?(string)$gambar[1]:'',
                'Gambar Banner 2' => (isset($gambar[2]))?(string)$gambar[2]:'',
                'Gambar Banner 3' => (isset($gambar[3]))?(string)$gambar[3]:'',
                'Gambar Banner 4' => (isset($gambar[4]))?(string)$gambar[4]:'',
                'Gambar Banner 5' => (isset($gambar[5]))?(string)$gambar[5]:'',
                'SKU Gambar' => (isset($detail['sku_gambar']))?(string)$detail['sku_gambar']:'',
                'Foto Produk Berlatar Putih' => (isset($detail['white']))?(string)$detail['white']:'',
                '*Deskripsi Produk' => (isset($detail['deskripsi']))?(string)$detail['deskripsi']:'',
                'Rincian Gambar 1' => (isset($detail['rincian_gambar1']))?(string)$detail['rincian_gambar1']:'',
                'Rincian Gambar 2' => (isset($detail['rincian_gambar2']))?(string)$detail['rincian_gambar2']:'',
                'Rincian Gambar 3' => (isset($detail['rincian_gambar3']))?(string)$detail['rincian_gambar3']:'',
                'Rincian Gambar 4' => (isset($detail['rincian_gambar4']))?(string)$detail['rincian_gambar4']:'',
                'Rincian Gambar 5' => (isset($detail['rincian_gambar5']))?(string)$detail['rincian_gambar5']:'',
                '*ID Template ongkir' => (isset($detail['ongkir']))?(string)$detail['ongkir']:'0',
                '*Berat (KG)' => (isset($detail['berat']))?(string)$detail['berat']:'1',
                '*Panjang (CM)' => (isset($detail['panjang']))?(string)$detail['panjang']:'10',
                '*Lebar (CM)' => (isset($detail['lebar']))?(string)$detail['lebar']:'10',
                '*Tinggi (CM)' => (isset($detail['tinggi']))?(string)$detail['berat']:'10',
            ];
        });
    }
}