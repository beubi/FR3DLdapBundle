<?php

namespace FR3D\LdapBundle;

use FR3D\LdapBundle\DependencyInjection\Compiler\FOSUBBridgeCompilerPass;
use FR3D\LdapBundle\Security\Factory\LdapFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FR3DLdapBundle extends Bundle
{
    public function boot()
    {
        if (!function_exists('ldap_connect')) {
            throw new \Exception("module php-ldap isn't install");
        }
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new LdapFactory());
die('here');
        $container->addCompilerPass(new FOSUBBridgeCompilerPass());
    }
}
