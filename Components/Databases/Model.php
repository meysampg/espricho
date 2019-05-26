<?php

namespace Espricho\Components\Databases;

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
     * @var string
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @var string
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;

    /**
     * @var string
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deleted_at;

    public function getCreatedAt(): Carbon
    {
        return Carbon::createFromTimeString($this->created_at);
    }

    public function setCreatedAt(string $dt)
    {
        $this->created_at = $dt;
    }

    public function getUpdatedAt(): Carbon
    {
        return Carbon::createFromTimeString($this->updated_at);
    }

    public function setUpdatedAt(string $dt)
    {
        $this->updated_at = $dt;
    }

    public function getDeletedAt(): Carbon
    {
        return Carbon::createFromTimeString($this->deleted_at);
    }

    public function setDeletedAt(string $dt)
    {
        $this->deleted_at = $dt;
    }
}
