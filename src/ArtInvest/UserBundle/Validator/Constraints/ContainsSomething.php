<?php 

namespace UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsSomething extends Constraint
{
    public $message = 'The string "%string%" is empty!';
}