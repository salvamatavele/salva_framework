<?php

namespace App\Controllers\Auth;

use Src\Auth;
use Src\Email;
use Src\Helper;
use Src\Sessions;
use App\Models\User;
use App\Models\PasswordReset;
use App\Controllers\Controller;

class AuthController extends Controller
{
    public function __construct($router)
    {
        
        parent::__construct($router);
    }

    /**
     * index
     *
     * @return void
     */
    public function index(): void
    {
        echo $this->view->run('auth.login');
    }

    /**
     * auth
     *
     * @param  mixed $datas
     * @return void
     */
    public function auth(array $datas): void
    {
        $validation = $this->validator->make($datas + $_FILES, [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
        $validation->validate();

        if ($validation->fails()) {
            // handling errors
            $errors = $validation->errors();
            $data = [
                'status' => 'error',
                'errors' => $errors->firstOfAll(),
            ];
        } else {
            // validation passes
            if (Helper::isExist($datas['email'], (new User), 'email')) {
                if (Helper::isValidPassword($datas['email'], (new User), 'email', $datas['password'])) {
                    $session = new Sessions();
                    $user = $session->auth($datas['email'], (new User), 'email');
                    $session->setSession($user);
                    var_dump($_SESSION["name"]);
                    $data = [
                        'status' => 'success',
                        'message' => 'Loged In Successfuly.'
                    ];
                } else {
                    $data = [
                        'status' => 'error',
                        'message' => 'Email ou senha invalida.',
                    ];
                }
            } else {
                $data = [
                    'status' => 'error',
                    'message' => 'Email ou senha invalida.',
                ];
            }
        }
        echo json_encode($data);
    }

    /**
     * register
     *
     * @return void
     */
    public function register(array $datas): void
    {
        $user = new User;
        $validation = $this->validator->make($datas + $_FILES, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|min:8|same:password',
            'terms' => 'required',
        ]);
        $validation->validate();

        if ($validation->fails()) {
            // handling errors
            $errors = $validation->errors();
            $data = [
                'status' => 'error',
                'errors' => $errors->firstOfAll(),
            ];
        } else {
            //sucess validation
            //chech if email exist
            $unique = new Helper();
            $unique->isUnique($datas['email'], $user, 'email');
            if (empty($unique->getError())) {
                $user->name = $datas['name'];
                $user->email = $datas['email'];
                $user->permition = 0;
                $user->password = password_hash($datas['password'], PASSWORD_DEFAULT);
                $user->avatar = Helper::make_avatar($datas['name']) ?? null;
                if ($user->save()) {
                    $data = [
                        'status' => 'success',
                        'message' => 'User registered successfuly.',
                    ];
                } else {
                    $data = [
                        'status' => 'error',
                        'message' => 'User not registered successfuly.',
                        'error' => $user,
                    ];
                }
            } else {
                $data = [
                    'status' => 'error',
                    'message' => 'The user email already exist.',
                ];
            }
        }
        echo json_encode($data);
    }


    /**
     * send password reset email
     *
     * @param array $data
     * @return void
     */
    public function sendPassword(array $datas)
    {
        $validation = $this->validator->make($datas, [
            'email' => 'required|email',
        ]);
        $validation->validate();

        if ($validation->fails()) {
            // handling errors
            $errors = $validation->errors();
            $data = [
                'status' => 'error',
                'errors' => $errors->firstOfAll(),
            ];
        } else {
            if (Helper::isExist($datas['email'], (new User), "email")) {
                $password = new PasswordReset();
                $password->email = $datas['email'];
                $password->token = Helper::_token();
                if ($password->save()) {
                    $params = http_build_query(["clause" => $datas['email']]);
                    $user = (new User())->find('email=:clause', $params)->fetch();
                    $mail = new Email();
                    $mail->add(
                        "Recuperação de senha de DRYCODE|Framework",
                        "<h2>Hello {$user->name} ,</h2><p>Clique no botão abaixo para recuperar a sua senha. Valido por 20 minutos.</p><a style='color: white; background-color: #5A827F; padding: 5px 11px; border: 2px solid #5a827f;' href='" . $this->router->route('reset', ['token' => $password->token]) . "'>Quero recuperar a senha.</a>",
                        $user->name,
                        $datas['email']
                    )->send();
                    if (!$mail->getError()) {
                        session_start();
                        $_SESSION['token'] = $password->token;
                        $data =  [
                            'status' => 'success',
                            'message' => 'Foi enviado um email de recuperação de  senha para seu email. Abra o seu email e clique em Quero recuperar a senha',
                        ];
                    } else {
                        $data =  [
                            'status' => 'error',
                            'message' => $mail->getError()->getMessage(),
                        ];
                    }
                } else {
                    $data =  [
                        'status' => 'error',
                        'message' => 'Ooops...Nao foi possivel afectuar essa operacao. Por favor tenete mais tarde.',
                    ];
                }
            } else {
                $data = [
                    'status' => 'error',
                    'message' => 'This user email not exist.',
                ];
            }
        }
        echo json_encode($data);
        return;
    }
    /**
     * reset
     *
     * @param  mixed $data
     * @return void
     */
    public function reset(array $data): void
    {
        echo $this->view->run("auth.reset-password", [
            'title' => 'Reset Password'
        ]);
    }
    
    /**
     * storePassword
     *
     * @param  mixed $datas
     * @return void
     */
    public function storePassword(array $datas): void
    {
        $tkn = end(explode("/", $_SERVER['HTTP_REFERER']));
        session_start();
        $token = (isset($_SESSION['token'])) ? $_SESSION['token'] : null;
        if (isset($token)) {
            $param = http_build_query(["token" => $tkn]);
            $email = (new PasswordReset)->find("token = :token", $param)->fetch();
            $isEmail = $email->email;
            if (isset($isEmail)) {
                if (Helper::isExist($isEmail, (new User), "email")) {
                    $validation = $this->validator->make($datas, [
                        'password' => 'required|min:8',
                        'confirm_password' => 'required|min:8|same:password',
                    ]);
                    $validation->validate();

                    if ($validation->fails()) {
                        // handling errors
                        $errors = $validation->errors();
                        $data = [
                            'status' => 'error',
                            'errors' => $errors->firstOfAll(),
                        ];
                    } else {
                        $acount = http_build_query(["email" => $isEmail]);
                        $id = (new User())->find("email = :email", $acount)->fetch();

                        $user = (new User())->findById($id->id);
                        $user->permition = 0;
                        $user->password = password_hash($datas['password'], PASSWORD_DEFAULT);
                        if ($user->save()) {
                            $data = [
                                'status' => 'success',
                                'message' => 'A sua nova senha foi configurada com sucesso. Faça o login novamente com a sua nova senha.'
                            ];
                        } else {
                            $data = [
                                'status' => 'error',
                                'message' => 'Ooops...Nao foi possível recuperar a sua senha. Tente novamente'
                            ];
                        }
                    }
                } else {

                    $data = [
                        'status' => 'error',
                        'message' => 'Ja nao existe conta com esse email'
                    ];
                }
            } else {
                $data = [
                    'status' => 'error',
                    'message' => 'Sem permissão para fazer essa operação. Use o botão de recuperação de senha do email que lhe foi enviado ou faça outra requisição.',
                ];
            }
        } else {
            $data = [
                'status' => 'error',
                'message' => 'O seu pedido de recuperação de senha expirou. Por favor tente novamente.',
            ];
        }
        echo json_encode($data);
    }

    /**
     * logout
     *
     * @return void
     */
    public function logout(): void
    {
        $log = new Auth;
        $log->logout();
    }
}
