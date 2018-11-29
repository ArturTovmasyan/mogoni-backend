<?php

namespace App\Services;

use App\Controller\Exception\Exception;

/**
 * Class CurlService
 * @package App\Services
 */
class CurlService
{
    /**
     * This function is used to check request required params
     *
     * @param $urlParams
     * @return array|string
     * @throws Exception
     */
    public function callGithubCurl($urlParams)
    {
        //get auth parameters for get access token
        $github_api_domain = getenv('GITHUB_API_DOMAIN');

        //start curl
        $curl = curl_init();

        //generate curl body for get token
        curl_setopt_array($curl, [
            CURLOPT_URL => $github_api_domain . $urlParams,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_USERAGENT => 'curl/' . curl_version()['version'],
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Authorization: token '.getenv('GITHUB_TOKEN'),
                'content-type: application/json'
            ],
        ]);

        //run curl
        $response = curl_exec($curl);
        $err = curl_error($curl);

        //close curl
        curl_close($curl);

        if ($err) {
            return 'cURL Error #:' . $err;
        }

        return json_decode($response, true);
    }
}