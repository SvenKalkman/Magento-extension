<?php

namespace MicroCash\Api;

use Exception;
use SimpleXMLElement;
use SoapClient;

ini_set('max_input_time', -1);
ini_set("default_socket_timeout", 10);
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);


class ApiConnection
{
    public function init()
    {
        function DeriveHash($username, $secretkey, $requestid, $lang = 1)
        {
            $data = utf8_encode(strtoupper($username.$secretkey.strval($lang).$requestid));
            $hash = base64_encode(hash('sha256', $data, true ));
            return $hash;
        }

        function GUID()
        {
            mt_srand((double)microtime()*10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// "{"
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12)
                .chr(125);// "}"
            return trim($uuid, '{}');
        }

        $username = 'mctest';
        $request = GUID();
        $filiaal = 2;
        $key = '58CFA851-3A18-472B-91BC-FA24A49E5EEE';

        $header = [];
        $header['Language'] = 1;
        $header['Username'] = $username;
        $header['Filiaal'] = $filiaal;
        $header['RequestID'] = $request;
        $header['Hash'] = DeriveHash($username, $key, $request, '1');

        $params = [];
        $params['pHeader'] = $header;


        $options = array(
            'location' => 'http://sven_magento_devel.twigacloud1.microcash.nl/API/Webshop/Versie1',
            'uri' => 'http://sven_magento_devel.twigacloud1.microcash.nl/API/Webshop/Versie1',
            'trace' => true,
            'keep_alive' => false,
            'connection_timeout' => 10,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'exceptions' => true,
        );

        $soapClient = new SoapClient('http://sven_magento_devel.twigacloud1.microcash.nl/API/Webshop/Versie1?singleWsdl', $options);

        $versionResponse = $soapClient->GetStores($params);

        foreach ($versionResponse->GetStoresResult->Store as $winkel)
        {
            echo "Winkel: ".$winkel->ID." Naam:".$winkel->Name."</br>";
        }

        var_dump($versionResponse);
    }
}










