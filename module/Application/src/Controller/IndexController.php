<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * This is the main controller class of the Blog application. The 
 * controller class is used to receive user input,  
 * pass the data to the models and pass the results returned by models to the 
 * view for rendering.
 */
class IndexController extends AbstractActionController
{
    /**
     * This action displays the About page.
     */
    public function aboutAction()
    {
        $appName = 'ZF App';
        $appDescription = 'A simple application with Zend Framework 3';

        return new ViewModel([
            'appName' => $appName,
            'appDescription' => $appDescription
        ]);
    }

}
