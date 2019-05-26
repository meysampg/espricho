<?php

namespace Espricho\Components\Databases;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Espricho\Components\Supports\Carbon;

/**
 * Class Model provides common a data mapper model functionality
 *
 * @package Espricho\Components\Databases
 */
class Model
{
    /**
     * @var DateTimeInterface
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var DateTimeInterface
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @var DateTimeInterface
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deletedAt;

    public function getCreatedAt(): Carbon
    {
        return Carbon::instance($this->createdAt);
    }

    public function setCreatedAt(string $dt)
    {
        $this->createdAt = $dt;
    }

    public function getUpdatedAt(): Carbon
    {
        return Carbon::instance($this->updatedAt);
    }

    public function setUpdatedAt(string $dt)
    {
        $this->deletedAt = $dt;
    }

    public function getDeletedAt(): Carbon
    {
        return Carbon::instance($this->deletedAt);
    }

    public function setDeletedAt(string $dt)
    {
        $this->deletedAt = $dt;
    }
}
