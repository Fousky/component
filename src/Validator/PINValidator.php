<?php declare(strict_types = 1);

namespace Fousky\Component\Validator;

use Fousky\Component\PINUtils;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class PINValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (empty($value)) {
            return;
        }

        if (!is_string($value)) {
            throw new \RuntimeException(sprintf(
                'Requried null|string value for constraint %s',
                get_class($constraint)
            ));
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
