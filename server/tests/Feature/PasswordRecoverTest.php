<?php

use App\Mail\RecoverPasswordMail;
use App\Services\AuthService;
use Illuminate\Support\Facades\Mail;

test('sends the recover password mail to the given email', function () {
    Mail::fake();

    (new AuthService())->sendResetPasswordLink('john_doe', 'john@example.com', 'token-123');

    Mail::assertSent(
        RecoverPasswordMail::class,
        fn(RecoverPasswordMail $mail) => $mail->hasTo('john@example.com')
    );
});

test('sends exactly one mail', function () {
    Mail::fake();

    (new AuthService())->sendResetPasswordLink('john_doe', 'john@example.com', 'token-123');

    Mail::assertSent(RecoverPasswordMail::class, 1);
});

test('does not send mail to other recipients', function () {
    Mail::fake();

    (new AuthService())->sendResetPasswordLink('john_doe', 'john@example.com', 'token-123');

    Mail::assertNotSent(
        RecoverPasswordMail::class,
        fn(RecoverPasswordMail $mail) => $mail->hasTo('other@example.com')
    );
});

test('mail contains the token and username', function () {
    Mail::fake();

    (new AuthService())->sendResetPasswordLink('john_doe', 'john@example.com', 'token-123');

    Mail::assertSent(RecoverPasswordMail::class, function (RecoverPasswordMail $mail) {
        $mail->assertSeeInHtml('token-123');

        $mail->assertSeeInHtml('john_doe');

        return true;
    });
});
