<?php

namespace FR3D\LdapBundle\FOSUBBridge;

use FOS\UserBundle\Model\UserManagerInterface;
use FR3D\LdapBundle\Model\UpdatingUserManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserManager implements UpdatingUserManagerInterface
{
    private $userManager;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * {@inheritdoc}
     */
    public function updateUser(UserInterface $user)
    {
        $this->userManager->updateUser($user);
    }
}
