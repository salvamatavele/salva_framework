<?php
namespace Src;

class Sessions
{
    private $login;
    private $timeSession = 1200;
    private $timeCanary = 300;


    public function __construct()
    {
        if (session_id() == '') {
            ini_set("session.save_handler", "files");
            ini_set("session.use_cookies", 1);
            ini_set("session.use_only_cookies", 1);
            ini_set("session.cookie_domain", _DOMIN_);
            ini_set("session.cookie_httponly", 1);
            if (_DOMIN_ != "localhost") {
                ini_set("session.cookie_secure", 1);
            }
            /*Criptografia das nossas sessions*/
            ini_set("session.entropy_length", 512);
            ini_set("session.entropy_file", '/dev/urandom');
            ini_set("session.hash_function", 'sha256');
            ini_set("session.hash_bits_per_character", 5);
            session_start();
        }
    }


    /**
     * protege contra rooubo de sessao
     *
     * @param [type] $par
     * @return void
     */
    public function setSessionCanary($param = null): void
    {
        # code...
        session_regenerate_id(true);
        if ($param == null) {
            $_SESSION['canary'] = [
                "birth" => time(),
                "IP" => Helper::getUserIp()
            ];
        } else {
            $_SESSION['canary']['birth'] = time();
        }
    }
    /**
     * verifica id da sessao
     *
     * @return void
     */
    public function verifyIdSessions(): void
    {
        # code...
        if (!isset($_SESSION['canary'])) {
            $this->setSessionCanary();
        }

        if ($_SESSION['canary']['IP'] !== Helper::getUserIp()) {
            $this->destructSessions();
            $this->setSessionCanary();
        }

        if ($_SESSION['canary']['birth'] < time() - $this->timeCanary) {
            $this->setSessionCanary("time");
        }
    }
    /**
     * set sessions
     *
     * @param [object] data
     * @return array
     */
    public function setSession($data): array
    {
        # code...
        $this->verifyIdSessions();
        $_SESSION["login"] = true;
        $_SESSION["time"] = time();
        $_SESSION["id"] = $data->id;
        $_SESSION["name"] = $data->name;
        $_SESSION["email"] = $data->email;
        $_SESSION["image"] = $data->image;
        $_SESSION["avatar"] = $data->avatar;
        $_SESSION["permition"] = $data->permition;
        return $_SESSION;
    }
    /**
     * verifica sessoes internas
     *
     * @return void
     */
    public function verifyInsideSessions()
    {
        $this->verifyIdSessions();
        if (!isset($_SESSION['login']) || !isset($_SESSION['canary'])) {
            $this->destructSessions();
            echo "
                <script>
                    window.location.href='" . __URL__ . "/login';
                </script>
            ";
        } else {
            if ($_SESSION['time'] >= time() - $this->timeSession) {
                $_SESSION['time'] = time();
                return true;
            } else {
                $this->destructSessions();
                echo "
                <script>
                    window.location.href='" . __URL__ . "/login';
                </script>
                ";
            }
        }
    }
    
    /**
     * verifyAdminPermition
     * verica se o usuario e um admin 
     *
     * @param  mixed $permition
     * @return mixed
     */
    public function verifyAdminPermition($permition = [])
    {
        $this->verifyIdSessions();
        if (!isset($_SESSION['login']) || !isset($_SESSION['canary'])) {
            $this->destructSessions();
            echo "
            <script>
                window.location.href='" . __URL__ . "/login';
            </script>
            ";
        } else {
            if ($_SESSION['time'] >= time() - $this->timeSession) {
                $_SESSION['time'] = time();
                $i=0;
                foreach ($permition as $permiti) {
                    if ($_SESSION['permition'] != 'manager' && $permiti != $_SESSION['permition']) {
                        $i++;
                    }
                }
                if(sizeof($permition) == $i){
                    echo "
                    <script>     
                        window.location.href='" . __URL__  . "';
                    </script>
                    ";
                }
            } else {
                $this->destructSessions();
                echo "
                <script>
                    window.location.href='" . __URL__ . "/login';
                </script>
                ";
            }
        }
    }
    /**
     * varify the user permition
     *
     * @param array $permition
     * @return mixed
     */
    public function verifyPermition($permition = [])
    {
        $this->verifyIdSessions();
        if (!isset($_SESSION['login']) || !isset($_SESSION['canary'])) {
            $this->destructSessions();
            echo "
            <script>
                window.location.href='" . __URL__ . "/login';
            </script>
            ";
        } else {
            if ($_SESSION['time'] >= time() - $this->timeSession) {
                $_SESSION['time'] = time();
                $i=0;
                foreach ($permition as $permiti) {
                    if ($_SESSION['permition'] != 'manager' && $permiti != $_SESSION['permition']) {
                        $i++;
                    }
                }
                if(sizeof($permition) == $i){
                    echo "
                    <script>     
                        alert('Você não tem acesso a este conteúdo!');
                        window.location.href='" . $_SERVER['HTTP_REFERER']  . "';
                    </script>
                    ";
                    
                }else{
                    return true;
                }
            } else {
                $this->destructSessions();
                echo "
                <script>
                    window.location.href='" . __URL__ . "/login';
                </script>
                ";
            }
        }
    }
    /**
     * destruct sessions
     *
     * @return void
     */
    public function destructSessions(): void
    {
        foreach (array_keys($_SESSION) as $key) {
            unset($_SESSION[$key]);
        }
    }
    /**
     * retorna o usuario logado ou qualquer
     *
     * @param [type] $param
     * @param [type] $model
     * @param [type] $column
     * @return mixed
     */
    public  function auth(string $param, $model, string $column)
    {
        # code...
        $params = http_build_query(["clause" => $param]);
        $data = $model->find($column . '=:clause', $params)->fetch();
        return $data;
    }
}
