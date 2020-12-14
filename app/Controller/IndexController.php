<?php


namespace App\Controller;


class IndexController extends Controller
{

    public function index()
    {
        return [
            'code' => 200,
            'message' => 'Welcome ZiyoREN',
            'now' => date('Y-m-d H:i:s'),
        ];
    }
}