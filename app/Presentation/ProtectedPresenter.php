<?php

declare(strict_types=1);

namespace App\Presentation;

use Nette\Application\UI\Presenter;

abstract class ProtectedPresenter extends Presenter
{
    protected function startup(): void
    {
        parent::startup();

        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Login:login');
        }
    }
}