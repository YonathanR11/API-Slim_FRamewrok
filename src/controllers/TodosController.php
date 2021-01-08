<?php

use App\Models\TodosModel;

class TodosController
{
    // * Mostrar todos los registros con valor del 'status' y idUsuario
    public static function getAllByStatusAndUsuario(int $status, int $todo)
    {
        $todo = TodosModel::all()->where('status', $status)->where('idUser', $todo);
        return $todo;
    }

    // * Crear un registro nuevo
    public static function postTodo(array $data)
    {
        $todo = TodosModel::create($data);
        return $todo;
    }

    // * Actualizar un registro
    public static function putTodo(array $data)
    {
        $todo = TodosModel::find($data['id']);
        $todo->update($data);
        return $todo;
    }

    // * Borrado logico de un registro
    public static function deleteTodo(array $data)
    {
        $todo = TodosModel::find($data['id']);
        $todo->update($data);
        return $todo;
    }
}
