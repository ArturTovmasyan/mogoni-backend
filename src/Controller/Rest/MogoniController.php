<?php

namespace App\Controller\Rest;

use App\Controller\Exception\Exception;
use App\Entity\Github;
use App\Entity\PublishProduct;
use App\Services\ValidateService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MogoniController
 * @package App\Controller\Rest
 */
class MogoniController extends AbstractController
{
    /**
     * This function is used to save published product
     *
     * @Route("/api/v1/publish/product", methods={"POST"}, name="mogoni_publish_product")
     *
     * @param Request $request
     * @param ValidateService $validateService
     *
     * @return JsonResponse
     * @throws
     */
    public function postPublishProductAction(Request $request, ValidateService $validateService): JsonResponse
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        // start DB transaction
        $entityManager->getConnection()->beginTransaction();
        $requestData = $request->request->all();

        try {

            /** @var PublishProduct $product */
            $product = $entityManager->getRepository(PublishProduct::class)->findOneBy(['repoName' => $requestData['repo_name']]);

            // create new published product
            if (!$product) {
                $product = new PublishProduct();
            }

            /** @var Github $github */
            $github = $entityManager->getRepository(Github::class)->find($requestData['id']);

            $product->setGithub($github);
            $product->setAuthorName($requestData['author_name'] ?? '');
            $product->setRepoName($requestData['repo_name'] ?? '');
            $product->setTitle($requestData['title'] ?? '');
            $product->setDescription($requestData['description'] ?? '');
            $product->setGoal($requestData['goal'] ?? '');
            $product->setRoadmap($requestData['roadmap'] ?? '');
            $product->setContact($requestData['contact'] ?? '');
            $product->setScreenshot($requestData['screenshot'] ?? array());
            $product->setInstallation($requestData['installation'] ?? array());
            $product->setUsag($requestData['usage'] ?? array());

            // check data validation and save it
            $validateService->checkValidation($product);
            $entityManager->persist($product);
            $entityManager->flush($product);
            $entityManager->getConnection()->commit();

        } catch (Exception $e) {
            $entityManager->getConnection()->rollBack();
            throw new Exception($e->getMessage(), $e->getCode(), $e->getData() ?? []);
        }

        // get web host
        $webHost = getenv('WEB_HOST');

        // generate unique url
        $uniqueUrl = $webHost.'/published-repo/%s/%s/%s';
        $uniqueUrl = sprintf($uniqueUrl, $product->getId(), str_replace(' ', '_', $requestData['author_name']),  str_replace(' ', '_', $requestData['repo_name']));

        return $this->json(['status' => JsonResponse::HTTP_CREATED, 'data' => ['unique_url' => $uniqueUrl]], JsonResponse::HTTP_CREATED);
    }
}