<?php

namespace Api\Model;

class Users
{
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function getUser($login)
    {
        if ($login == 'admin') {
            $user = array(
                    'username' => $login,
                    'state' => 'success',
                );
            return $user;
        } else {
            $user = array(
                    'state' => 'error',
                    'errorMessage' => 'Not found user!',
                );
            return $user;
        }
    }

    public function auth($login, $pass)
    {
        if ($login == 'admin' and $pass == 'admin') {
            $user = array(
                    'username' => $login,
                    'state' => 'success',
                );
            return $user;
        } else {
            $user = array(
                    'state' => 'error',
                    'errorMessage' => 'Error password!',
                );
            return $user;
        }
    }
}
