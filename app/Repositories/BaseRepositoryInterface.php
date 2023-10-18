<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function get();
    public function find($id);
    public function findByParam($column, $param);
    public function store($attributes, $isFile = false, $field = null, $folder = null);
    public function update($id, $attributes, $isFile = false, $field = null, $folder = null);
    public function softDelete($id);
    public function hardDelete($id);
    public function Query();
}
