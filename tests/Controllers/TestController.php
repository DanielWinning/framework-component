<?php

namespace Luma\Tests\Controllers;

use Luma\Framework\Controller\LumaController;
use Luma\HttpComponent\Response;
use Luma\HttpComponent\StreamBuilder;

class TestController extends LumaController
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return $this->respond('Index');
    }
}