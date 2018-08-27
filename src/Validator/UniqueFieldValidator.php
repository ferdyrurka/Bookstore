<?php


namespace App\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class UniqueFieldValidator
 * @package App\Validator
 */
class UniqueFieldValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * UniqueFieldValidator constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        $entityRepository = $this->entityManager->getRepository($constraint->entityClass);

        $field = 'get' . $constraint->field;

        $searchResult = $entityRepository->findOneBy([
            $constraint->field => $value->$field()
        ]);

        if ($constraint->checkId === true) {
            if ($id = $value->getId()) {
                if (empty($searchResult) || $id === $searchResult->getId()) {
                    return;
                }
            }
        }

        if ($searchResult) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
