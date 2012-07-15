<?php

namespace Mzz\MzzBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;

/**
 * MzzExtension is an extension for the mzz
 *
 */
class MzzExtension extends Extension
{

    public function load(array $config, ContainerBuilder $container)
    {
      foreach ($config as $section) {
            $this->doConfigLoad($section, $container);
        }
    }

    public function doConfigLoad($config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        //$loader->load('security.xml');
        //$loader->load('controller.xml');
       // $loader->load('jip.xml');
   //     $loader->load('templating.xml');
       /* $loader->load('validator.xml');

        if (!empty($config['authentication']) && !isset($config['class']['user_repository.class'])) {
            throw new \RuntimeException('You must define your user repository class (user_repository.class). In your project you need to
                make a class that implements UserRepository and will be used to retrieve a user from a data source (databases, etc)');
        }

        if (isset($config['user_controller'])) {
            $this->remapParameters($config, $container, array(
                'user_controller' => 'mzz.authentication.controller.user.class',
            ));
        }
*/
        $namespaces = array(
            'class' => 'mzz.%s'
        );
        $this->remapParametersNamespaces($config, $container, $namespaces);

    }

    protected function remapParameters(array $config, ContainerBuilder $container, array $map)
    {
        foreach ($map as $name => $paramName) {
            if (isset($config[$name])) {
                $container->setParameter($paramName, $config[$name]);
            }
        }
    }

    protected function remapParametersNamespaces(array $config, ContainerBuilder $container, array $namespaces)
    {
        foreach ($namespaces as $ns => $map) {
            if ($ns) {
                if (!isset($config[$ns])) {
                    continue;
                }
                $namespaceConfig = $config[$ns];
            } else {
                $namespaceConfig = $config;
            }
            if (is_array($map)) {
                $this->remapParameters($namespaceConfig, $container, $map);
            } else {
                foreach ($namespaceConfig as $name => $value) {
                    if (null !== $value) {
                        $container->setParameter(sprintf($map, $name), $value);
                    }
                }
            }
        }
    }



    /**
     * Returns the base path for the XSD files.
     *
     * @return string The XSD base path
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config';
    }

    /**
     * Returns the namespace to be used for this extension (XML namespace).
     *
     * @return string The XML namespace
     */
    public function getNamespace()
    {
        return 'http://symfony.com/schema/dic/mzz';
    }

    /**
     * Returns the recommended alias to use in XML.
     *
     * This alias is also the mandatory prefix to use when using YAML.
     *
     * @return string The alias
     */
    public function getAlias()
    {
        return 'mzz';
    }
}
