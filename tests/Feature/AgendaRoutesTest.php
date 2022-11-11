<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class AgendaRoutesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     *
     * @return void
     */
    public function routeAgendaStore()
    {
        $evento = fake()->sentence();
        $dataHora = date_format(fake()->dateTime(), 'Y-m-d H:i:s');
        $user = User::factory()->createOne();
        $teste = $this->actingAs($user)->post(route('agenda.store'), [
            'user_id' => $user->id,
            'evento' => $evento,
            'dataHora' => $dataHora,
        ]);
        $this->assertDatabaseHas('agendas', [
            'user_id' => $user->id,
            'evento' => $evento,
            'dataHora' => $dataHora,
        ]);
    }
}
