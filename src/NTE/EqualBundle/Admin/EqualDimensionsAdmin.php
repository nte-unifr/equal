<?php

/**
 * Copyright 2014 Centre NTE <http://nte.unifr.ch>, Université de Fribourg, Suisse
 * 
 * This file is part of Equal+.
 *
 * Equal+ is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Equal+ is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Equal+.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace NTE\EqualBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class EqualDimensionsAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('nom', null, array('label' => 'admin.label_nom'))
            ->add('approche.langue', null, array('label' => 'admin.label_langue'))
            //->add('description', null, array('label' => 'admin.label_description'))
            ->add('approche', null, array('label' => 'admin.label_approche'))
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('nom', null, array('label' => 'admin.label_nom'))
            ->add('approche.langue.locale', null, array('label' => 'admin.label_langue'))
            //->add('description', null, array('label' => 'admin.label_description'))
            ->add('approche.nom', null, array('label' => 'admin.label_approche'))
            
            ->add('items', 'sonata_type_collection', 
                array(
                    'label' => 'admin.label_items', 
                    'by_reference' => false, 
                    'template' => 'NTEEqualBundle:CRUD:list_orm_one_to_many_plus.html.twig'), 
                array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'position'
            ))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('nom', null, array('label' => 'admin.label_nom'))
            ->add('description', null, array('label' => 'admin.label_description', 'attr' => array('class' => 'ckeditor')))
            ->add('approche', null, array('label' => 'admin.label_approche'))
            
            /*
            ->with('Items')
                ->add('items', 'sonata_type_collection', array(), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'position'
                ))
            ->end()
            */
        ;        
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('admin.label_dimension')
                ->add('nom', null, array('label' => 'admin.label_nom'))
                ->add('description', null, array('safe' => true, 'label' => 'admin.label_description'))
                ->add('approche', null, array('label' => 'admin.label_approche'))
            ->end()

            ->with('admin.label_items')
                ->add('items', 'sonata_type_collection', array('label' => 'admin.label_items', 'by_reference' => false) , array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'position'
                ))
            ->end()
            
        ;
    }
}
