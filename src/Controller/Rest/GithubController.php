<?php

namespace App\Controller\Rest;

use App\Controller\Exception\Exception;
use App\Services\CurlService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @return JsonResponse
     * @throws
     */
    public function getRepoData(CurlService $curlService): JsonResponse
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        try {
            $repoData = [];

            // get repo full data by name
            $githubData = $curlService->callGithubCurl('repos/SNStatComp/s4sroboto');

            if (\count($githubData) > 0) {
                $repoData = [
                    'name' => $githubData['name'],
                    'full_name' => $githubData['full_name'],
                    'starCount' => $githubData['stargazers_count'],
                    'owner' => [
                        'name' => $githubData['owner']['login'],
                        'avatar_url' => $githubData['owner']['avatar_url'],
                    ]
                ];
            }

            $githubData = $curlService->callGithubCurl('repos/SNStatComp/S4Sroboto/languages');

            $language = array_keys($githubData);
            $repoData['language'] = reset($language);

            // TODO will be save end data in DB
//            $newGithubData = new GithubData();
//            $newGithubData->setData(json_encode($githubData));
//            $newGithubData->setName($repoName);
//            $entityManager->persist($newGithubData);
//            $entityManager->flush();
//            $entityManager->getConnection()->commit();

        } catch (Exception $e) {
            $entityManager->getConnection()->rollBack();
            throw new Exception($e->getMessage(), $e->getCode(), $e->getData() ?? []);
        }

        return new JsonResponse(
            [
                $repoData
            ], JsonResponse::HTTP_OK);
    }
}