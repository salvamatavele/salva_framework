<?php

namespace Src;

class Helper
{
    private  $error;

    public static function _token(): string
    {
        # rondom token;
        return bin2hex(random_bytes(64));
    }
    /**
     * make_avatar
     *
     * @param  mixed $character
     * @return string
     */
    public static function make_avatar(string $character): string
    {
        $char = strtoupper($character[0]);
        $path = "public/storage/avatar/" . $character . time() . ".png";
        $image = imagecreate(200, 200);
        $red = rand(0, 255);
        $green = rand(0, 255);
        $blue = rand(0, 255);
        imagecolorallocate($image, $red, $green, $blue);
        $textcolor = imagecolorallocate($image, 255, 255, 255);

        imagettftext($image, 100, 0, 55, 150, $textcolor, 'public/fonts/arial.ttf', $char);
        //header("Content-type: image/png");  
        imagepng($image, $path);
        imagedestroy($image);
        return $path;
    }

    /**
     * getUserIp
     *
     * @return array
     */
    public static function getUserIp()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }

    /**
     * isUnique
     *
     * @param  mixed $param
     * @param  mixed $model
     * @param  mixed $column
     * @param  mixed $errorMessage
     * @return bool
     */
    public function isUnique(string $param,  $model, string $column, string $errorMessage = null): bool
    {
        $params = http_build_query(["clause" => $param]);
        $data = $model->find($column . '=:clause', $params)->fetch(true);
        if (empty($data)) {
            return true;
        } else {
            $this->setError($errorMessage ?? "The " . $column . " allrady exist.");
            return false;
        }
    }

    /**
     * isUniqueEdit
     *
     * @param  mixed $id
     * @param  mixed $param
     * @param  mixed $model
     * @param  mixed $column
     * @param  mixed $errorMessage
     * @return bool
     */
    public  function isUniqueEdit(int $id, string $param,  $model, string $column, string $errorMessage = null): bool
    {
        $params = http_build_query(["clause" => $param, 'id' => $id]);
        $data = $model->find($column . '=:clause and id != :id', $params)->fetch(true);
        if (empty($data)) {
            return true;
        } else {
            $this->setError($errorMessage ?? "The " . $column . " allrady exist.");
            return false;
        }
    }

    /**
     * isExist
     *
     * @param  mixed $param
     * @param  mixed $model
     * @param  mixed $column
     * @return bool
     */
    public static function isExist(string $param,  $model, string $column): bool
    {
        $params = http_build_query(["clause" => $param]);
        $data = $model->find($column . '=:clause', $params)->fetch(true);
        if (empty($data)) {
            return false;
            self::setError($errorMessage ?? "The " . $column . " not exist.");
        } else {
            return true;
        }
    }

    /**
     * isValidPassword
     *
     * @param  mixed $uniquiParam
     * @param  mixed $model
     * @param  mixed $column
     * @param  mixed $password
     * @param  mixed $columnPasswordName
     * @return bool
     */
    public static function isValidPassword(string $uniquiParam, $model, string $column, string $password, string $columnPasswordName = null): bool
    {
        $params = http_build_query(["clause" => $uniquiParam]);
        $data = $model->find($column . '=:clause', $params)->fetch();
        if (empty($data)) {
            self::setError($errorMessage ?? "The " . $uniquiParam . " not exist.");
            return false;
        } else {
            if ($columnPasswordName == null) {
                $hash = $data->password;
            } else {
                $hash = $data->$columnPasswordName;
            }

            return password_verify($password, $hash);
        }
    }

    /**
     * logout
     *
     * @return void
     */
    public  function logout(): void
    {
        # code...
        $session = new Sessions();
        $session->destructSessions();
        echo "<script>
            window.location.href='" . __URL__ . "';
        </script>";
    }
    /**
     * Get the value of error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set the value of error
     *
     * @return  self
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }
}
