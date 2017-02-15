<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Tests\Browser\Pages\User\Settings\AccountPage;
use Laravel\Dusk\Browser;

class UserTest extends DuskTestCase
{
    public function testAccountSettings()
    {
        $user = factory(User::class)->create();
        $otherUser = factory(User::class)->create();
        $fakeUser = factory(User::class)->make();

        $this->browse(function ($browser) use ($user, $fakeUser, $otherUser) {
            $page = new AccountPage($user);

            $browser
                ->loginAs($user)
                ->visit($page)
                ->assertInputValue('fname', $user->fname)
                ->assertInputValue('lname', $user->lname)
                ->assertInputValue('email', $user->email)
                ->type('fname', $fakeUser->fname)
                ->type('lname', $fakeUser->lname)
                ->type('email', $fakeUser->email)
                ->press('Submit')
                ->assertPathIs($page->url())
                ->assertInputValue('fname', $fakeUser->fname)
                ->assertInputValue('lname', $fakeUser->lname)
                ->assertInputValue('email', $fakeUser->email)
                ->type('email', $otherUser->email)
                ->press('Submit')
                ->assertPathIs($page->url())
                ->assertInputValue('email', $fakeUser->email)
                ;
        });
    }
}
