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


class PagesAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('title', null, array('label' => 'admin.label_titre'))
            ->add('showTitle', null, array('required' => false, 'label' => 'admin.label_show_title'))
            ->add('showBreadcrumb', null, array('required' => false, 'label' => 'admin.label_breadcrumb'))
            ->add('title_menu_left', null, array('label' => 'admin.label_titre_menu'))
            ->add('title_menu_top', null, array('label' => 'admin.label_titre_menu_top'))
            ->add('subtitle', null, array('label' => 'admin.label_soustitre'))
            ->add('langue', null, array('label' => 'admin.label_langue'))
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('title', null, array('label' => 'admin.label_titre'))
            ->add('showTitle', null, array('required' => false, 'label' => 'admin.label_show_title'))
            ->add('showBreadcrumb', null, array('required' => false, 'label' => 'admin.label_breadcrumb'))
            //->add('title_menu_left', null, array('label' => 'admin.label_titre_menu'))
            //->add('title_menu_top', null, array('label' => 'admin.label_titre_menu_top'))
            ->add('langue', null, array('label' => 'admin.label_langue'))
            ->add('parent', null, array('label' => 'admin.label_parent'))
            ->add('approche', null, array('label' => 'admin.label_approche'))
            ->add('position')
            ->add('top')
            
            
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
            //->add('id')
        ->with('admin.tab_content')
            ->add('title', null, array('label' => 'admin.label_titre'))
            ->add('title_menu_left', null, array('label' => 'admin.label_titre_menu'))
            ->add('title_menu_top', null, array('label' => 'admin.label_titre_menu_top'))
            ->add('subtitle', null, array('label' => 'admin.label_soustitre'))
            ->add('introduction', null, array('attr' => array('class' => 'ckeditor')))
            ->add('text', null, array('label' => 'admin.label_texte', 'attr' => array('class' => 'ckeditor')))
        ->end()
        ->with('admin.tab_properties')
            ->add('langue', null, array('label' => 'admin.label_langue'))
            ->add('parent', null, array('required' => false, 'label' => 'admin.label_parent'))
            ->add('position')
            ->add('approche', null, array('label' => 'admin.label_approche'))
            ->add('special', null, array('label' => 'admin.label_special'))
        ->end()
        ->with('admin.tab_param')
            ->add('top', 'checkbox', array('required' => false))
            ->add('showTitle', 'checkbox', array('required' => false, 'label' => 'admin.label_show_title'))
            ->add('showBreadcrumb', 'checkbox', array('required' => false, 'label' => 'admin.label_breadcrumb'))
        ->end()
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            //->add('id')
            ->add('title', null, array('label' => 'admin.label_titre'))
            ->add('title_menu_left', null, array('label' => 'admin.label_titre_menu'))
            ->add('title_menu_top', null, array('label' => 'admin.label_titre_menu_top'))
            ->add('subtitle', null, array('label' => 'admin.label_soustitre'))
            ->add('text', null, array('safe' => true, 'label' => 'admin.label_texte'))
            //->add('langue', null, array('label' => 'admin.label_langue'))
            ->add('parent', null, array('label' => 'admin.label_parent'))
            ->add('slug')
            //->add('top')
            ->add('approche', null, array('label' => 'admin.label_approche'))
            
        ;
    }
}
