<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CodingApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_create_note()
    {
        $data = [
            'title' => 'new title',
            'content' => 'some data here',
            'due_date' => '2022-04-26',
            'is_done' => 0
        ];

        // $this->withoutExceptionHandling();

        $this->json('POST', 'api/notes', $data)
                ->assertStatus(200)
                ->assertJson($data);
    }

    public function test_get_all_notes(){
        $this->json('GET', 'api/notes')
                ->assertStatus(200);
    }

    public function test_get_all_notes_that_are_done(){
        $isdone = 1;

        $this->json('GET', 'api/notes?$isdone')
                ->assertStatus(200);
    }

    public function test_get_notes_by_tag(){
        $this->json('GET', 'api/notes?tag[]=test&tag[]=one')
                ->assertStatus(200);
    }

    public function test_get_notes_by_search_query_on_title_and_content(){

        $this->json('GET', 'api/notes?search=someword')
                ->assertStatus(200);
    }

    public function test_get_notes_order_by_due_date(){

        $this->json('GET', 'api/notes?order=due_date&direction=desc')
                ->assertStatus(200);
    }

    public function test_get_notes_order_by_creation_date(){

        $this->json('GET', 'api/notes?order=created_at&direction=asc')
                ->assertStatus(200);
    }

}
