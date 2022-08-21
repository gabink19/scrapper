<?php

namespace App\Http\Controllers;

use App\Helper\HtmlDomParser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function tokpedSortArray($sort)
    {
        $array = [
            'paling_sesuai' => '23',
            'terbaru' => '2',
            'termahal' => '10',
            'termurah' => '9',
            'penjualan' => '8',
            'ulasan' => '11',
        ];

        return $array[$sort];
    }

    public static function file_get_html(
        $url,
        $use_include_path = false,
        $context = null,
        $offset = 0,
        $maxLen = -1,
        $lowercase = true,
        $forceTagsClosed = true,
        $target_charset = 'UTF-8',
        $stripRN = true,
        $defaultBRText = "\r\n",
        $defaultSpanText = ' ')
    {
        if($maxLen <= 0) { $maxLen = 600000; }

        $dom = new HtmlDomParser(
            null,
            $lowercase,
            $forceTagsClosed,
            $target_charset,
            $stripRN,
            $defaultBRText,
            $defaultSpanText
        );

        $options = array(
            'http' => array(
                'header'  => "User-Agent:MyAgent/1.0\r\n",
                'method'  => "GET",
                'timeout' => 30,
                'ignore_errors' => true,
            ),
        );

        $context = stream_context_create($options);
        /**
         * For sourceforge users: uncomment the next line and comment the
         * retrieve_url_contents line 2 lines down if it is not already done.
         */
        $contents = file_get_contents(
            $url,
            $use_include_path,
            $context,
            $offset,
            $maxLen
        );
        // $contents = retrieve_url_contents($url);

        if (empty($contents) || strlen($contents) > $maxLen) {
            $dom->clear();
            return false;
        }
        return $dom->load($contents, $lowercase, $stripRN);
    }

    public static function str_get_html(
        $str,
        $lowercase = true,
        $forceTagsClosed = true,
        $target_charset = 'UTF-8',
        $stripRN = true,
        $defaultBRText = "\r\n",
        $defaultSpanText = ' ')
    {
        $dom = new HtmlDomParser(
            null,
            $lowercase,
            $forceTagsClosed,
            $target_charset,
            $stripRN,
            $defaultBRText,
            $defaultSpanText
        );

        if (empty($str) || strlen($str) > 600000) {
            $dom->clear();
            return false;
        }

        return $dom->load($str, $lowercase, $stripRN);
    }

    public static function dump_html_tree($node, $show_attr = true, $deep = 0)
    {
        $node->dump($node);
    }
    
    public function messageValidator()
    {
        return [
            'accepted'             => ':attribute must be accepted.',
            'active_url'           => ':attribute is not a valid URL.',
            'after'                => ':attribute must be a date after :date.',
            'after_or_equal'       => ':attribute must be a date after or equal to :date.',
            'alpha'                => ':attribute may only contain letters.',
            'alpha_dash'           => ':attribute may only contain letters, numbers, dashes and underscores.',
            'alpha_num'            => ':attribute may only contain letters and numbers.',
            'array'                => ':attribute must be an array.',
            'before'               => ':attribute must be a date before :date.',
            'before_or_equal'      => ':attribute must be a date before or equal to :date.',
            'between'              => [
                'numeric' => ':attribute must be between :min and :max.',
                'file'    => ':attribute must be between :min and :max kilobytes.',
                'string'  => ':attribute must be between :min and :max characters.',
                'array'   => ':attribute must have between :min and :max items.',
            ],
            'boolean'              => ':attribute field must be true or false.',
            'confirmed'            => ':attribute confirmation does not match.',
            'date'                 => ':attribute is not a valid date.',
            'date_format'          => ':attribute does not match the format :format.',
            'different'            => ':attribute and :other must be different.',
            'digits'               => ':attribute must be :digits digits.',
            'digits_between'       => ':attribute must be between :min and :max digits.',
            'dimensions'           => ':attribute has invalid image dimensions.',
            'distinct'             => ':attribute field has a duplicate value.',
            'email'                => ':attribute must be a valid email address.',
            'exists'               => 'selected :attribute is invalid.',
            'file'                 => ':attribute must be a file.',
            'filled'               => ':attribute field must have a value.',
            'gt'                   => [
                'numeric' => ':attribute must be greater than :value.',
                'file'    => ':attribute must be greater than :value kilobytes.',
                'string'  => ':attribute must be greater than :value characters.',
                'array'   => ':attribute must have more than :value items.',
            ],
            'gte'                  => [
                'numeric' => ':attribute must be greater than or equal :value.',
                'file'    => ':attribute must be greater than or equal :value kilobytes.',
                'string'  => ':attribute must be greater than or equal :value characters.',
                'array'   => ':attribute must have :value items or more.',
            ],
            'image'                => ':attribute must be an image.',
            'in'                   => 'selected :attribute is invalid.',
            'in_array'             => ':attribute field does not exist in :other.',
            'integer'              => ':attribute must be an integer.',
            'ip'                   => ':attribute must be a valid IP address.',
            'ipv4'                 => ':attribute must be a valid IPv4 address.',
            'ipv6'                 => ':attribute must be a valid IPv6 address.',
            'json'                 => ':attribute must be a valid JSON string.',
            'lt'                   => [
                'numeric' => ':attribute must be less than :value.',
                'file'    => ':attribute must be less than :value kilobytes.',
                'string'  => ':attribute must be less than :value characters.',
                'array'   => ':attribute must have less than :value items.',
            ],
            'lte'                  => [
                'numeric' => ':attribute must be less than or equal :value.',
                'file'    => ':attribute must be less than or equal :value kilobytes.',
                'string'  => ':attribute must be less than or equal :value characters.',
                'array'   => ':attribute must not have more than :value items.',
            ],
            'max'                  => [
                'numeric' => ':attribute may not be greater than :max.',
                'file'    => ':attribute may not be greater than :max kilobytes.',
                'string'  => ':attribute may not be greater than :max characters.',
                'array'   => ':attribute may not have more than :max items.',
            ],
            'mimes'                => ':attribute must be a file of type: :values.',
            'mimetypes'            => ':attribute must be a file of type: :values.',
            'min'                  => [
                'numeric' => ':attribute must be at least :min.',
                'file'    => ':attribute must be at least :min kilobytes.',
                'string'  => ':attribute must be at least :min characters.',
                'array'   => ':attribute must have at least :min items.',
            ],
            'not_in'               => 'selected :attribute is invalid.',
            'not_regex'            => ':attribute format is invalid.',
            'numeric'              => ':attribute must be a number.',
            'present'              => ':attribute field must be present.',
            'regex'                => ':attribute format is invalid.',
            'required'             => ':attribute field is required.',
            'required_if'          => ':attribute field is required when :other is :value.',
            'required_unless'      => ':attribute field is required unless :other is in :values.',
            'required_with'        => ':attribute field is required when :values is present.',
            'required_with_all'    => ':attribute field is required when :values is present.',
            'required_without'     => ':attribute field is required when :values is not present.',
            'required_without_all' => ':attribute field is required when none of :values are present.',
            'same'                 => ':attribute and :other must match.',
            'size'                 => [
                'numeric' => ':attribute must be :size.',
                'file'    => ':attribute must be :size kilobytes.',
                'string'  => ':attribute must be :size characters.',
                'array'   => ':attribute must contain :size items.',
            ],
            'string'               => ':attribute must be a string.',
            'timezone'             => ':attribute must be a valid zone.',
            'unique'               => ':attribute has already been taken.',
            'uploaded'             => ':attribute failed to upload.',
            'url'                  => ':attribute format is invalid.'
            ];
    }
}
