<?php

namespace Luma\Tests\Classes;

use Luma\AuroraDatabase\Attributes\Column;
use Luma\AuroraDatabase\Attributes\Identifier;
use Luma\AuroraDatabase\Attributes\Schema;
use Luma\AuroraDatabase\Model\Aurora;
use Luma\AuroraDatabase\Utils\Collection;
use Luma\SecurityComponent\Authorization\AbstractPermission;
use Luma\SecurityComponent\Authorization\Interface\RoleInterface;

#[Schema('FrameworkComponentTest')]
class Role extends Aurora implements RoleInterface
{
    #[Identifier]
    #[Column('intRoleId')]
    protected int $id;

    #[Column('strName')]
    protected string $name;

    #[Column('strHandle')]
    protected string $handle;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHandle(): string
    {
        return $this->handle;
    }

    /**
     * @return Collection
     */
    public function getPermissions(): Collection
    {
        return new Collection();
    }

    /**
     * @param AbstractPermission|string $permission
     *
     * @return bool
     */
    public function hasPermission(AbstractPermission|string $permission): bool
    {
        return false;
    }
}