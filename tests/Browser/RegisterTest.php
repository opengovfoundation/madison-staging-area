<?php

namespace Tests\Browser;

use App\Models\User;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testUserCanRegister()
    {
        $fakeUser = factory(User::class)->make();

        Event::fake();
        // Mail::fake();

        $this->browse(function ($browser) use ($fakeUser) {
            $browser->visit('/register')
                ->type('fname', $fakeUser->fname)
                ->type('lname', $fakeUser->lname)
                ->type('email', $fakeUser->email)
                ->type('password', 'secret')
                ->type('password_confirmation', 'secret')
                ->press('Register')
                ->assertPathIs('/')
                ->assertSee('You haven\'t verified your email')
                ;

            Event::assertDispatched('Illuminate\Auth\Events\Registered');
            // Mail::assertSent(EmailVerification::class, function ($mail) use ($fakeUser) {
            //     return $mail->hasTo($fakeUser->email);
            // });

            $user = User::first();
            $this->assertEquals($fakeUser->fname, $user->fname);
            $this->assertEquals($fakeUser->lname, $user->lname);
            $this->assertEquals($fakeUser->email, $user->email);
        });
    }
}
