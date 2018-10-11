<?php

namespace App\Service;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

abstract class AbstractBaseService
{
    /**
     * Validate data and get violations (if any)
     * Documentation: https://symfony.com/doc/current/validation/raw_values.html
     *
     * @param array $data    which contains data to validate
     * @param array $rules   Specifies which keys in data and how to validate. All keys will be validated by default.
     * @param array $options Additional options to use during validation
     *
     * @return ConstraintViolationListInterface
     */
    protected function getViolations(array $data, array $rules = [], $options = []): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();
        $params = [
            'fields' => $rules,
            // even though it's anomaly, currently we don't care if there are unrelated fields in $data
            'allowExtraFields' => true,
        ];
        foreach ($options as $optionName => $optionValue) {
            $params[$optionName] = $optionValue;
        }
        $constraint = new Assert\Collection($params);
        $violations = $validator->validate($data, $constraint);

        return $violations;
    }

    /**
     * Convert array of violations (if any) to string with specified delimiter
     *
     * @param ConstraintViolationListInterface $violations List of violations to extract error messages from
     *
     * @return array
     */
    protected function getErrors(ConstraintViolationListInterface $violations): array
    {
        return array_map(
            function (ConstraintViolation $violation) {
                return $violation->getMessage();
            },
            iterator_to_array($violations)
        );
    }
}