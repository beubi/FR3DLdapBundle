<?php

namespace FR3D\LdapBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

interface UpdatingUserManagerInterface
{
    /**
     * @param UserInterface $user
     * @return void
     */
    public function updateUser(UserInterface $user);
} 
