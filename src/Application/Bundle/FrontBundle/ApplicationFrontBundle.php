<?php

namespace Application\Bundle\FrontBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ApplicationFrontBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
