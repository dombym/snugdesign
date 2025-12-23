<?php

declare(strict_types=1);

namespace App\Model\User;

use Nextras\Orm\Mapper\Mapper;

final class UsersMapper extends Mapper
{
   
    public function getTableName(): string
    {
        return 'users';
    }
}