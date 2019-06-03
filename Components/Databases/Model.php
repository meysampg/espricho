<?php

namespace Espricho\Components\Databases;

use JsonSerializable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Espricho\Components\Supports\Carbon;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use function count;
use function sprintf;
use function in_array;
use function method_exists;

/**
 * Class Model provides common a data mapper model functionality
 *
 * @package Espricho\Components\Databases
 */
abstract class Model implements JsonSerializable
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
     * Call a model clean, if it's not mass loaded
     *
     * @var bool
     */
    protected $isClean = true;

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return sprintf("%s#%s", static::class, $this->getId());
    }

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
     * Check the model has validation error or not
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return count($this->errors) != 0;
    }

    /**
     * Indicate model is mass loaded or not
     *
     * @return bool
     */
    public function isDirty(): bool
    {
        return !$this->isClean;
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

        return !$this->hasErrors();
    }

    /**
     * Load a given set of data into the model
     *
     * @param array $data
     *
     * @return bool
     */
    public function load(array $data): bool
    {
        $done = false;

        foreach ($data as $property => $value) {
            if (
                 !in_array($property, (array)$this->fillable)
                 || !method_exists($this, 'set' . $property)
            ) {
                continue;
            }

            $this->{'set' . $property}($value);
            $done = true;
        }

        $this->isClean = !$done;

        return $done;
    }

    /**
     * createdAt getter
     *
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return Carbon::instance($this->createdAt);
    }

    /**
     * createdAt setter
     *
     * @param DateTimeInterface $dt
     */
    public function setCreatedAt(DateTimeInterface $dt)
    {
        $this->createdAt = $dt;
    }

    /**
     * updatedAt getter
     *
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return Carbon::instance($this->updatedAt);
    }

    /**
     * updatedAt setter
     *
     * @param DateTimeInterface $dt
     */
    public function setUpdatedAt(DateTimeInterface $dt)
    {
        $this->updatedAt = $dt;
    }

    /**
     * deletedAt setter
     *
     * @return Carbon
     */
    public function getDeletedAt(): Carbon
    {
        return Carbon::instance($this->deletedAt);
    }

    /**
     * deletedAt setter. For safe delete fill it with a value and to undelete
     * fill it with null value.
     *
     * @param DateTimeInterface|null $dt
     */
    public function setDeletedAt(?DateTimeInterface $dt)
    {
        $this->deletedAt = $dt;
    }

    /**
     * @inheritDoc
     */
    abstract public function jsonSerialize(): array;
}
