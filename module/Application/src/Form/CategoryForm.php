<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Application\Entity\Category;

/**
 * This form is used to collect post data.
 */
class CategoryForm extends Form
{

    /**
     * Constructor.     
     */
    public function __construct()
    {
        // Define form name
        parent::__construct('post-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {

        // Add "title" field
        $this->add([
            'type' => 'text',
            'name' => 'name',
            'attributes' => [
                'id' => 'name'
            ],
            'options' => [
                'label' => 'Name',
            ],
        ]);

        // Add "status" field
        $this->add([
            'type' => 'select',
            'name' => 'status',
            'attributes' => [
                'id' => 'status'
            ],
            'options' => [
                'label' => 'Status',
                'value_options' => [
                    Category::STATUS_ACTIVE => 'Active',
                    Category::STATUS_INACTIVE => 'Inactive',
                ]
            ],
        ]);

        // Add the submit button
        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Create',
                'id' => 'submitbutton',
            ],
        ]);
    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter()
    {

        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 1024
                    ],
                ],
            ],
        ]);
    }

}
