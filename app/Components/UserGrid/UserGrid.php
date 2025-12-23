<?php

declare(strict_types=1);

namespace App\Components\UserGrid;

use Nextras\Datagrid\Datagrid;
use Nextras\Datagrid\Paginator;
use Nette\Application\UI\Control;
use App\Model\User\UsersRepository;

final class UserGrid extends Control
{
    public function __construct(
        private UsersRepository $usersRepository
    ) {}

    protected function createComponentGrid(): Datagrid
    {
        $grid = new Datagrid;

        $grid->setRowPrimaryKey('id');

        $grid->addColumn('id', 'ID');
        $grid->addColumn('firstName', 'First Name');
        $grid->addColumn('lastName', 'Last Name');
        $grid->addColumn('email', 'Email');

        $grid->setDatasourceCallback(function (
            array $filter,
            ?array $order,
            ?Paginator $paginator
        ) {
            $collection = $this->usersRepository->findAll();

            if ($order !== null) {
                [$col, $dir] = $order;
                $collection->order("$col $dir");
            }

            if ($paginator !== null) {
                $collection->limit(
                    $paginator->getItemsPerPage(),
                    $paginator->getOffset()
                );
            }

            return $collection;
        });

        return $grid;
    }

    public function render(): void
    {
        $this['grid']->render();
    }
}
