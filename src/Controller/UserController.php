<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/byLastName", name="byLastName", methods={"POST"},
     *     defaults={
     *         "_api_resource_class"=User::class,
     *         "_api_collection_operation_name"="byLastName"
     *     })
     * @param User $data
     * @return User|null
     */
    public function byLastNameAction(User $data): User
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneByFirstName($data->getName());
        return $users;
    }
}
