<?php

namespace App\Services;

use App\Models\Agenda;

class AgendaUserStore extends BaseService
{
    public function rules()
    {
        return [
            'evento' => 'required|string',
            'dataHora' => 'required|date_format:Y-m-d H:i:s',
        ];
    }

    /**
     * execute
     *
     * @param  mixed $data
     *
     */
    public function execute($data)
    {
        $this->validate($data);

        $evento = Agenda::create($data);

        return json_encode($evento);
    }
}
