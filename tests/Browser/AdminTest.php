<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Tests\Browser\Pages\Admin;
use Laravel\Dusk\Browser;

class AdminTest extends DuskTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->admin = factory(User::class)->create();
        $this->admin->makeAdmin();

        $this->user = factory(User::class)->create();
    }

    public function testSiteSettings()
    {
        $this->browse(function ($browser) {
            $page = new Admin\SiteSettingsPage;

            $this->assertNonAdminsDenied($browser, $page);

            $browser
                ->loginAs($this->admin)
                ->visit($page)
                ;

            // Google Analytics
            $browser
                ->assertInputValue('madison.google_analytics_property_id', '')
                ->assertSourceMissing('window.ga')
                ->type('madison.google_analytics_property_id', 'UA-123-456')
                ->click('@submitBtn')
                ->assertSourceHas('window.ga')
                ->assertSourceHas("ga('create', 'UA-123-456', 'auto');")
                ->clear('madison.google_analytics_property_id')
                ->click('@submitBtn')
                ->assertSourceMissing('window.ga')
                ;
        });
    }

    public function assertNonAdminsDenied($browser, $page)
    {
        // anonymous user
        $browser
            ->visit($page)
            // 403 status
            ->assertSee('Whoops, looks like something went wrong')
            ;

        // non-admin
        $browser
            ->loginAs($this->user)
            ->visit($page)
            // 403 status
            ->assertSee('Whoops, looks like something went wrong')
            ;
    }
}
