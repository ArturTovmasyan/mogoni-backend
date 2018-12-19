<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Class ProfileAdmin
 * @package App\Admin
 */
class ProfileAdmin extends AbstractAdmin
{
    protected $datagridValues = ['_sort_order' => 'DESC'];

    /**
     * @param RouteCollection $collection
     */
    public function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('export');
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('username')
            ->add('url', null, ['label' => 'Profile URL', 'template' => 'Admin/Show/url_show.html.twig'])
            ->add('avatarUrl', null, ['template' => 'Admin/Show/url_show.html.twig'])
            ->add('totalCount', null, ['label' => 'Repos Count'])
            ->add('repoList', null, ['label' => 'Repos List', 'template' => 'Admin/Show/repos_list_show.html.twig']);
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('username')
            ->add('url', null, ['label' => 'Profile URL'])
            ->add('avatarUrl');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username', null, ['show_filter' => true, 'label' => 'Username'])
            ->add('url', null, ['show_filter' => true, 'label' => 'Profile URL']);
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('username', null, ['label' => 'Username', 'route' => ['name' => 'show']])
            ->add('url', null, ['label' => 'Profile URL'])
            ->add('totalCount', null, ['label' => 'Repos Count'])
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ]
            ]);
    }
}