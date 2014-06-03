<?php

namespace FR3D\LdapBundle\Security\User;

use FR3D\LdapBundle\Model\UpdatingUserManagerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class LdapUpdatingUserProvider implements UserProviderInterface
{
    /**
     * @var UserProviderInterface[]
     */
    protected $providers;

    /**
     * @var UpdatingUserManagerInterface[]
     */
    protected $userManagers;

    /**
     * @var LdapUserProvider
     */
    protected $ldapProvider;

    /**
     * @var array
     */
    protected $properties;

    public function __construct(array $providers, array $userManagers, LdapUserProvider $ldapProvider, array $properties)
    {
        $this->providers = $providers;
        $this->userManagers = $userManagers;
        $this->ldapProvider = $ldapProvider;
        $this->properties = $properties;
    }

    /**
     * @return array
     */
    public function getProviders()
    {
        return array_merge($this->providers, array($this->ldapProvider));
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        foreach ($this->providers as $provider) {
            var_dump(get_class($provider));
            try {
                $user = $provider->loadUserByUsername($username);
                die('update');
                $this->updateUsers($username);


                return $user;
            } catch (UsernameNotFoundException $notFound) {
                // try next one
            }
        }
        die('not found');

        $ex = new UsernameNotFoundException(sprintf('There is no user with name "%s".', $username));
        $ex->setUsername($username);
        throw $ex;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $supportedUserFound = false;

        foreach ($this->providers as $provider) {
            try {
                return $provider->refreshUser($user);
            } catch (UnsupportedUserException $unsupported) {
                // try next one
            } catch (UsernameNotFoundException $notFound) {
                $supportedUserFound = true;
                // try next one
            }
        }

        if ($supportedUserFound) {
            $ex = new UsernameNotFoundException(sprintf('There is no user with name "%s".', $user->getUsername()));
            $ex->setUsername($user->getUsername());
            throw $ex;
        } else {
            throw new UnsupportedUserException(sprintf('The account "%s" is not supported.', get_class($user)));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        foreach ($this->providers as $provider) {
            if ($provider->supportsClass($class)) {
                return true;
            }
        }

        return $this->ldapProvider->supportsClass($class);
    }

    private function updateUsers($username)
    {
        $ldapUser = $this->ldapProvider->loadUserByUsername($username);

        for ($i = 0; $i < count($this->providers); $i++) {
            $user = $this->providers[$i]->loadUserByUsername($username);

            $this->updateUser($user, $ldapUser);

            $this->userManagers[$i]->updateUser($user);
        }
    }

    /**
     * @param UserInterface $user
     * @param UserInterface $ldapUser
     */
    protected function updateUser(UserInterface $user, UserInterface $ldapUser)
    {
        foreach ($this->properties as $property) {
            $getter = 'get' . ucfirst($property);
            $setter = 'set' . ucfirst($property);
die('Update');
            if (method_exists($ldapUser, $getter) && is_callable(array($ldapUser, $getter)) &&
                method_exists($user, $setter) && is_callable(array($user, $setter))
            ) {
                $user->$setter($ldapUser->$getter());
            }
        }
    }
}
