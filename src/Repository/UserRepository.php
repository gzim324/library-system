<?php

namespace App\Repository;

class UserRepository extends \Doctrine\ORM\EntityRepository
{
//    /**
//     * @return array
//     */
//    public function selectUsers() {
//        return $this->createQueryBuilder("user")
//            ->where("user.roles = :role")
//            ->setParameter("role", array(User::ROLE_DEFAULT))
//            ->getQuery()
//            ->getResult();
//    }
}