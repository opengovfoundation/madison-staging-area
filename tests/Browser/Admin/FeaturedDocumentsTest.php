<?php

namespace Tests\Browser;

use App\Models\Doc as Document;
use Tests\Browser\AdminTestCase;
use Tests\Browser\Pages\Admin;
use Laravel\Dusk\Browser;

class FeaturedDocumentsTest extends AdminTestCase
{
    public function testEmpty()
    {
        $this->browse(function ($browser) {
            $page = new Admin\FeaturedDocumentsPage;

            $this->assertNonAdminsDenied($browser, $page);

            $browser
                ->loginAs($this->admin)
                ->visit($page)
                ;

            // TODO: what should they see
        });
    }

    public function testList()
    {
        $documents = factory(Document::class, 3)->create();

        $this->assertEquals([], Document::getFeaturedDocumentIds()->toArray());

        $featuredDocs = $documents->take(2)->each(function ($document) {
            $document->setAsFeatured();
        });
        $nonFeaturedDocs = $documents->take(-1);

        $this->assertEquals(
            $featuredDocs->pluck('id')->reverse()->values(),
            Document::getFeaturedDocumentIds()
        );

        $this->browse(function ($browser) use ($featuredDocs, $nonFeaturedDocs) {
            $page = new Admin\FeaturedDocumentsPage;

            $this->assertNonAdminsDenied($browser, $page);

            $browser
                ->loginAs($this->admin)
                ->visit($page)
                ;

            foreach ($featuredDocs as $document) {
                $browser->assertSee($document->title);
            }

            foreach ($nonFeaturedDocs as $document) {
                $browser->assertDontSee($document->title);
            }

            # Correct buttons are disabled
            $browser
                ->onDocumentRow($featuredDocs->first(), function ($row) {
                    $row
                        ->assertVisible('@moveUpBtnDisabled')
                        ->assertVisible('@moveDownBtn')
                        ->assertVisible('@unfeatureBtn')
                        ;
                })
                ->onDocumentRow($featuredDocs->last(), function ($row) {
                    $row
                        ->assertVisible('@moveDownBtnDisabled')
                        ->assertVisible('@moveUpBtn')
                        ->assertVisible('@unfeatureBtn')
                        ;
                })
                ;
        });
    }

    /**
     * @depends testList
     */
    public function testMove()
    {
        $documents = factory(Document::class, 2)
            ->create()
            ->each(function ($document) {
                $document->setAsFeatured();
            });

        $this->browse(function ($browser) use ($documents) {
            $page = new Admin\FeaturedDocumentsPage;

            $this->assertNonAdminsDenied($browser, $page);

            $browser
                ->loginAs($this->admin)
                ->visit($page)
                ;

            $browser
                ->onDocumentRow($featuredDocs->last(), function ($row) {
                    $row
                        ->click('@moveUpBtn')
                        ;
                })
                ->assertPathIs($page->url())
                ->assertVisible('.alert.alert-info')
                ->onDocumentRow($featuredDocs->last(), function ($row) {
                    $row
                        ->assertVisible('@moveUpBtnDisabled')
                        ->assertVisible('@moveDownBtn')
                        ;
                })
                ;
        });
    }

    /**
     * @depends testList
     */
    public function testRemove()
    {
        $documents = factory(Document::class, 2)
            ->create()
            ->each(function ($document) {
                $document->setAsFeatured();
            });

        $this->browse(function ($browser) use ($documents) {
            $page = new Admin\FeaturedDocumentsPage;

            $this->assertNonAdminsDenied($browser, $page);

            $browser
                ->loginAs($this->admin)
                ->visit($page)
                ;

            $unfeaturedDoc = $documents->shift();
            $browser
                ->onDocumentRow($unfeaturedDoc, function ($row) {
                    $row
                        ->click('@unfeatureBtn')
                        ;
                })
                ->assertPathIs($page->url())
                ->assertVisible('.alert.alert-info')
                ->assertDontSee($unfeaturedDoc->title)
                ;

            foreach ($documents as $document) {
                $browser->assertSee($document->title);
            }

            $this->assertEquals(
                $documents->pluck('id')->reverse()->values(),
                Document::getFeaturedDocumentIds()
            );

        });
    }
}
