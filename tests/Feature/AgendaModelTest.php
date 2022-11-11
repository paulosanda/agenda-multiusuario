<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Agenda;
use Tests\TestCase;

class AgendaModelTest extends TestCase
{
    use RefreshDatabase;
    public function setup(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }
    /** @test */
    public function agendaInsert()
    {
        $user = User::factory()->createOne();

        $event = Agenda::factory()->createOne([
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('agendas', [
            'user_id' => $user->id,
            "evento" => $event->evento,
            "dataHora" => $event->dataHora,
        ]);
    }
}
