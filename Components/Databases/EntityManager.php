<?php

namespace Espricho\Components\Databases;

use Doctrine\ORM\EntityManager as BaseEntityManager;

class EntityManager extends BaseEntityManager
{
    /**
     * Find or create a model and populate it with given data. If it fail, return
     * an empty instance.
     *
     * @param string $model
     * @param array  $data
     *
     * @return Model
     */
    public static function findOrCreate(string $model, array $data): Model
    {
        if (isset($data['id'])) {
            $fetched = em()->getRepository($model)->findOneBy(['id' => $data['id']]);
            if ($fetched) {
                $model = $fetched;
            } else {
                $model = new $model;
            }
        } else {
            $model = new $model();
        }

        $model->load($data);

        return $model;
    }
}
