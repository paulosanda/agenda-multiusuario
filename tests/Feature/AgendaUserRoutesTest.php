<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Agenda;

class AgendaUserRoutesTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function createEventoUser()
    {
        $user = User::factory()->createOne();
        $evento = fake()->sentence();
        $dataHora = date_format(fake()->dateTime(), 'Y-m-d H:i:s');

        $create = $this->actingAs($user)->post(route('agenda.user.store'), [
            'user_id' => $user->id,
            'evento' => $evento,
            'dataHora' => $dataHora,
        ])->assertStatus(200);
        $this->assertDatabaseHas('agendas', [
            'user_id' => $user->id,
            'evento' => $evento,
            'dataHora' => $dataHora,
        ]);
    }

    /** @test */
    public function showEventUser()
    {
        $user = User::factory()->createOne();

        $evento = Agenda::factory()->create(
            ['user_id' => $user->id,]
        );

        $show = $this->actingAs($user)->get(route('agenda.user.show', $evento->id))
            ->assertStatus(200);
    }

    /** @test*/
    public function denyShowEventForAnotherUser()
    {
        $owner = User::factory()->createOne();
        $user = User::factory()->createOne();

        $evento = Agenda::factory()->create([
            'user_id' => $owner->id,
        ]);

        $this->actingAs($user)->get(route('agenda.user.show', $evento->id))
            ->assertStatus(403);
    }

    /** @test*/
    public function ShowAnotherUserAgendaToAdmin()
    {
        $admin = User::factory()->createOne(['is_admin' => 1]);
        $user = User::factory()->createOne();

        $evento = Agenda::factory()->create([
            'user_id' => $user->id,
        ]);
        $this->actingAs($admin)->get(route('agenda.user.show', $evento->id))
            ->assertStatus(200);
    }

    /** @test*/
    public function DenyAcessWithouLogin()
    {
        $user = User::factory()->createOne();

        $evento = Agenda::factory()->create(
            ['user_id' => $user->id,]
        );

        $show = $this->get(route('agenda.user.show', $evento->id))
            ->assertStatus(500);
    }
}
