<?php

namespace Tutorial\Ticket\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Tutorial\User\Models\User;
use Tutorial\RolePermissions\Database\seeders\RolePermissionTableSeeder;
use Tutorial\RolePermissions\Models\Permission;
use Tutorial\Ticket\Models\Reply;
use Tutorial\Ticket\Models\Ticket;

class TicketTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_can_see_tickets(): void
    {
        $this->actionAsUser();
        $this->get(route('tickets.index'))->assertOk();
    }

    public function test_user_can_see_create_ticket_form(): void
    {
        $this->actionAsUser();
        $this->get(route('tickets.create'))->assertOk();
    }

    public function test_user_can_create_ticket(): void
    {
        $this->actionAsUser();
        $this->createTicket();
        $this->assertEquals(1,Ticket::count());
    }

    public function test_user_can_reply_ticket(): void
    {
        $this->actionAsUser();
        $this->createTicket();
        $this->post(route('tickets.reply',1),[
            'body'=>fake()->text(),
            'attachment' => UploadedFile::fake()->create('attach.zip',2048)
        ])->assertRedirect();
        $this->assertEquals(2,Reply::where('ticket_id',1)->count());
    }

    public function test_user_permitted_can_delete_ticket(): void
    {
        $this->actionAsAdmin();
        $this->createTicket();
        $this->assertEquals(1,Ticket::count());
        $this->delete(route('tickets.destroy',1))->assertOk();
        $this->assertEquals(0,Ticket::count());
    }

    public function test_user_permitted_can_not_delete_ticket(): void
    {
        $this->actionAsUser();
        $this->createTicket();
        $this->assertEquals(1,Ticket::count());
        $this->delete(route('tickets.destroy',1))->assertStatus(403);
        $this->assertEquals(1,Ticket::count());
    }

    public function test_user_can_close_ticket(): void
    {
        $this->actionAsUser();
        $this->createTicket();
        $this->patch(route('tickets.close',1))->assertOk();
        $this->assertEquals(Ticket::STATUS_CLOSE,Ticket::where('id',1)->first()->status);
    }

    public function test_user_can_not_close_other_ticket(): void
    {
        $this->actionAsUser();
        $this->createTicket();
        $this->actionAsUser();
        $this->patch(route('tickets.close',1))->assertStatus(403);
        $this->assertEquals(Ticket::STATUS_OPEN,Ticket::where('id',1)->first()->status);
    }


    private function actionAsAdmin()
    {
        $this->createUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_TICKETS);
    }

    private function actionAsUser()
    {
        $this->createUser();
    }

    private function createUser()
    {
        $this->actingAs(User::factory()->make());
        auth()->user()->markEmailAsVerified();
        $this->seed(RolePermissionTableSeeder::class);
    }

    private function createTicket()
    {
        return $this->post(route('tickets.store'),[
            'title' => fake()->title(),
            'body' => fake()->text(),
        ]);
    }

}
