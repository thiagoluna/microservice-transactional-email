<?php

namespace Tests\Feature\Api;

use App\Models\Email;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;

class EmailApiTest extends TestCase
{
    use DatabaseMigrations;

    public function test_endpoint_listemails()
    {
        $response = $this->get('/api/v1/listemail');
        $response->assertStatus(200);
    }

    public function test_list_all_emails()
    {
        factory(Email::class, 2)->create();
        $emails = Email::all();
        $response = $this->get('/api/v1/listemail');

        $this->assertCount(2, $emails);
    }

    public function test_store_email()
    {
        $response = $this->postJson('/api/v1/sendemail', [
            'name' => 'Takeaway',
            'email' => 'contact@email.com',
            'subject' => 'Subject',
            'content' => 'Content'
        ]);

        $response->assertStatus(201)
                ->assertExactJson(["success" => "Email saved and sent to queue."]);
    }

    public function testStoreWithRequiredFields()
    {
        $response = $this->postJson('/api/v1/sendemail', [
            'name' => '',
            'email' => '',
            'subject' => '',
            'content' => ''
        ]);

        $this->assertInvalidationFields($response, ['name'], 'required', []);
        $this->assertInvalidationFields($response, ['email'], 'required', []);
        $this->assertInvalidationFields($response, ['subject'], 'required', []);
        $this->assertInvalidationFields($response, ['content'], 'required', []);
    }

    protected function assertInvalidationFields(
        TestResponse $response,
        array $fields,
        string $rule,
        array $ruleParams = []
    )
    {
        $response->assertStatus(422)
            ->assertJsonValidationErrors($fields);

        foreach ($fields as $field) {
            $fieldName = str_replace('_', ' ', $field);
            $response->assertJsonFragment([
                Lang::get("validation.{$rule}", ['attribute' => $fieldName] + $ruleParams)
            ]);
        }
    }
}
