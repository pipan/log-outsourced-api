<?php

namespace Tests\Feature\Api\V1\Administrator;

use App\Domain\Administrator\AdministratorEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class RegisterTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $administrators = [
            $this->createUser('1', 'test', '', '010101'),
            $this->createUser('2', 'test-pass', 'aaaaaa', '')
        ];

        foreach ($administrators as $administrator) {
            $this->administratorRepository->getMocker()
                ->getSimulation('exists')
                ->whenInputReturn(true, [$administrator->getUsername()]);
            $this->administratorRepository->getMocker()
                ->getSimulation('getByUsername')
                ->whenInputReturn($administrator, [$administrator->getUsername()]);
        }
    }

    private function createUser($id, $username, $password, $inviteToken)
    {
        return new AdministratorEntity($id, $username, $password, $inviteToken);
    }

    public function testResponseOk()
    {
        $response = $this->post('api/v1/register', [
            'invite_token' => '010101',
            'username' => 'test',
            'password' => 'test'
        ]);

        $response->assertStatus(200);
    }

    public function testOkUpdatePasswordHash()
    {
        $this->post('api/v1/register', [
            'invite_token' => '010101',
            'username' => 'test',
            'password' => 'test'
        ]);

        $result = $this->administratorRepository->getMocker()
            ->getSimulation('update')
            ->getExecutions();

        $this->assertCount(1, $result);
        $this->assertNotEmpty($result[0][1]->getPasswordHash());
    }

    public function testOkUpdateInviteToken()
    {
        $this->post('api/v1/register', [
            'invite_token' => '010101',
            'username' => 'test',
            'password' => 'test'
        ]);

        $result = $this->administratorRepository->getMocker()
            ->getSimulation('update')
            ->getExecutions();

        $this->assertCount(1, $result);
        $this->assertEmpty($result[0][1]->getInviteToken());
    }

    public function testResponseValidationErrorIfEmptyUsesrname()
    {
        $response = $this->post('api/v1/register', [
            'invite_token' => '010101',
            'username' => '',
            'password' => 'test'
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorIfUsernameTooLong()
    {
        $username = "";
        for ($i = 0; $i < 256; $i++) {
            $username .= "a";
        }
        $response = $this->post('api/v1/register', [
            'invite_token' => '010101',
            'username' => $username,
            'password' => 'test'
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorIfUsernameDoesNotExists()
    {

        $response = $this->post('api/v1/register', [
            'invite_token' => '010101',
            'username' => 'root',
            'password' => 'test'
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorIfPasswordEmpty()
    {
        $response = $this->post('api/v1/register', [
            'invite_token' => '010101',
            'username' => 'test',
            'password' => ''
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorIfPasswordMissing()
    {
        $response = $this->post('api/v1/register', [
            'invite_token' => '010101',
            'username' => 'test'
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorIfUserHasPassword()
    {
        $response = $this->post('api/v1/register', [
            'invite_token' => '010101',
            'username' => 'test-pass',
            'password' => 'test'
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorIfTokenMissing()
    {
        $response = $this->post('api/v1/register', [
            'username' => 'test',
            'password' => 'test'
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorIfTokenEmpty()
    {
        $response = $this->post('api/v1/register', [
            'invite_token' => '',
            'username' => 'test',
            'password' => 'test'
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorIfTokenDoesNotMatch()
    {
        $response = $this->post('api/v1/register', [
            'invite_token' => 'abcdef',
            'username' => 'test',
            'password' => 'test'
        ]);

        $response->assertStatus(422);
    }
}
