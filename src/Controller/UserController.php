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

    /**
     * @Route("/authState", name="authState", methods={"GET"},
     *     defaults={
     *         "_api_resource_class"=User::class,
     *         "_api_collection_operation_name"="authState"
     *     })
     * @return User|null
     */
    public function authStateAction(): User
    {
        return $this->getUser();
    }

    /**
     * @Route("/saveRoles/{id}", name="saveRoles", methods={"PUT"},
     *     defaults={
     *         "_api_resource_class"=User::class,
     *         "_api_item_operation_name"="saveRoles"
     *     })
     * @param User $data
     * @return User|null
     */
    public function saveRolesAction(User $data): User
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Вы не можете изменять права');

        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        return $data;
    }

}
