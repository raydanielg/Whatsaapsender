<?php

namespace Alex\LaravelDocSchema\Controllers;

use Illuminate\Routing\Controller;

class WelcomeController extends Controller
{
    /**
     * Display the installer welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        $server = appServerUrl(url('/')); 
        return view('pdo::welcome',compact('server'));
    }
}
