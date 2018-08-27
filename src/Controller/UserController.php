<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/byLastName/{name}", name="byLastName", methods={"GET"},
     *     defaults={
     *         "_api_resource_class"=User::class,
     *         "_api_collection_operation_name"="byLastName"
     *     })
     */
    public function byLastNameAction(string $name)
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneByFirstName($name);
        return $users;
    }
}
