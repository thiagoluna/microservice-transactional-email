<?php

namespace Tests\Feature\Models;

use App\Models\Email;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use function factory;

class EmailTest extends TestCase
{
    use DatabaseMigrations;

    public function test_list_emails()
    {
        factory(Email::class, 1)->create();
        $emails = Email::all();
        $emailKey = array_keys($emails->first()->getAttributes());

        $this->assertCount(1, $emails);
        $this->assertEqualsCanonicalizing(
            [
                'id',
                'name',
                'email',
                'subject',
                'status' ,
                'service',
                'created_at',
                'updated_at',
            ],
            $emailKey
        );
    }

    public function test_store_email()
    {
        $email = factory(Email::class)->create();

        $this->assertEquals('Takeaway', $email->name);
        $this->assertEquals('contact@email.com', $email->email);
        $this->assertEquals('Email Test', $email->subject);
        $this->assertEquals('delivered', $email->status);
        $this->assertNull($email->service);
    }

    public function test_update_email()
    {
        $email = factory(Email::class)->create();

        $data = [
            'name' => 'Takeaway',
            'email' => 'contact@email.com',
            'subject' => 'Email Test',
            'status' => 'delivered',
            'service' => 'Mailjet'
        ];
        $email->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $email->{$key});
        }
    }

    public function test_update_status_email_by_id()
    {
        $email = factory(Email::class)->create();
        $item = $email->find(1);

        $data = ['status' => 'delivered'];
        $item->update($data);

        $this->assertEquals('delivered', $item->status);
    }
}
