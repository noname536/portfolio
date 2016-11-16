<?php

/**
 * Created by PhpStorm.
 * User: Jose Franco
 * Date: 19/04/2016
 * Time: 01:03
 */
interface IUserAuthenticator
{
    public function register($email, $password);
    public function login($email, $password, $stay_logged);
    public function logout();
}