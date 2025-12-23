<?php

declare(strict_types=1);

namespace App\Model\Security;

use Nette\Security\Authenticator;
use Nette\Security\SimpleIdentity;
use Nette\Security\AuthenticationException;
use Nextras\Orm\Repository\Repository;

final class UserAuthenticator implements Authenticator
{
    private Repository $usersRepository;

    public function __construct(Repository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function authenticate(string $username, string $password): SimpleIdentity
    {
        $user = $this->usersRepository->findBy(['email' => $username])->fetch();

        if (!$user) {
            throw new AuthenticationException('Uživatel nenalezen.');
        }

        if (!password_verify($password, $user->password)) {
            throw new AuthenticationException('Nesprávné heslo.');
        }

        return new SimpleIdentity($user->id, null, [
            'email' => $user->email,
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
        ]);
    }
}
