<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;
use function session;
use function validator;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    
    protected function signIn(?User $user = null): User {
        $user = $user ?: User::factory()->create();

        $this->actingAs($user);

        return $user;
    }

    protected function assertInvalidDataReturnsErrors(string $url, array $invalidDataExamples): void {
        foreach ($invalidDataExamples as $invalidDataExample) {
            [$fields, $value, $rule] = $invalidDataExample;

            if (!is_array($fields)) {
                $fields = [$fields];
            }

            foreach ($fields as $field) {
                $this->assertNotEmpty(
                        $expectedErrorMessage = validator(
                                $data = [$field => $value],
                                [$field => [$rule]]
                        )->messages()->first(),
                        $this->errorMessageNotFound($data, $rule)
                );
                $this->post($url, $data)->assertSessionHasErrors([
                    $field => $expectedErrorMessage
                ]);
            }
        }
    }
    
    public function assertExpectedErrorWasReceivedUsingAPattern(
            string $expectedError, 
            string $field
    ): void {
        $this->assertNotEmpty(
            $errors = session('errors')->get($field),
            "No error found in session for '$field'."
        );
        
        $this->assertTrue(
                Str::is($expectedError, $errors[0]),
                $this->wrongErrorReceived($expectedError, $errors[0])
        );
    }
    
    public function wrongErrorReceived(string $expectedError, string $errorReturned): string {
        return "Wrong error received. Expected error was '$expectedError' ".
                "but the received error is '$errorReturned'";
    }
    
    protected function errorMessageNotFound(array $data, $rule) {
        return "Unable to get an error message when the given data is " .
                "validated against the given rule. Data: " .
                json_encode($data) . ". Rule: " . json_encode($rule);
    }
}
