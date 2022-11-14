<?php

namespace App\Services;

use App\Models\Agenda;

class AgendaEventoList extends BaseService
{
    public function execute($id)
    {
        $response = Agenda::where('user_id', $id)->get();

        return json_encode($response);
    }
}
