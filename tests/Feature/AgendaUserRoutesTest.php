<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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

        /** @var User $user */
        $this->actingAs($user)->post(route('agenda.user.store'), [
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

        /** @var User $user*/
        $this->actingAs($user)->get(route('agenda.user.show', $evento->id))
            ->assertStatus(200);
        $this->assertDatabaseHas('agendas', [
            'user_id' => $user->id,
            'evento' => $evento->evento,
            'dataHora' => $evento->dataHora
        ]);
    }

    /** @test*/
    public function denyShowEventForAnotherUser()
    {
        $owner = User::factory()->createOne();
        $user = User::factory()->createOne();

        $evento = Agenda::factory()->create([
            'user_id' => $owner->id,
        ]);

        /** @var User $user */
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

        /** @var User $admin */
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

    /** @test */
    public function UpdateAgendaUser()
    {

        $user = User::factory()->createOne(
            ['is_admin'  => 1]
        );
        $agenda = Agenda::factory()->create([
            'user_id' => $user->id
        ]);

        /** @var User $user*/
        $this->actingAs($user)->put(route('agenda.user.update', $agenda->id), [
            'user_id' => $user->id,
            'evento' => 'Evento alterado no teste',
            'dataHora' => '1971-02-25 12:01:00',
        ])->assertOk();

        $this->assertDatabaseHas('agendas', [
            'id' => $agenda->id,
            'user_id' => $user->id,
            'evento' => 'Evento alterado no teste',
        ]);
    }

    /** @test*/
    public function UpdateForbidenToUserWhosNotOwnAgenda()
    {
        $user = User::factory()->createOne();
        $anotherUser = User::factory()->createOne();

        $agenda = Agenda::factory()->create([
            'user_id' => $user->id
        ]);

        /** @var User $anotherUser*/
        $this->actingAs($anotherUser)->put(route('agenda.user.update', $agenda->id), [
            'user_id' => $user->id,
            'evento' => 'Evento alterado no teste',
            'dataHora' => '1971-02-25 12:01:00',
        ])->assertForbidden();
    }

    /** @test */
    public function AdminUserCanUpdateAnotherUserAgenda()
    {

        /** @var User $user */
        $admin = User::factory()->createOne([
            'is_admin' => 1,
        ]);

        $user = User::factory()->createOne(
            ['is_admin'  => 1]
        );
        $agenda = Agenda::factory()->create([
            'user_id' => $user->id
        ]);

        /** @var User $admin */
        $this->actingAs($admin)->put(route('agenda.user.update', $agenda->id), [
            'user_id' => $user->id,
            'evento' => 'Evento alterado no teste',
        ]);
        $this->assertDatabaseHas('agendas', [
            'id' => $agenda->id,
            'user_id' => $user->id,
            'evento' => 'Evento alterado no teste',
        ]);
    }

    /** @test*/
    public function listEventsUserAgenda()
    {
        $users = User::factory()->count(10)->create();
        foreach ($users as $user) {
            Agenda::factory()->count(5)->create(['user_id' => $user->id]);
        }
        $user = User::factory()->createOne();
        $agenda = Agenda::factory()->count(2)->create(['user_id' => $user->id]);

        /** @var User $user*/
        $result = $this->actingAs($user)->get(route('agenda.user.list', $user->id))
            ->assertStatus(200)
            ->assertJsonCount(2);
    }

    /** @test*/
    public function adminUserCanViewAnyAgendaGate()
    {
        $users = User::factory()->count(10)->create();
        foreach ($users as $user) {
            Agenda::factory()->count(5)->create(['user_id' => $user->id]);
        }
        $admin = User::factory()->createOne(['is_admin' => true]);

        /** @var User $admin*/
        $result = $this->actingAs($admin)->get(route('agenda.user.list', 1))
            ->assertStatus(200)
            ->assertJsonCount(5);
    }

    /** @test*/
    public function userCanDeleteEventoInOwnAgenda()
    {
        $user = User::factory()->createOne();
        $agenda = Agenda::factory()->createOne([
            'user_id' => $user->id
        ]);

        /** @var User $user */
        $this->actingAs($user)->delete(route('agenda.user.delete', $agenda->id))
            ->assertStatus(200);
        $this->assertDatabaseMissing('agendas', [
            'id' => $agenda->id,
        ]);
    }

    /** @test*/
    public function adminCanDeleteAnyEvento()
    {
        $user = User::factory()->createOne();
        $agenda = Agenda::factory()->createOne([
            'user_id' => $user->id
        ]);

        $admin = User::factory()->createOne([
            'is_admin' => 1,
        ]);

        /** @var User $admin */
        $this->actingAs($admin)->delete(route('agenda.user.delete', $agenda->id))
            ->assertStatus(200);
        $this->assertDatabaseMissing('agendas', [
            'id' => $agenda->id,
        ]);
    }
}
