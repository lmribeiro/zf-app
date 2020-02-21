<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Json\Json;
use Doctrine\ORM\EntityManager;
use Application\Entity\Category;
use Application\Entity\Repository\CategoryRepository;
use Application\Form\CategoryForm;
use Application\InputFilter\FormCategoryFilter;

class ApiController extends AbstractRestfulController
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
     * Retrive all categories
     */
    public function allAction()
    {
        $categories = $this->entityManager->getRepository(Category::class)
                ->findBy([], ['name' => 'ASC']);

        $data = [];
        foreach ($categories as $category) {
            $data[] = $category->toArray();
        }

        $this->sendOk("Categories retrived successful!", $data);
    }

    /**
     * Retrive category with a given id
     */
    public function oneAction()
    {
        $id = $this->params()->fromRoute('id', -1);

        if (!$model = $this->entityManager->getRepository(Category::class)->find($id)) {
            $this->sendShortResponse(404, "Category not found");
        }

        $this->sendOk("Category retrived successful!", $model->toArray());
    }

    /**
     * Add a category
     */
    public function addAction()
    {
        // Check if is a POST request.
        if (!$this->getRequest()->isPost()) {
            $this->sendShortResponse(406, "The action only accepts POST requests!");
        }

        // Get POST data.
        $post = file_get_contents("php://input");
        $post_aux = json_decode($post, true);

        // Create the form and fill it
        $form = new CategoryForm();
        $form->setData($post_aux);

        if (!$form->isValid()) {
            $this->sendShortResponse(406, "Invalid data submited!", $post_aux);
        }

        // Get validated form data and category to databe
        $data = $form->getData();
        $response = $this->categoryManager->addCategory($data);

        $this->sendOk("Category created successful!", $response);
    }

    /**
     * Edit category with a given id 
     */
    public function editAction()
    {
        if (!$this->getRequest()->isPost()) {
            $this->sendShortResponse(406, "The action only accepts POST requests!");
        }

        $id = (int) $this->params()->fromRoute('id', -1);
        if (!$category = $this->entityManager->getRepository(Category::class)->findOneById($id)) {
            $this->sendShortResponse(404, "Category not found");
        }

        // Get POST data.
        $post = file_get_contents("php://input");
        $post_aux = json_decode($post, true);

        // Create the form and fill it
        $form = new CategoryForm();
        $form->setData($post_aux);

        if (!$form->isValid()) {
            $this->sendShortResponse(406, "Invalid data submited!", $post_aux);
        }

        // Get validated form data and update category.
        $data = $form->getData();
        $response = $this->categoryManager->updateCategory($category, $data);

        $this->sendOk("Category updated successful!", $response);
    }

    /**
     * Delete category with given id
     */
    public function deleteAction()
    {
        if (!$this->getRequest()->isDelete()) {
            $this->sendShortResponse(406, "The action only accepts DELETE requests!");
        }
        
        $id = (int) $this->params()->fromRoute('id', -1);
        if (!$category = $this->entityManager->getRepository(Category::class)->findOneById($id)) {
            $this->sendShortResponse(404, "Category not found");
        }

        // Delete category
        $this->categoryManager->removeCategory($category);

        $this->sendOk("Category deleted successful!", null);
    }

    /**
     * Send api's short response
     * @param type $status response status
     * @param type $msg response message
     * @param type $data response data
     */
    public function sendShortResponse($status, $msg, $data = null)
    {
        $returnArray["status"] = $status;
        $returnArray["message"] = $msg;
        $returnArray["data"] = $data;
        self::sendResponse(200, json_encode($returnArray), "application/json");
    }

    /**
     * Send response with success status
     * @param type $msg response message
     * @param type $obj response data
     */
    public function sendOk($msg, $obj)
    {
        $returnArray["status"] = "success";
        $returnArray["message"] = $msg;
        $returnArray["data"] = $obj;
        self::sendResponse(200, json_encode($returnArray), "application/json");
    }

    /**
     * Sends the API response
     *
     * @param int $status response status
     * @param string $body response body
     * @param string $content_type response content type
     */
    private function sendResponse($status = 200, $body = '', $content_type = 'application/json')
    {
        $status_header = 'HTTP/1.1 '.$status.' '.$this->getStatusCodeMessage($status);
        // set the status
        header($status_header);
        // set the content type
        header('Content-type: '.$content_type);
        //header('Last-modified: ' . $last_modified);
        // pages with body are easy
        if ($body != '') {
            // send the body
            echo $body;
            exit;
        }
        // we need to create the body if none is passed
        else {
            // create some body messages
            $message = '';

            // this is purely optional, but makes the pages a little nicer to read
            // for your users.  Since you won't likely send a lot of different status codes,
            // this also shouldn't be too ponderous to maintain
            switch ($status) {
                case 401:
                    $message = 'You must be authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL '.$_SERVER['REQUEST_URI'].' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

            // servers don't always have a signature turned on (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'].' Server at '.$_SERVER['SERVER_NAME'].' Port '.$_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

            // this should be templatized in a real-world solution
            $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
                        <html>
                            <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                                <title>'.$status.' '.$this->_getStatusCodeMessage($status).'</title>
                            </head>
                            <body>
                                <h1>'.$this->_getStatusCodeMessage($status).'</h1>
                                <p>'.$message.'</p>
                                <hr />
                                <address>'.$signature.'</address>
                            </body>
                        </html>';

            echo $body;
            exit;
        }
    }

    /**
     * Gets the message for a status code
     *
     * @param mixed $status status code 
     */
    private function getStatusCodeMessage($status)
    {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $codes = Array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );

        return (isset($codes[$status])) ? $codes[$status] : '';
    }

}
