<?php

namespace Fousky\Component\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Czech personal identification number constraint EG. '880101/2121'
 *
 * @author Lukáš Brzák <lukas.brzak@aquadigital.cz>
 *
 * @Annotation
 */
class PIN extends Constraint
{
    public $message = 'Zadaná hodnota není platné rodné číslo.';
}
