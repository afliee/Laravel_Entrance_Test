<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

abstract class ApiController extends Controller
{
    abstract protected function getService();

    public function index()
    {
        return $this->getService()->index();
    }

    public function show(Request $request)
    {
        return $this->getService()->show($request);
    }

}
