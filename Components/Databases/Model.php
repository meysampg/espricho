<?php

namespace Espricho\Components\Databases;

use JsonSerializable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Espricho\Components\Supports\Carbon;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use function in_array;
use function method_exists;
use function property_exists;

/**
 * Class Model provides common a data mapper model functionality
 *
 * @package Espricho\Components\Databases
 */
abstract class Model implements JsonSerializable
{
    /**
     * List of properties which can be mass assign with the load method
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * Store validation of business errors
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Return business validation errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Set Business validation errors
     *
     * @param ConstraintViolationList $errors
     */
    public function setErrors(ConstraintViolationList $errors): void
    {
        foreach ($errors as $error) {
            $this->errors[$error->getPropertyPath()][] = $error->getMessage();
        }
    }

    /**
     * Validate the model based on the values stored on it and its business rules
     *
     * @return bool
     */
    public function validate(): bool
    {
        $errors = service(ValidatorInterface::class)->validate($this);
        $this->setErrors($errors);

        return count($errors) == 0;
    }

    /**
     * Load a given set of data into the model
     *
     * @param array $data
     */
    public function load(array $data)
    {
        foreach ($data as $property => $value) {
            if (
                 !in_array($property, (array)$this->fillable)
                 || !property_exists($this, $property)
                 || !method_exists($this, 'set' . $property)
            ) {
                continue;
            }

            $this->{'set' . $property}($value);
        }
    }

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

    public function setCreatedAt(DateTimeInterface $dt)
    {
        $this->createdAt = $dt;
    }

    public function getUpdatedAt(): Carbon
    {
        return Carbon::instance($this->updatedAt);
    }

    public function setUpdatedAt(DateTimeInterface $dt)
    {
        $this->updatedAt = $dt;
    }

    public function getDeletedAt(): Carbon
    {
        return Carbon::instance($this->deletedAt);
    }

    public function setDeletedAt(DateTimeInterface $dt)
    {
        $this->deletedAt = $dt;
    }

    abstract public function jsonSerialize(): array;
}
