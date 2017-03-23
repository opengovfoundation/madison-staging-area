<?php

namespace Tests\Browser\Pages\Sponsor;

use App\Models\Sponsor;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class EditPage extends Page
{
    public $sponsor;

    public function __construct(Sponsor $sponsor)
    {
        $this->sponsor = $sponsor;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return route('sponsors.edit', [$this->sponsor], false);
    }

    /**
     * Assert that the browser is on the page.
     *
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@nameField' => 'input[name=name]',
            '@displayNameField' => 'input[name=display_name]',
            '@address1Field' => 'input[name=address1]',
            '@cityField' => 'input[name=city]',
            '@stateField' => 'input[name=state]',
            '@postalCodeField' => 'input[name=postal_code]',
            '@phoneField' => 'input[name=phone]',
            '@submitBtn' => '#content form button[type=submit]',
        ];
    }

    public function assertFormHasDataForSponsor(Browser $browser, Sponsor $sponsor)
    {
        $browser
            ->assertInputValue('name', $sponsor->name)
            ->assertInputValue('display_name', $sponsor->display_name)
            ->assertInputValue('address1', $sponsor->address1)
            ->assertInputValue('city', $sponsor->city)
            ->assertInputValue('state', $sponsor->state)
            ->assertInputValue('postal_code', $sponsor->postal_code)
            ->assertInputValue('phone', $sponsor->phone)
            ;
    }
}
