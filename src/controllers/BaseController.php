<?php

class BaseController
{
    protected function render($view_name, $data = [])
    {
        extract($data);
        include BASE_PATH . 'views/' . $view_name . '.php';
    }
}
