<?php

namespace Tests\Browser;

use Tests\DuskTestCase;

class DocumentPageTest extends DuskTestCase
{

    public function testExample()
    {
        $this->browse(function ($browser) {
            $browser->visit('/')
                    ->assertSee('Laravel');
        });
    }
}
