<?php

namespace App\UserManagement\Domain\Ports\Inbound;

interface UserRepositoryInterface
{
    public function save(UserViewInterface $user): void;

    public function delete(UserViewInterface $user): void;
}
