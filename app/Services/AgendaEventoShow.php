<?php

namespace App\Services;

use App\Models\Agenda;

class AgendaEventoShow extends BaseService
{
    public function execute($id)
    {
        $response = Agenda::find($id);

        return json_encode($response);
    }
}
