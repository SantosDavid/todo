<?php

namespace Tests\AppBundle\Unit\Form;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Component\Form\Test\TypeTestCase;

class UserTypeTest extends TypeTestCase
{
    public function testSubmitValidDate()
    {
        $formData = [
            'username' => 'David',
            'email' => 'david.santos@email.com',
            'password' => '123456',
        ];

        $form = $this->factory->create(UserType::class);

        $user = new User();
        $user->fromArray($formData);

        $formData['password'] = ['first' => '123456', 'second' => '123456'];
        $form->submit($formData);

        $this->assertEquals($form->getData(), $user);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}