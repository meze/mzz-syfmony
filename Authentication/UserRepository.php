<?php

namespace Mzz\MzzBundle\Authentication;

interface UserRepository
{
    public function createEmptyUser();
    public function findByUsername($username);

    public function createNewRememberMeTokenFor($user);
    public function findRememberMeTokenByUserIdAndToken($id, $token);
    public function removeAllRememberMeTokensFor($user);
    public function removeRememberMeToken($user, $token);
}
