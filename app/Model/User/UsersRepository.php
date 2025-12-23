<?php

declare(strict_types=1);

namespace App\Model\User;

use Nextras\Orm\Repository\Repository;

/**
 * @method User|null getById(int $id)
 * @method User[] findAll()
 */
final class UsersRepository extends Repository
{
    public static function getEntityClassNames(): array
    {
        return [User::class];
    }

    public function getByEmail(string $email): ?User
    {
        return $this->findBy(['email' => $email])->fetch();
    }
}