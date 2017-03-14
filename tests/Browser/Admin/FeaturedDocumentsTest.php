<?php

namespace Tests\Browser;

use App\Models\Doc as Document;
use Tests\Browser\AdminTestCase;
use Tests\Browser\Pages\Admin;
use Laravel\Dusk\Browser;

class FeaturedDocumentsTest extends AdminTestCase
{
    public function testAdminPage()
    {
        $documents = factory(Document::class, 3)->create();

        foreach ($documents->take(2) as $document) {
            $document->setAsFeatured();
        }

        $this->browse(function ($browser) {
            $page = new Admin\FeaturedDocumentsPage;

            $this->assertNonAdminsDenied($browser, $page);

            $browser
                ->loginAs($this->admin)
                ->visit($page)
                ;
        });
    }
}
