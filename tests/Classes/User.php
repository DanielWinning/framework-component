<?php

namespace Luma\Tests\Classes;

use Luma\AuroraDatabase\Attributes\AuroraCollection;
use Luma\AuroraDatabase\Attributes\Column;
use Luma\AuroraDatabase\Attributes\Identifier;
use Luma\AuroraDatabase\Attributes\Schema;
use Luma\AuroraDatabase\Attributes\Table;
use Luma\AuroraDatabase\Model\Aurora;
use Luma\AuroraDatabase\Utils\Collection;
use Luma\SecurityComponent\Attributes\SecurityIdentifier;
use Luma\SecurityComponent\Authentication\AbstractUser;

#[Schema('FrameworkComponentTest')]
class User extends AbstractUser
{
    #[Identifier]
    #[Column('intUserId')]
    protected int $id;

    #[SecurityIdentifier]
    #[Column('strUsername')]
    protected string $username;

    #[Column('strPassword')]
    protected string $password;

    #[Column('strEmailAddress')]
    private string $emailAddress;

    #[AuroraCollection()]
    protected Collection $roles;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): Collection
    {
        return $this->roles;
    }
}