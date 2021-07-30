<?php
namespace App\Models;

use CoffeeCode\DataLayer\DataLayer;

class PasswordReset extends DataLayer
{
    public function __construct()
    {
        parent::__construct('password_resets', ['email','token']);
    }
}
