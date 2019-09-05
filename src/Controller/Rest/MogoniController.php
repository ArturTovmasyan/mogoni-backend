<?php

namespace App\Controller\Rest;

use App\Controller\Exception\Exception;
use App\Entity\Github;
use App\Entity\PublishProduct;
use App\Services\ValidateService;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Finder\Finder;
// Include the Response and ResponseHeaderBag
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

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
            $product->setScreenshots($requestData['screenshots'] ?? array());
            $product->setExamples($requestData['examples'] ?? array());
            $product->setInstallations($requestData['installations'] ?? array());

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
        $uniqueUrl = $webHost.'/published/%s/%s/%s';
        $uniqueUrl = sprintf($uniqueUrl, $product->getId(), str_replace(' ', '_', $requestData['author_name']),  str_replace(' ', '_', $requestData['repo_name']));

        // generate response data
        $response = [
            'id' => $product->getId(),
            'author_name' => $requestData['author_name'],
            'repo_name' => $requestData['repo_name'],
            'unique_url' => $uniqueUrl
        ];

        return $this->json(['status' => JsonResponse::HTTP_CREATED, 'data' => $response], JsonResponse::HTTP_CREATED);
    }

    /**
     * This function is used to save published product
     *
     * @Route("/api/v1/publish/product/{id}", methods={"GET"}, name="mogoni_get_publish_product", requirements={"id"="\d+"})
     *
     * @param int $id
     * @param SerializerInterface $serializer
     *
     * @return JsonResponse
     * @throws
     */
    public function getPublishProductAction(int $id, SerializerInterface $serializer): JsonResponse
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var PublishProduct $product */
        $product = $entityManager->getRepository(PublishProduct::class)->find($id);

        // generate filters data body
        $userContent = $serializer->serialize($product, 'json', SerializationContext::create()->setGroups(['publish']));
        $decodeData = json_decode($userContent, true);

        // Decode json string fields
        $decodeData['examples'] = json_decode($decodeData['examples'], true);
        $decodeData['installations'] = json_decode($decodeData['installations'], true);
        $decodeData['screenshots'] = json_decode($decodeData['screenshots'], true);
        $decodeData['github']['license'] = json_decode($decodeData['github']['license'], true);
        $userContent = json_encode($decodeData);

        // generate JsonResponse
        $response = new JsonResponse();
        $response->setContent($userContent);

        return $response;
    }

    /**
     * This function is used to download product README.md
     *
     * @Route("/api/v1/download/product", methods={"POST"}, name="mogoni_download_product")
     *
     * @param Request $request
     * @param ValidateService $validateService
     *
     * @return JsonResponse
     * @throws
     */
    public function postDownloadProductAction(Request $request, ValidateService $validateService): Response
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        // start DB transaction
        $entityManager->getConnection()->beginTransaction();
        $requestData = $request->request->all();

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
        $product->setScreenshots($requestData['screenshots'] ?? array());
        $product->setExamples($requestData['examples'] ?? array());
        $product->setInstallations($requestData['installations'] ?? array());


        // The dinamically created content of the file
        $content = $this->getReadmeTemplate();
        $fileContent = $this->replacePlaceholders($content, $product);
        
        // Return a response with a specific content
        $response = new Response($fileContent);

        // Provide a name for your file with extension
        $filename = 'README.md';

        // Create the disposition of the file
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        // Set the content disposition
        $response->headers->set('Access-Control-Expose-Headers', "Content-Disposition");
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', "application/octet-stream");

        // Dispatch request
        return $response;
    }

    private function getReadmeTemplate() 
    {
        $finder = new Finder();
        $finder->in(__DIR__);

        $finder->path('Data/README.md');
        foreach ($finder as $file) {
            // print_r($file);
            $contents = $file->getContents();
            return $contents;
        }
    }

    private function replacePlaceholders($content, $product) 
    {
        $content = str_replace('{author_name}', $product->getAuthorName(), $content);
        $content = str_replace('{repo_name}', $product->getRepoName(), $content);
        $content = str_replace('{goal}', $product->getGoal(), $content);
        $content = str_replace('{roadmap}', $product->getRoadmap(), $content);
        $content = str_replace('{contact}', $product->getContact(), $content);

        $screenshotsTable = $this->getScreenshotsTable($product->getScreenshots());
        $content = str_replace('{screenshots}', $screenshotsTable, $content);
        // TODO: Installation blocks
        // TODO: Examples code blocks
        return $content;
    }

    private function getScreenshotsTable($screenshotsData) {
        $screenshots = $this->getScreenshots($screenshotsData);
        $titles = $this->getScreenshotsTitles($screenshotsData);

        $array = array();
        foreach ($screenshotsData as $value) {
            $array[] = "--";
        }
        $separated = implode(" | ", $array);
        $separated = "|" . $separated . "|";
        
        $content = <<<CONTENT
$screenshots
$separated
$titles
CONTENT;
        return $content;
    }

    private function getScreenshots($screenshotsData) 
    {
        $templateScreenshot = "![{title}]({imageUrl})";

        $array = array();
        foreach ($screenshotsData as $screenshot) {
            $title = $screenshot['title'];
            $imageUrl = $screenshot['imageUrl'];
            $newStr = str_replace('{title}', $title, $templateScreenshot);
            $newStr = str_replace('{imageUrl}', $imageUrl, $newStr);
            $array[] = $newStr;
        }
        $separated = implode(" | ", $array);
        $separated = "| " . $separated . " |";

        return $separated;
    }

    private function getScreenshotsTitles($screenshotsData)
    {
        $titles = "";

        $templateTitle = "{title}";

        $array = array();
        foreach ($screenshotsData as $screenshot) {
            $title = $screenshot['title'];
            $imageUrl = $screenshot['imageUrl'];
            $newStr = str_replace('{title}', $title, $templateTitle);
            $array[] = $newStr;
        }
        $separated = implode(" | ", $array);
        $separated = "| " . $separated . " |";

        return $separated;
    }
}