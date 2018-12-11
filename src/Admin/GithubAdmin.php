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
    /**
     * @param RouteCollection $collection
     */
    public function configureRoutes(RouteCollection $collection) {
        $collection->remove('export');
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('title')
            ->add('subtitle')
            ->add('url')
            ->add('ownerName')
            ->add('ownerAvatarUrl')
            ->add('ownerGithubUrl')
            ->add('starsCount')
            ->add('mainLanguage')
            ->add('openIssueCount')
            ->add('closedIssuesCount')
            ->add('lastCommitDate', 'date', ['widget' => 'single_text'])
            ->add('commitsCount')
            ->add('allCommitCount');
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('subtitle')
            ->add('url')
            ->add('ownerName')
            ->add('ownerAvatarUrl')
            ->add('ownerGithubUrl')
            ->add('starsCount')
            ->add('mainLanguage')
            ->add('openIssueCount')
            ->add('closedIssuesCount')
            ->add('lastCommitDate', null, ['widget' => 'single_text'])
            ->add('commitsCount')
            ->add('allCommitCount');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', null, ['show_filter' => true])
            ->add('url', null, ['show_filter' => true])
            ->add('ownerName', null, ['show_filter' => true]);
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('subtitle')
            ->add('url')
            ->add('ownerName')
            ->add('ownerAvatarUrl')
            ->add('ownerGithubUrl')
            ->add('starsCount')
            ->add('mainLanguage')
            ->add('openIssueCount')
            ->add('closedIssuesCount')
            ->add('lastCommitDate', null, ['widget' => 'single_text'])
            ->add('commitsCount')
            ->add('allCommitCount')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ]
            ]);
    }
}