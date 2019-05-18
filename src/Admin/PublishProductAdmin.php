<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Class PublishProductAdmin
 * @package App\Admin
 */
final class PublishProductAdmin extends AbstractAdmin
{
    protected $datagridValues = ['_sort_order' => 'DESC'];

    /**
     * @param RouteCollection $collection
     */
    public function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('export');
        $collection->remove('create');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
			->add('id', null, ['show_filter' => true])
			->add('title', null, ['show_filter' => true])
			->add('authorName', null, ['show_filter' => true])
			->add('repoName', null, ['show_filter' => true])
			;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
			->add('id')
			->add('title')
			->add('authorName')
			->add('repoName')
			->add('goal')
			->add('roadmap')
			->add('contact')
			->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
			->add('title')
			->add('description')
			->add('authorName')
			->add('repoName')
			->add('goal')
			->add('roadmap')
			->add('contact')
			;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
			->add('id')
			->add('title')
			->add('description')
			->add('authorName')
			->add('repoName')
			->add('goal')
			->add('roadmap')
			->add('screenshots', null, ['template' => 'Admin/Show/product_screenshots_show.html.twig'])
			->add('installations', null, ['template' => 'Admin/Show/product_installation_show.html.twig'])
			->add('examples', null, ['template' => 'Admin/Show/product_example_show.html.twig'])
			->add('contact')
			->add('createdAt')
			->add('updatedAt')
			;
    }
}
