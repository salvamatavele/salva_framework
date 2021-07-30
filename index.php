<?php 

use CoffeeCode\Router\Router;

require __DIR__ . "/vendor/autoload.php";

$router = new Router(__URL__);

$router->group(null)->namespace("app\Controllers");
$router->get("/", "HomeController:index",'home');

/**
 * Auth routers
 */
$router->group(null)->namespace("app\Controllers\Auth");
$router->get('/login',"AuthController:index",'login');
$router->get('/logout',"AuthController:logout",'logout');
$router->post('/auth',"AuthController:auth",'auth');
$router->post('/register',"AuthController:register",'register');
$router->post("/password","AuthController:sendPassword","password");
$router->get("/password/reset/{token}","AuthController:reset","reset");
$router->put("/password/store","AuthController:storePassword","password.store");

//profile
$router->get('/profile',"ProfileController:index",'profile');
$router->get('/profile/show',"ProfileController:show",'profile.show');
$router->put('/profile/update',"ProfileController:update",'profile.update');
$router->put('/profile/password',"ProfileController:changePassword",'profile.change.password');
$router->put('/profile/upload',"ProfileController:upload",'profile.upload');
$router->delete('/profile/destroy',"ProfileController:destroy",'profile.destroy');

/**
 * Group Error
 * This monitors all Router errors. Are they: 400 Bad Request, 404 Not Found, 405 Method Not Allowed and 501 Not Implemented
 */
$router->group("error")->namespace("app\Controllers");
$router->get("/{errcode}", "ErrorController:index", "error");


/**
 * This method executes the routes
 */
$router->dispatch();

/*
 * Redirect all errors
 */
if ($router->error()) {
    $router->redirect("error",['errcode'=>$router->error()]);
}
