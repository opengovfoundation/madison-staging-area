<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Tests\Browser\Pages\HomePage;

use App\Models\Doc as Document;

class HomeTest extends DuskTestCase
{

    public function testFeaturedDefaultsToMostRecent()
    {
        $document = factory(Document::class)->create([
            'publish_state' => Document::PUBLISH_STATE_PUBLISHED,
        ]);

        $this->browse(function ($browser) use ($document) {
            $browser->visit(new HomePage);
            $browser->with('@mainFeatured', function ($featured) use ($document) {
                $featured->assertSee($document->title);
            });
        });
    }

    public function testFeaturedDocument()
    {
        $document = factory(Document::class)->create([
            'publish_state' => Document::PUBLISH_STATE_PUBLISHED,
        ]);
        $document->setAsFeatured();

        $recentDocument = factory(Document::class)->create([
            'publish_state' => Document::PUBLISH_STATE_PUBLISHED,
        ]);

        $this->browse(function ($browser) use ($document, $recentDocument) {
            $browser->visit(new HomePage);

            $browser->assertSeeIn('@mainFeatured', $document->title);
            $browser->assertDontSeeIn('@mainFeatured', $recentDocument->title);
            $browser->assertDontSeeIn('@featured', $recentDocument->title);
        });
    }

    public function testMultipleFeaturedDocuments()
    {
        $document = factory(Document::class)->create([
            'publish_state' => Document::PUBLISH_STATE_PUBLISHED,
        ]);
        $document->setAsFeatured();

        $secondDocument = factory(Document::class)->create([
            'publish_state' => Document::PUBLISH_STATE_PUBLISHED,
        ]);
        $secondDocument->setAsFeatured();

        $this->browse(function ($browser) use ($document, $secondDocument) {
            $browser->visit(new HomePage);

            $browser->assertSeeIn('@featured', $document->title);
            $browser->assertSeeIn('@featured', $secondDocument->title);

            // Most recently "featured" document shows up first
            $browser->assertSeeIn('@mainFeatured', $secondDocument->title);
            $browser->assertDontSeeIn('@mainFeatured', $document->title);
        });
    }
}
