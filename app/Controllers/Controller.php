<?php 
namespace App\Controllers;

use eftec\bladeone\BladeOne;
use Rakit\Validation\Validator;

class Controller 
{
     /**
     * view
     *
     * @var [pretected $view]
     */
    protected $view;
    /**
     * router
     *
     * @var [$router]
     */
    protected $router;
    
    /**
     * validator
     *
     * @var mixed
     */
    protected $validator;

    public function __construct($router, $dir = __DIR__.'/../../views', $global = [])
    {
      $cache = __DIR__.'/../../public/cache';
        $this->view = new BladeOne($dir,$cache,BladeOne::MODE_DEBUG);

        $this->router = $router;
        $this->view->share('router',$this->router);
        $this->validator = new Validator();
    }
}
