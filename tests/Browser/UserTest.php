<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Tests\Browser\Pages\User\Settings\AccountPage;
use Laravel\Dusk\Browser;

class UserTest extends DuskTestCase
{
    public function testAccountSettingsView()
    {
        $user = factory(User::class)->create();

        $this->browse(function ($browser) use ($user) {
            $page = new AccountPage($user);

            $browser
                ->loginAs($user)
                ->visit($page)
                ->assertInputValue('fname', $user->fname)
                ->assertInputValue('lname', $user->lname)
                ->assertInputValue('email', $user->email)
                ;
        });
    }

    public function testAccountSettingsUpdate()
    {
        $user = factory(User::class)->create();
        $fakeUser = factory(User::class)->make();

        $this->browse(function ($browser) use ($user, $fakeUser) {
            $page = new AccountPage($user);

            $browser
                ->loginAs($user)
                ->visit($page)
                ->type('fname', $fakeUser->fname)
                ->type('lname', $fakeUser->lname)
                ->type('email', $fakeUser->email)
                ->press('Submit')
                ->assertPathIs($page->url())
                ->assertVisible('.alert.alert-info')
                ->assertInputValue('fname', $fakeUser->fname)
                ->assertInputValue('lname', $fakeUser->lname)
                ->assertInputValue('email', $fakeUser->email)
                ;
        });
    }

    public function testAccountSettingsUniqueEmail()
    {
        $user = factory(User::class)->create();
        $otherUser = factory(User::class)->create();

        $this->browse(function ($browser) use ($user, $otherUser) {
            $page = new AccountPage($user);

            $browser
                ->loginAs($user)
                ->visit($page)
                ->type('email', $otherUser->email)
                ->press('Submit')
                ->assertPathIs($page->url())
                ->assertVisible('.alert.alert-danger')
                ->assertInputValue('email', $otherUser->email)
                ;
        });
    }
}
