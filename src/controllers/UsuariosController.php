<?php

use App\Models\UsuariosModel;

class UsuariosController
{
    // * Actualizar un registro
    public static function putTodo(array $data)
    {
        $todo = UsuariosModel::find($data['idUser']);
        $todo->update($data);
        return $todo;
    }
}
