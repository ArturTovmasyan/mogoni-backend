<?php

namespace App\Controller\Rest;

use App\Controller\Exception\Exception;
use App\Entity\Github;
use App\Entity\Profile;
use App\Services\CurlService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GithubController
 * @package App\Controller\Rest
 */
class GithubController extends AbstractController
{
    /**
     * This function is used to get Repo data by github API
     *
     * @Route("/api/v1/repo/data", methods={"POST"}, name="mogoni_github_repo_data")
     *
     * @param CurlService $curlService
     * @param Request $request
     *
     * @return JsonResponse
     * @throws
     */
    public function getRepoDataAction(CurlService $curlService, Request $request): JsonResponse
    {
        $repoData = [];

        try {
            // get last 2 month date
            $date = date('Y-m-d', strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '-2 month'));
            $repoUrl = $request->get('repoUrl');

            // get repository information from Github API-s
            $this->getGlobalInfo($repoUrl, $repoData, $curlService);

            $this->getLanguageInfo($repoUrl, $repoData, $curlService);

            $this->getIssuesInfo($repoUrl, $date, $repoData, $curlService);

            $this->getCommitsInfo($repoUrl, $date, $repoData, $curlService);

            $this->getReadmeInfo($repoUrl, $repoData, $curlService);

            $this->getLicenseInfo($repoUrl, $repoData, $curlService);

            $this->saveGithubData($repoData);

        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e->getData() ?? []);
        }

        return $this->json([$repoData], JsonResponse::HTTP_OK);
    }

    /**
     * This function is used to get user profile data by url
     *
     * @Route("/api/v1/profile/data", methods={"POST"}, name="mogoni_github_profile_data")
     *
     * @param CurlService $curlService
     * @param Request $request
     *
     * @return JsonResponse
     * @throws
     */
    public function getProfileDataAction(CurlService $curlService, Request $request): JsonResponse
    {
        $profileData = [];

        try {
            $profileUrl = $request->get('profileUrl');

            // get repository information from Github API-s
            $this->getProfileData($profileUrl, $profileData, $curlService);
            $this->saveProfileData($profileData);

        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e->getData() ?? []);
        }

        return $this->json([$profileData], JsonResponse::HTTP_OK);
    }

    /**
     * @param $repoUrl
     * @param $repoData
     * @param CurlService $curlService
     */
    private function getGlobalInfo($repoUrl, &$repoData, CurlService $curlService): void
    {
        $repoInfoApi = $curlService->generateGithubRepoApi($repoUrl, 'repos/%s/%s');
        $githubData = $curlService->callGithubApi($repoInfoApi);
        if (\count($githubData) > 0) {
            $repoData = [
                'title' => explode('/', $githubData['name'])[0],
                'subtitle' => $githubData['description'],
                'html_url' => $githubData['html_url'],
                'star' => $githubData['stargazers_count'],
                'owner' => [
                    'name' => $githubData['owner']['login'],
                    'avatar_url' => $githubData['owner']['avatar_url'],
                    'html_url' => $githubData['owner']['html_url'],
                ],
                'license' => [
                    'key' => $githubData['license']['key'],
                    'name' => $githubData['license']['name'],
                    'spdx_id' => $githubData['license']['spdx_id'],
                    'url' => $githubData['license']['url'],
                ]

            ];
        }
    }

    /**
     * @param $repoUrl
     * @param $repoData
     * @param CurlService $curlService
     */
    private function getLanguageInfo($repoUrl, &$repoData, CurlService $curlService): void
    {
        $repoLanguageApi = $curlService->generateGithubRepoApi($repoUrl, 'repos/%s/%s/languages');
        $githubData = $curlService->callGithubApi($repoLanguageApi);

        if (\count($githubData) > 0) {
            $language = array_keys($githubData);
            $repoData['language'] = reset($language);
        }
    }

    /**
     * @param $repoUrl
     * @param $repoData
     * @param CurlService $curlService
     */
    private function getReadmeInfo($repoUrl, &$repoData, CurlService $curlService): void
    {
        $repoReadmeApi = $curlService->generateGithubRepoApi($repoUrl, 'repos/%s/%s/readme');
        $githubData = $curlService->callGithubApi($repoReadmeApi);

        if (\count($githubData) > 0) {
            $repoData['readme'] = [
                'url' => $githubData['html_url'] ?? '',
                'content' => isset($githubData['content']) ? base64_decode($githubData['content']) : ''
            ];
        }
    }

    /**
     * @param $repoUrl
     * @param $repoData
     * @param CurlService $curlService
     */
    private function getLicenseInfo($repoUrl, &$repoData, CurlService $curlService): void
    {
        $repoLicenseApi = $curlService->generateGithubRepoApi($repoUrl, 'repos/%s/%s/license');
        $githubData = $curlService->callGithubApi($repoLicenseApi);

        if (\count($githubData) > 0) {
            $repoData['license']['url'] = $githubData['html_url'] ?? $githubData['documentation_url'] ?? '';
            $repoData['license']['content'] = isset($githubData['content']) ? base64_decode($githubData['content']) : '';
        }
    }

    /**
     * @param $repoUrl
     * @param $date
     * @param $repoData
     * @param CurlService $curlService
     */
    private function getIssuesInfo($repoUrl, $date, &$repoData, CurlService $curlService): void
    {
        // opened issues count
        $repoOpenedIssuesApi = $curlService->generateGithubRepoApi(
            $repoUrl,
            'search/issues?q=repo:%s/%s+type:issue+state:open+created:>' . $date
        );

        $githubData = $curlService->callGithubApi($repoOpenedIssuesApi);
        $repoData['issues']['opened'] = $githubData['total_count'] ?? 0;

        // closed issues count
        $repoClosedIssuesApi = $curlService->generateGithubRepoApi(
            $repoUrl,
            'search/issues?q=repo:%s/%s+type:issue+state:closed+closed:>' . $date
        );

        $githubData = $curlService->callGithubApi($repoClosedIssuesApi);
        $repoData['issues']['closed'] = $githubData['total_count'] ?? 0;
    }

    /**
     * @param $repoUrl
     * @param $date
     * @param $repoData
     * @param CurlService $curlService
     */
    private function getCommitsInfo($repoUrl, $date, &$repoData, CurlService $curlService): void
    {
        $repoCommitsApi = $curlService->generateGithubRepoApi($repoUrl, 'search/commits?q=repo:%s/%s+sort:committer-date+committer-date:>=' . $date);
        $githubData = $curlService->callGithubApi($repoCommitsApi);

        $repoData['commits']['last2Month'] = $githubData['total_count'] ?? 0;

        $currentDate = new \DateTime();
        $currentDate = $currentDate->format('Y-m-d');

        $repoCommitsApi = $curlService->generateGithubRepoApi($repoUrl,
            'search/commits?q=repo:%s/%s+sort:committer-date+committer-date:<=' . $currentDate);
        $githubData = $curlService->callGithubApi($repoCommitsApi);

        if ($githubData['total_count'] > 0) {
            $repoData['commits']['total'] = $githubData['total_count'];
            $repoData['commits']['lastDate'] = $githubData['items'][0]['commit']['committer']['date'] ?? null;
        }
    }

    /**
     * @param $profileUrl
     * @param $profileData
     * @param CurlService $curlService
     */
    private function getProfileData($profileUrl, &$profileData, CurlService $curlService): void
    {
        // Github profile info data API
        $profileDataApi = $curlService->generateProfileDataApi($profileUrl, 'users/%s/repos');
        $userData = $curlService->callGithubApi($profileDataApi);

        if (\count($userData) > 0) {

            $firstResult = reset($userData);

            // get profile data by request
            $profileData['username'] = $firstResult['owner']['login'] ?? '';
            $profileData['avatar_url'] = $firstResult['owner']['avatar_url'] ?? '';
            $profileData['url'] = $firstResult['owner']['html_url'] ?? '';

            foreach ($userData as $data) {

                $repoUrl = $data['html_url'];
                // TODO will be use in future
//                $this->getLanguageInfo($repoUrl, $repoData, $curlService);

                $profileData['repo_lists'][] = [
//                    'language' => $repoData['language'] ?? '',
                    'name' => $data['name'],
                    'url' => $repoUrl
                ];
//                $repoData = [];
            }
        }
    }

    /**
     * @param $githubData
     */
    private function saveGithubData($githubData): void
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var Github $github */
        $github = $entityManager->getRepository(Github::class)->findOneBy(['url' => $githubData['html_url']]);

        // create github data
        if (!\is_object($github)) {
            $github = new Github();
        }

        // get last commit date and convert to object if exist
        $lastCommitDate = isset($githubData['commits']['lastDate']) ? new \DateTime($githubData['commits']['lastDate']) : null;

        $github->setTitle($githubData['title']);
        $github->setSubtitle($githubData['subtitle']);
        $github->setUrl($githubData['html_url']);
        $github->setStarsCount($githubData['star']);
        $github->setOwnerName($githubData['owner']['name']);
        $github->setMainLanguage($githubData['language']);
        $github->setOwnerAvatarUrl($githubData['owner']['avatar_url']);
        $github->setOwnerGithubUrl($githubData['owner']['html_url']);
        $github->setClosedIssuesCount($githubData['issues']['closed']);
        $github->setOpenIssueCount($githubData['issues']['opened']);
        $github->setCommitsCount($githubData['commits']['last2Month']);
        $github->setAllCommitCount($githubData['commits']['total']);
        $github->setLicense($githubData['license']);
        $github->setReadme($githubData['readme']);

        if ($lastCommitDate) {
            $github->setLastCommitDate($lastCommitDate);
        }

        $entityManager->persist($github);
        $entityManager->flush();
    }

    /**
     * @param $profileData
     */
    private function saveProfileData($profileData): void
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var Profile $profile */
        $profile = $entityManager->getRepository(Profile::class)->findOneBy(['username' => $profileData['username']]);

        // create github data
        if (!\is_object($profile)) {
            $profile = new Profile();
        }

        $profile->setUsername($profileData['username']);
        $profile->setUrl($profileData['url']);
        $profile->setAvatarUrl($profileData['avatar_url']);
        $profile->setRepoList($profileData['repo_lists']);

        $entityManager->persist($profile);
        $entityManager->flush();
    }
}