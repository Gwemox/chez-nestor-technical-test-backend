<?php

namespace App\Service\CrudService;

interface CrudServiceInterface
{
    public function getAll(string $entity): array;

    public function getOneById(string $entity, $id);

    public function post(string $entity, string $formTypeClass);

    public function edit(string $entity, string $formTypeClass, $id);

    public function delete(string $entity, $id);
}