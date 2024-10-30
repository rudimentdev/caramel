<?php

namespace App\Providers\Filament\Pages\Auth;

use Coderflex\FilamentTurnstile\Forms\Components\Turnstile;
use Filament\Pages\Auth\Login as AuthLogin;
use Illuminate\Validation\ValidationException;

class Login extends AuthLogin
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        Turnstile::make('captcha')
                            ->label('Captcha')
                            ->theme('auto'),
                        $this->getRememberFormComponent(),
                    ])
                    ->statePath('data')
            ),
        ];
    }

    /**
     * @throws ValidationException
     */
    protected function throwFailureValidationException(): never
    {
        $this->dispatch('reset-captcha');

        parent::throwFailureValidationException();
    }
}
