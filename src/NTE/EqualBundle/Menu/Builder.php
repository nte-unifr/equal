<?php
// src/NTE/EqualBundle/Menu/Builder.php
namespace NTE\EqualBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function leftMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem( 'root');

        $menu->addChild( 'Unifr',  array('uri' => 'http://www.unifr.ch/' ) );
        $menu->addChild( 'Centre NTE', array('uri' => 'http://www.unifr.ch/nte/' ) );

        return $menu;
    }

    public function topMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem( 'root');

        $menu->addChild( 'Accueil', array( 'route' => 'equal_home' ) );
        $menu->addChild( 'Enseignement', array( 'route' => 'equal_auto_enseignement' ) );
        $menu->addChild( 'Filières d\'étude', array( 'route' => 'equal_auto_filieres' ) );
        $menu->addChild( 'Glossaire', array( 'route' => 'equal_glossaire' ) );
        $menu->addChild( 'Bibliographie', array( 'route' => 'equal_biblio' ) );
        
        return $menu;
    }

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem( 'root');

        $menu->addChild( 'Accueil', array( 'route' => 'equal_home' ) );
            $menu['Accueil']->addChild( 'Introduction', array( 'route' => 'equal_intro' ) );
            $menu['Accueil']->addChild( 'Utilisation', array( 'route' => 'equal_utilisation' ) );
            $menu['Accueil']->addChild( 'Conception', array( 'route' => 'equal_conception' ) );
            
        $menu->addChild( 'Enseignement', array( 'route' => 'equal_auto_enseignement' ) );
            $menu['Enseignement']->addChild( 'Approche implicite', array( 'route' => 'equal_app_implicite' ) );
            $menu['Enseignement']->addChild( 'Approche explicite', array( 'route' => 'equal_app_explicite' ) );
    
        $menu->addChild( 'Filières d\'étude', array( 'route' => 'equal_auto_filieres' ) );
            $menu['Filières d\'étude']->addChild( 'Approche intégrée', array( 'route' => 'equal_app_integree' ) );

        $menu->addChild( 'Glossaire', array( 'route' => 'equal_glossaire' ) );
        $menu->addChild( 'Bibliographie', array( 'route' => 'equal_biblio' ) );
        $menu->addChild( 'Impressum', array( 'route' => 'equal_impressum' ) );
        $menu->addChild( 'Contact', array( 'route' => 'equal_contact' ) );

        $menu->setChildrenAttribute('class', 'unstyled');

        if (!$options['display_children']) {
            $menu['Accueil']->setDisplayChildren(false);
            $menu['Enseignement']->setDisplayChildren(false);
            $menu['Filières d\'étude']->setDisplayChildren(false);
        }

        if ($options['top_menu']) {
            $menu['Impressum']->setDisplay(false);
            $menu['Contact']->setDisplay(false);
        }

        if (isset($options['class'])) {
            $menu->setChildrenAttribute('class', $options['class']);            
        }

        return $menu;
    }
}
