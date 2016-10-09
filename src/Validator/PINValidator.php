<?php

namespace Fousky\Component\Validator;

use Fousky\Component\PINUtils;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @author Lukáš Brzák <lukas.brzak@aquadigital.cz>
 */
class PINValidator extends ConstraintValidator
{
    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (empty($value)) {
            return;
        }

        $util = new PINUtils($value);

        if (!$util->isValid()) {
            /** @var \Symfony\Component\Validator\Context\ExecutionContextInterface $context */
            $context = $this->context;

            $context
                ->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
