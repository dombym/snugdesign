<?php

declare(strict_types=1);

namespace App\Presentation\User;

use App\Components\UserGrid\UserGrid;
use App\Presentation\ProtectedPresenter;
use App\Model\User\UsersRepository;
use Nette\Application\UI\Control;

final class UserPresenter extends ProtectedPresenter
{
    private UsersRepository $usersRepository;

    public function __construct(UsersRepository $usersRepository)
    {
        parent::__construct();
        $this->usersRepository = $usersRepository;
    }

    protected function createComponentUsersGrid(): Control
    {
        return new UserGrid($this->usersRepository);
    }

    public function renderList(): void
    {
        
    }
}
