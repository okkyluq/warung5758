<?php

namespace App\Repositories\Eloquent;
use App\Repositories\JurnalRepositoryInterface;
use App\HistoryJurnal;


class JurnalRepository implements JurnalRepositoryInterface 
{

    private $model;

    public function __construct(HistoryJurnal $model)
    {
        $this->model = $model;        
    }


    public function getAll()
    {
        return $this->model->all();
    }


}