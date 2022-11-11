<?php

namespace App\Services;

use App\Models\Agenda;

class AgendaEventoStore extends BaseService
{
    public function rules()
    {
        return [
            'evento' => 'required|string',
            'dataHora' => 'required|date_format:Y-m-d H:i:s',
        ];
    }

    public function execute($data)
    {
        $this->validate($data);
        $evento = Agenda::create($data);
        return $evento;
    }
}
