<?php

namespace App\Repository;

use FOS\UserBundle\Model\User;

class UserRepository extends \Doctrine\ORM\EntityRepository
{
//    /**
//     * @return array
//     * @param User $user
//     */
//    public function selectUsers(User $user) {
//        return $this->createQueryBuilder("user")
//            ->where("user.roles = :role")
//            ->setParameter("role", $user->hasRole())
//            ->getQuery()
//            ->getResult();
//    }
}