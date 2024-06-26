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
     * This function is used to call github API
     *
     * @param $githubUrl
     * @return array|string
     * @throws Exception
     */
    public function callGithubApi($githubUrl)
    {
        //start curl
        $curl = curl_init();

        //generate curl body for get token
        curl_setopt_array($curl, [
            CURLOPT_URL => getenv('GITHUB_API_DOMAIN') . $githubUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_USERAGENT => 'curl/' . curl_version()['version'],
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Authorization: token ' . getenv('GITHUB_TOKEN'),
                'Accept: application/vnd.github.cloak-preview',
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

        $response = json_decode($response, true);

        // check if github api return not found message
        if (\array_key_exists('message', $response)) {
            $response = [];
        }

        return $response;
    }

    /**
     * This functions is used to generate Github API with params
     *
     * @param $repoUrl
     * @param $githubApi
     * @return string
     */
    public function generateGithubRepoApi($repoUrl, $githubApi): string
    {
        $repoUrlData = explode('/', $repoUrl);

        return sprintf($githubApi, $repoUrlData[3], $repoUrlData[4]);
    }
    /**
     * This functions is used to generate Github profile data API
     *
     * @param $profileUrl
     * @param $githubApi
     * @param $page
     * @return string
     */
    public function generateProfileDataApi($profileUrl, $githubApi, $page = 1): string
    {
        $repoUrlData = explode('/', $profileUrl);

        return sprintf($githubApi, $repoUrlData[3], $page);
    }
}