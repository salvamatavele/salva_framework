<?php
namespace App\Controllers;

use App\Controllers\Controller;
use Src\Auth;

class HomeController extends Controller
{
    public function __construct($router)
    {
        Auth::guest();
        parent:: __construct($router);
    }

    public function index(): void
    {
        echo $this->view->run('home',[
            'title'=>'dry code|framework'
        ]);
    }
    
}
