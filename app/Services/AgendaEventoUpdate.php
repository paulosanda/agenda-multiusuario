<?php

namespace App\Services;

use App\Models\Agenda;

class AgendaEventoUpdate extends BaseService
{
    public function rules()
    {
        return [
            'id' => 'required',
            'user_id' => 'required',
            'evento' => 'nullable|string',
            'dataHora' => 'nullable|date_format:Y-m-d H:i:s',
        ];
    }

    public function execute($data)
    {

        $this->validate($data);

        $actual = Agenda::findOrFail($data['id']);

        $evento = $data['evento'] ? $data['evento'] : $actual->evento;
        $dataHora = $data['dataHora'] ? $data['dataHora'] : $actual->dataHora;

        $evento = Agenda::findOrFail($data['id'])->update([
            'evento' => $evento,
            'dataHora' => $dataHora,
        ]);

        return $evento;
    }
}
