<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\CategoryForm;
use Application\Entity\Category;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

/**
 * This is the Category controller class. 
 * This controller is used for managing categories (adding/editing/viewing/deleting).
 */
class CategoryController extends AbstractActionController
{

    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager;

    /**
     * Category manager.
     * @var Application\Service\CategoryManager 
     */
    private $categoryManager;

    /**
     * Constructor is used for injecting dependencies into the controller.
     */
    public function __construct($entityManager, $categoryManager)
    {
        $this->entityManager = $entityManager;
        $this->categoryManager = $categoryManager;
    }

    /**
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $page = $this->params()->fromQuery('page', 1);

        // Get recent posts
        $query = $this->entityManager->getRepository(Category::class)
                ->findCategories();

        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        // Render the view template.
        return new ViewModel([
            'categories' => $paginator,
            'categoryManager' => $this->categoryManager,
        ]);
    }


    /**
     * This action displays the "New Category" page. The page contains a form allowing
     * to enter post title, content and tags. When the user clicks the Submit button,
     * a new Category entity will be created.
     */
    public function addAction()
    {
        // Create the form.
        $form = new CategoryForm();

        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {

            // Get POST data.
            $data = $this->params()->fromPost();

            // Fill form with data.
            $form->setData($data);
            if ($form->isValid()) {

                // Get validated form data.
                $data = $form->getData();

                // Use post manager service to add new post to database.                
                $this->categoryManager->addCategory($data);

                // Redirect the user to "index" page.
                return $this->redirect()->toRoute('categories');
            }
        }

        // Render the view template.
        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     * This action displays the "View Category" page allowing to see the post title
     * and content. The page also contains a form allowing
     * to add a comment to post. 
     */
    public function viewAction()
    {
        $id = (int) $this->params()->fromRoute('id', -1);

        // Validate input parameter
        if ($id < 0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Find the post by ID
        $category = $this->entityManager->getRepository(Category::class)
                ->findOneById($id);

        if ($category == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Render the view template.
        return new ViewModel([
            'category' => $category,
            'categoryManager' => $this->categoryManager
        ]);
    }

    /**
     * This action displays the page allowing to edit a post.
     */
    public function editAction()
    {
        // Create form.
        $form = new CategoryForm();

        // Get post ID.
        $id = (int) $this->params()->fromRoute('id', -1);

        // Validate input parameter
        if ($id < 0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Find the existing post in the database.
        $category = $this->entityManager->getRepository(Category::class)
                ->findOneById($id);
        if ($category == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {

            // Get POST data.
            $data = $this->params()->fromPost();

            // Fill form with data.
            $form->setData($data);
            if ($form->isValid()) {

                // Get validated form data.
                $data = $form->getData();

                // Use post manager service update existing post.                
                $this->categoryManager->updateCategory($category, $data);

                // Redirect the user to "admin" page.
                return $this->redirect()->toRoute('categories', ['action' => 'admin']);
            }
        } else {
            $data = [
                'name' => $category->getName(),
                'status' => $category->getStatus()
            ];

            $form->setData($data);
        }

        // Render the view template.
        return new ViewModel([
            'form' => $form,
            'category' => $category
        ]);
    }

    /**
     * This "delete" action deletes the given post.
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', -1);

        // Validate input parameter
        if ($id < 0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $category = $this->entityManager->getRepository(Category::class)
                ->findOneById($id);
        if ($category == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->categoryManager->removeCategory($category);

        // Redirect the user to "admin" page.
        return $this->redirect()->toRoute('categories', ['action' => 'admin']);
    }

    /**
     * This "admin" action displays the Manage Categorys page. This page contains
     * the list of categories with an ability to edit/delete any post.
     */
    public function adminAction()
    {
        // Get recent categories
        $categories = $this->entityManager->getRepository(Category::class)
                ->findBy([], ['name' => 'ASC']);

        // Render the view template
        return new ViewModel([
            'categories' => $categories,
            'categoryManager' => $this->categoryManager
        ]);
    }

}
