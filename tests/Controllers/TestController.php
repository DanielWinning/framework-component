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

    /**
     * @return Response
     */
    public function respondsWithJson(): Response
    {
        return $this->json([
            'title' => 'JSON Response',
        ]);
    }

    /**
     * @return Response
     */
    public function respondsWithRender(): Response
    {
        return $this->render('render-test');
    }
}