<?php

namespace Redbastie\Crudify\Traits;

use Illuminate\Support\Str;
use JamesMills\LaravelTimezone\Facades\Timezone;

trait BuildsTables
{
    private $modelInstance;

    protected function dataTables($query)
    {
        $dataTables = datatables()->eloquent($query);

        foreach (app($this->model)->getDates() as $date) {
            $dataTables->editColumn($date, function ($model) use ($date) {
                return Timezone::convertToLocal($model->$date);
            });
        }

        return $dataTables;
    }

    protected function tableBuilder()
    {
        return $this->builder()
            ->setTableId(Str::snake(class_basename($this->model), '-') . '-table')
            ->autoWidth(false)
            ->responsive()
            ->stateSave()
            ->stateDuration(0)
            ->stateSaveParams("function (settings, data) { data.search.search = ''; data.start = 0; }")
            ->minifiedAjax()
            ->columns($this->getColumns());
    }
}
