<?php

require_once __DIR__ . '/BaseController.php';

class TestController extends BaseController
{
    public function showPage()
    {
        $this->render('test');
    }
}
