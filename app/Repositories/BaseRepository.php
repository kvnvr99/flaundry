<?php

namespace App\Repositories;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use File;

class BaseRepository implements BaseRepositoryInterface {

    protected $model;
    public function __construct($model) {
        $this->model = $model;
    }

    // Semua Logic CRUD Master Data di proses Core Function ini
    public function get() {
        return $this->model->get();
    }

    public function trashonly() {
        return $this->model->onlyTrashed()->get();
    }

    public function restoretrash($id) {
        return $this->model->withTrashed()->find($id)->restore();
    }

    public function find($id) {
        return $this->model->find($id);
    }

    public function findByParam($column, $param) {
        return $this->model->where($column, $param)->firstOrFail();
    }

    public function findByParams($column, $param) {
        return $this->model->where($column, $param)->get();
    }

    public function store($attributes, $isFile = false, $field = null, $folder = null) {
        if ($isFile == true) {
            foreach ($field as $key => $value) {
                if (request()->file($value)) {
                    $attributes[$value] = request()->file($value)->store('filemanager/' . $folder, 'public');
                }
            }
        }
        return $this->model->create($attributes);
    }

    public function update($params, $attributes, $isFile = false, $field = null, $folder = null) {
        if (is_numeric($params)) {
            $model = $this->model->find($params);
        } else if (is_array($params)) {
            $model = $this->model->where($params)->first();
        }

        if ($model) {
            if ($isFile == true) {
                if (isset($field)) {
                    foreach ($field as $key => $value) {
                        if (request()->file($value)) {
                            File::delete('storage/' . $model[$value]);
                            $attributes[$value] = request()->file($value)->store('filemanager/' . $folder, 'public');
                        }
                    }
                }
            }
            if (is_numeric($params)) {
                $model->update($attributes);
            } else if (is_array($params)) {
                $model->where($params)->update($attributes);
            }
            return $model;
        } else {
            return false;
        }

    }

    public function softDelete($id, $foreign_key = null) {
        $model = $this->model->find($id);
        $model->delete();
        return $model;
    }

    public function hardDelete($id, $isFile = false, $field = null) {
        $model = $this->model->withTrashed()->find($id);
        if ($isFile == true) {
            File::delete('storage/' . $model[$field]);
        }
        return $model->forceDelete();
    }
    // End Core Function

    public function Query() {
        return $this->model->query();
    }
};
