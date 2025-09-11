<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as baseLogin;
use Illuminate\Validation\ValidationException;

class LoginUser extends baseLogin
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        // $this->getRememberFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }
    protected function throwFailureValidationException(): never
    {

        throw ValidationException::withMessages([
            'data.email' => __('the email and password are incorrect'),
            'data.password' => " ",
        ]);
    }
}
