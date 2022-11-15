<?php

namespace App\Http\Controllers;

use App\Services\AgendaUserStore;
use App\Services\AgendaEventoUpdate;
use App\Services\AgendaEventoShow;
use App\Services\AgendaEventoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Agenda;
use App\Models\User;

class AgendaUserController extends Controller
{


    /**
     * list
     * @param  mixed $agenda
     * @param  mixed $id
     * @return void
     */
    public function list($id)
    {
        $this->authorize('list', $id);

        $response = app(AgendaEventoList::class)->execute($id);

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @return mixed
     */
    public function store(Request $request)
    {
        $agenda = app(AgendaUserStore::class)->execute([
            'user_id' => Auth::user()->id,
            'evento' => $request->evento,
            'dataHora' => $request->dataHora,
        ]);

        return response($agenda, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param  string $agenda
     * @return \Illuminate\Http\Response
     */
    public function show(Agenda $agenda, $id)
    {
        $this->authorize('view', $agenda->find($id));

        $response = app(AgendaEventoShow::class)->execute($id);

        return $response;
    }

    /**
     * update
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed $id
     * @param  string $agenda
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Agenda $agenda, $id)
    {
        $this->authorize('update', $agenda);

        $evento = $request->evento ? $request->evento : null;
        $dataHora = $request->dataHora ? $request->dataHora : null;

        $response = app(AgendaEventoUpdate::class)->execute([
            'id' => $id,
            'user_id' => $request->user_id,
            'evento' => $evento,
            'dataHora' => $dataHora
        ]);

        return $response;
    }
}
