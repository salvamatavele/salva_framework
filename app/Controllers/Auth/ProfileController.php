<?php

namespace App\Controllers\Auth;

use Src\Auth;
use App\Controllers\Controller;
use App\Models\User;
use Src\FileManager;
use Src\Helper;
use Src\Sessions;

class ProfileController extends Controller
{

    public function __construct($router)
    {
        Auth::check();
        parent::__construct($router);
    }
    /**
     * index
     *
     * @return void
     */
    public function index(): void
    {
        echo $this->view->run('auth.profile', [
            'title' => 'profile| ' . Auth::user()->name,
        ]);
    }

    /**
     * show
     *
     * @return void
     */
    public function show(): void
    {
        if ($user = Auth::user()) {
            $data = [
                'status' => 'success',
                'user' => $user,
            ];
        } else {
            $data = [
                'status' => 'error',
                'message' => 'None user',
            ];
        }

        echo json_encode($data);
    }

    /**
     * update
     *
     * @param  mixed $args
     * @return void
     */
    public function update(array $args): void
    {
        $validation = $this->validator->make($args, [
            'name' => 'required',
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
            $id = Auth::user()->id;
            $unique = new Helper();
            $unique->isUniqueEdit($id, $args['email'], (new User()), 'email');
            if (!empty($unique->getError())) {
                $data = [
                    'status' => 'error',
                    'message' => $unique->getError(),
                ];
            } else {
                $user = (new User)->findById($id);
                $user->name = $args['name'];
                $user->email = $args['email'];
                $user->permition = 0;
                $user->avatar = Helper::make_avatar($args['name']);
                if ($user->save()) {
                    $data = [
                        'status' => 'success',
                        'message' => 'The user has ben updated successfuly.',
                    ];
                    $session = new Sessions();
                    $session->setSession($user);
                } else {
                    $data = [
                        'status' => 'error',
                        'message' => 'Ooops...The user has not ben updated successfuly.',
                    ];
                }
            }
        }

        echo json_encode($data);
    }

    public function changePassword(array $args): void
    {
        $validation = $this->validator->make($args, [
            'password' => 'required|min:8',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|min:8|same:new_password'
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
            $auth = Auth::user();
            if (!password_verify($args['password'], $auth->password)) {
                $data = [
                    'status' => 'error',
                    'message' => 'The last password not match.',
                ];
            } else {
                $user = (new User)->findById($auth->id);
                $user->password = password_hash($args['new_password'], PASSWORD_DEFAULT);
                $user->permition = 0;
                if ($user->save()) {
                    $data = [
                        'status' => 'success',
                        'message' => 'The password has ben updated successfuly. Please log in again.',
                    ];
                    $session = new Sessions();
                    $session->setSession($user);
                } else {
                    $data = [
                        'status' => 'error',
                        'message' => 'Ooops...The password has not ben updated successfuly.',
                    ];
                }
            }
        }

        echo json_encode($data);
    }
    public function upload(array $args): void
    {
        // make it
        $validation = $this->validator->make($args + $_FILES, [   
            'photo' => 'required|uploaded_file:0,5m,png,jpeg,jpg',
        ]);

        // then validate
        $validation->validate();

        if ($validation->fails()) {
            // handling errors
            $errors = $validation->errors();
            $data = [
                'status'=>'error',
                'errors'=>$errors->firstOfAll(),
            ];
        } else {
            // validation passes
            $file = new FileManager();
            if ($file->storageAs('public/storage/users')) {
                $src = $file->getPath();

                $user = (new User)->findById(Auth::user()->id);
                $user->permition = 0;
                $user->image = $src;
                if ($user->save()) {
                    $data = [
                        'status'=>'success',
                        'message'=>'The profile photo have been updated successfuly.',
                    ];
                }else {
                    $data = [
                        'status'=>'error',
                        'message'=>'Ooops...The profile photo has  not updated successfuly.',
                    ];
                    $file->deleteFile($src);
                }
            }else {
                $data = [
                    'status'=>'error',
                    'message'=>$file->getError(),
                ];
            }
            
        }
        echo json_encode($data);
    }

    public function destroy(array $args): void
    {
        $user = (new User)->findById(Auth::user()->id);
        if ($user->destroy()) {
            $data = [
                'status' => 'success',
                'message' => 'You acount has been deleted. Please wait until finish this load.',
            ];
        } else {
            $data = [
                'status' => 'error',
                'message' => 'Ooops...Acount not deleted. Please try again late.',
            ];
        }
        echo json_encode($data);
    }
}
