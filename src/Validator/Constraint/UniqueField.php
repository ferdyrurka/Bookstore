<?php


namespace App\Validator\Constraint;

use App\Validator\UniqueFieldValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueField
 * @package App\Validator
 *
 * @Annotation
 */
class UniqueField extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This value already exists';

    /**
     * @var string
     */
    public $entityClass;

    /**
     * @var string
     */
    public $field;

    /**
     * @var bool
     */
    public $checkId = true;

    /**
     * @return string[]
     */
    public function getRequiredOptions(): array
    {
        return array(
            'entityClass',
            'field',
        );
    }
    /**
     * @return string
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return UniqueFieldValidator::class;
    }
}
