<?php

namespace AppVentus\Awesome\SpoolMailerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AvAwesomeSpoolMailerExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $container->setParameter(
            'av_awesome_spool_mailer.contact_addresses',
            $config['contact_addresses']
         );

        //Generate a parameter for each kind of contact
        //example :
        //
        //av_awesome_spool_mailer:
        //    contact_addresses:
        //      sales_manager:                  #generated parameter : contact_addresses_sales_manager_address
        //        address: salesman@you.com
        //        name: Mitch
        //      marketing_department:           #generated parameter : contact_addresses_marketing_department_address
        //        address: marketing@you.com
        //        name: Marketing Dept.
        //      it_crowd:                       #generated parameter : contact_addresses_it_crowd_address
        //        address: it@you.com
        //        name: The IT guy
        //      noreply:                        #generated parameter : contact_addresses_noreply_address
        //        address: noreply@you.com
        //        name: Do not reply
        //
        foreach ($config['contact_addresses'] as $key => $value) {
            $container->setParameter(
                'contact_addresses_'.$key.'_address',
                $value['address']
             );
            $container->setParameter(
                'contact_addresses_'.$key.'_name',
                $value['name']
             );
        }
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return "av_awesome_spool_mailer";
    }
}
