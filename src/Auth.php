<?php 
namespace Src;

use App\Models\User;
use Src\Sessions;

class Auth
{   
    /**
     * guest
     * Guest auth
     * @return void
     */
    public static function guest(): void
    {
        $session = new Sessions();
    }
    
    /**
     * isAuth
     * Check if user is autheticated
     * @return bool
     */
    public static function check(): bool
    {
        $session = new Sessions();
        if ($session->verifyInsideSessions()) {
            return true;
        } else {
            return false;
        }
    }

    public static function user(): mixed
    {
        $session = new Sessions();
        $id = $_SESSION['id'];
        if ($id) {
            $data = (new User())->findById($id)->data();
        }else {
            $data = ['name'=>'pleas log in first.'];
        }
        return $data;
    }
    
    /**
     * logout
     *
     * @return void
     */
    public function logout(): void
    {
        $session = new Sessions();
        $session->destructSessions();
        header('location: '.__URL__);
    }
    
    /**
     * isAdmin
     *
     * @param  mixed $permition
     * @return void
     */
    public function isAdmin(array $permition ): void
    {
        $session = new Sessions();
        $session->verifyAdminPermition($permition);
    }
    
    /**
     * permition
     *
     * @param  mixed $permition
     * @return void
     */
    public static function permition(array $permition ): mixed
    {
        $session = new Sessions();
        if ($session->verifyPermition($permition)) {
            return true;
        } else {
            return false;
        }
    }
}
