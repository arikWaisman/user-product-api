<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductRestTest extends TestCase
{

	protected $token;

	/**
     * A basic test example.
     *
     * @return void
     */
    public function testGetProduct()
    {
		$this->getToken();

		$response = $this->get('/api/lawline/v1/product?token=' . $this->token);

        $response->assertStatus(200);

    }


    protected function getToken($data=['email'=>'johndoe@gmail.com', 'password'=>'secret']){

//		$this->post('/api/lawline/v1/login', $data);
//		$content = json_decode($this->response->getContent());
//
//		$this->assertObjectHasAttribute('token', $content, 'Token does not exists');
//		$this->token = $content->token;
//
//		return $this;


	}
}
