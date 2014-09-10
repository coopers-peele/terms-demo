<?php

namespace CP\Terms\DemoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CPTermsDemoBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
