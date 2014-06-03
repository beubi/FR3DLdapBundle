<?php

namespace FR3D\LdapBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FOSUBBridgeCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        die('compile');
        foreach (array('fos_user.user_provider.username', 'fos_user.user_provider.username_email') as $userProviderService) {
            if ($container->hasDefinition($userProviderService)) {
                $alias = new Alias($userProviderService, true);
                var_dump('fr3d_ldap.bridge.' . $userProviderService);
                $container->setAlias('fr3d_ldap.bridge.' . $userProviderService, $alias);
            }
        }
    }
}
