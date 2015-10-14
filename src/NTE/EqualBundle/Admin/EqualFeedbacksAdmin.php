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

class EqualFeedbacksAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('nom', null, array('label' => 'admin.label_nom'))
            ->add('dimension', null, array('label' => 'admin.label_dimension'))
            ->add('dimension.approche.langue', null, array('label' => 'admin.label_langue'))
            ->add('liminf', null, array('label' => 'admin.label_liminf'))
            ->add('limsup', null, array('label' => 'admin.label_limsup'))
            //->add('description', null, array('label' => 'admin.label_description'))
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
            ->add('dimension.nom', null, array('label' => 'admin.label_dimension'))
            ->add('dimension.approche.langue', null, array('label' => 'admin.label_langue', 'sortable' => true))
            ->add('liminf', null, array('label' => 'admin.label_liminf'))
            ->add('limsup', null, array('label' => 'admin.label_limsup'))
            ->add('description', null, 
                array(
                    'label' => 'admin.label_description', 
                    'template' => 'NTEEqualBundle:CRUD:list_plus.html.twig'
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
            ->add('dimension', null, array('label' => 'admin.label_dimension'))
            ->add('liminf', null, array('label' => 'admin.label_liminf'))
            ->add('limsup', null, array('label' => 'admin.label_limsup'))
            ->add('description', null, array('label' => 'admin.label_description', 'attr' => array('class' => 'ckeditor')))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('nom', null, array('label' => 'admin.label_nom'))
            ->add('dimension', null, array('label' => 'admin.label_dimension'))
            ->add('liminf', null, array('label' => 'admin.label_liminf'))
            ->add('limsup', null, array('label' => 'admin.label_limsup'))
            ->add('description', null, 
                array(
                    'label' => 'admin.label_description',
                    'safe' => true))
        ;
    }
}
