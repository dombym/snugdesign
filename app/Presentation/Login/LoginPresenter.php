<?php

declare(strict_types=1);

namespace App\Presentation\Login;

use Nette\Application\UI\Presenter;
use Nette\Application\UI\Form;
use Nextras\FormsRendering\Renderers\Bs5FormRenderer;

final class LoginPresenter extends Presenter
{
    protected function createComponentLoginForm(): Form
    {
        $form = new Form;
        $form->setRenderer(new Bs5FormRenderer);

        $form->addText('email', 'Email')->setRequired();
        $form->addPassword('password', 'Heslo')->setRequired();
        $form->addSubmit('login', 'Přihlásit se');

        $form->onSuccess[] = function (Form $form, array $values) {
            $this->getUser()->login($values['email'], $values['password']);
            $this->redirect('User:list');
        };

        return $form;
    }

    public function actionOut(): void
    {
        $this->getUser()->logout();
        $this->redirect('Login:login');
    }
}