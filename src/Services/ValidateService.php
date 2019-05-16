<?php

namespace App\Services;

use App\Controller\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ValidateService
 * @package App\Services
 */
class ValidateService
{
    /** @var ValidatorInterface $validator */
    protected $validator;

    /**
     * ValidateService constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * This function is used to check model validation
     *
     * @param $model
     * @param array $group
     * @throws Exception
     */
    public function checkValidation($model, $group = []): void
    {
        $errors = $this->validator->validate($model, null, $group);
        $returnErrors = [];

        // get errors
        if ($errors->count() > 0) {

            foreach ($errors as $error) {
                $returnErrors[$error->getPropertyPath()] = $error->getMessage();
            }

            throw new Exception(
                'Validation Error',
                JsonResponse::HTTP_BAD_REQUEST,
                ['errors' => $returnErrors]
            );
        }
    }

    /**
     * This function is used to check request required params
     *
     * @param $params
     * @throws Exception
     */
    public function checkRequiredParams($params): void
    {
        foreach ($params as $key => $param) {

            if ($key && $param !== 0 && !$param) {
                throw new Exception(
                    'Invalid Request Data',
                    JsonResponse::HTTP_BAD_REQUEST
                );
            }
        }
    }
}