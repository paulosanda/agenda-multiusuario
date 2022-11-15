<?php

namespace App\Services;

use App\Models\Agenda;

class AgendaEventoDelete extends BaseService
{
    public function execute($id)
    {
        $response = Agenda::find($id)->delete();

        return json_encode($response);
    }
}
