<?php 
namespace App\Controllers;

use App\Controllers\Controller;

class ErrorController extends Controller
{

    public function __construct($router)
    {
        parent:: __construct($router);
    }
        
    /**
     * index
     *
     * @param  mixed $errocode
     * @return void
     */
    public function index($errocode): void
    {
        //var_dump($this->view);
        echo $this->view->run('error',[
            'title'=>$errocode['errcode'],
            'errocode'=>$errocode['errcode']
    ]);
    }
}
