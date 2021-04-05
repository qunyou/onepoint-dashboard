<?php

namespace Onepoint\Dashboard\Services;

use Jenssegers\Agent\Agent;
use Onepoint\Dashboard\Services\RouteService;
use Onepoint\Base\Entities\BrowserAgent;
use Onepoint\Dashboard\Presenters\FormPresenter;

class BaseService
{
    /**
     * 取得不含頁數的 QueryString 陣列
     */
    public static function getQueryString($include_page = false, $toString = false, $exclude = [])
    {
        $qs = $_GET;
        if (!$include_page) {
            unset($qs['page']);
        }
        if (count($exclude)) {
            foreach ($exclude as $item) {
                unset($qs[$item]);
            }
        }
        if ($toString) {
            return http_build_query($qs);
        } else {
            return $qs;
        }
    }

    /**
     * 網址文字slug處理
     */
    public static function slug($title, $separator = '-', $language = 'en')
    {
        // $title = $language ? static::ascii($title, $language) : $title;

        // Convert all dashes/underscores into separator
        $flip = $separator === '-' ? '_' : '-';

        $title = preg_replace('!['.preg_quote($flip).']+!u', $separator, $title);

        // Replace @ with the word 'at'
        $title = str_replace('@', $separator.'at'.$separator, $title);

        // Remove all characters that are not the separator, letters, numbers, or whitespace.
        $title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', mb_strtolower($title, 'UTF-8'));

        // Replace all separator characters and whitespace by a single separator
        $title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);
        return trim($title, $separator);
    }

    /**
     * Agent
     */
    public static function agent()
    {
        $agent = new Agent;
        $datas['platform'] = $agent->platform();
        $datas['platform_version'] = $agent->version($datas['platform']);
        $datas['browser'] = $agent->browser();
        $datas['browser_version'] = $agent->version($datas['browser']);
        $datas['languages'] = implode(', ', $agent->languages());
        $datas['device'] = $agent->device();
        $datas['robot'] = $agent->robot();

        /**
         * 其他可用方法
         */
        // 作業系統
        // Agent::is('Windows');
        // Agent::is('Firefox');
        // Agent::is('iPhone');
        // Agent::is('OS X');

        // 瀏覽器判斷
        // Agent::isAndroidOS();
        // Agent::isNexus();
        // Agent::isSafari();

        // 瀏覽器尺寸
        // $agent->isDesktop();
        // $agent->isPhone();
        // $agent->isMobile();
        // $agent->isTablet();
        $ip_info = Self::ipInfo("Visitor", "Location");
        if (is_null($ip_info)) {
            $datas['ip'] = $_SERVER["REMOTE_ADDR"];
        } else {
            $datas['ip'] = $ip_info['ip'];
            $datas['city'] = $ip_info['city'];
            $datas['state'] = $ip_info['state'];
            $datas['country'] = $ip_info['country'];
            $datas['address'] = $ip_info['address'];
            $datas['country_code'] = $ip_info['country_code'];
            $datas['continent'] = $ip_info['continent'];
            $datas['continent_code'] = $ip_info['continent_code'];
            $datas['url_full'] = url()->full();
            $datas['url_previous'] = url()->previous();
        }
        BrowserAgent::create($datas);
    }

    /**
     * 以IP取得瀏覽者國家資料
     */
    public static function ipInfo($ip = NULL, $purpose = "location", $deep_detect = TRUE)
    {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $address = implode(", ", array_reverse($address));
                        $output = array(
                            "ip"             => @$ip,
                            "city"           => @$ipdat->geoplugin_city,
                            "state"          => @$ipdat->geoplugin_regionName,
                            "country"        => @$ipdat->geoplugin_countryName,
                            "address"        => @$address,
                            "country_code"   => @$ipdat->geoplugin_countryCode,
                            "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }
}