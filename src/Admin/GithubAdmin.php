<?php
/**
 * Created by PhpStorm.
 * User: arthurt
 * Date: 12/12/18
 * Time: 10:09 PM
 */

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Class GithubAdmin
 * @package App\Admin
 */
class GithubAdmin extends AbstractAdmin
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
            ->add('title', null, ['label' => 'Repo Name'])
            ->add('subtitle')
            ->add('url', null, ['label' => 'Repo URL', 'template' => 'Admin/Show/url_show.html.twig'])
            ->add('ownerName', null, ['label' => 'Name'])
            ->add('ownerAvatarUrl', null, ['label' => 'Avatar URL', 'template' => 'Admin/Show/url_show.html.twig'])
            ->add('ownerGithubUrl', null, ['label' => 'Github URL', 'template' => 'Admin/Show/url_show.html.twig'])
            ->add('starsCount', null, ['label' => 'Number of Stars'])
            ->add('mainLanguage', null, ['label' => 'Primary language'])
            ->add('openIssueCount', null, ['label' => 'Opened Issue'])
            ->add('closedIssuesCount', null, ['label' => 'Closed Issue'])
            ->add('lastCommitDate', null, ['widget' => 'single_text'])
            ->add('commitsCount')
            ->add('allCommitCount', null, ['label' => 'All Commits'])
            ->add('readme')
            ->add('license');
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, ['label' => 'Repo Name'])
            ->add('subtitle')
            ->add('url', null, ['label' => 'Repo URL'])
            ->add('ownerName', null, ['label' => 'Name'])
            ->add('ownerAvatarUrl', null, ['label' => 'Avatar URL'])
            ->add('ownerGithubUrl', null, ['label' => 'Github URL'])
            ->add('starsCount', null, ['label' => 'Number of Stars'])
            ->add('mainLanguage', null, ['label' => 'Primary language'])
            ->add('openIssueCount', null, ['label' => 'Opened Issue'])
            ->add('closedIssuesCount', null, ['label' => 'Closed Issue'])
            ->add('lastCommitDate', null, ['widget' => 'single_text'])
            ->add('commitsCount')
            ->add('allCommitCount', null, ['label' => 'All Commits']);
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', null, ['show_filter' => true, 'label' => 'Repo Name'])
            ->add('url', null, ['show_filter' => true, 'label' => 'Repo URL'])
            ->add('ownerName', null, ['show_filter' => true, 'label' => 'Name']);
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('ownerName', null, ['label' => 'Name', 'route' => ['name' => 'show']])
            ->add('ownerGithubUrl', null, ['label' => 'Github URL'])
            ->add('title', null, ['label' => 'Repo Name'])
            ->add('url', null, ['label' => 'Repo URL'])
            ->add('mainLanguage', null, ['label' => 'Primary language'])
            ->add('starsCount', null, ['label' => 'Number of Stars'])
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ]
            ]);
    }
}