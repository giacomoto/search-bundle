<?php

namespace Luckyseven\Bundle\LuckysevenSearchBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class LuckysevenSearchBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
