<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Tests\Browser\Pages\DocumentPage;
use Tests\FactoryHelpers;

use App\Models\Doc as Document;
use App\Models\User;
use App\Models\Role;
use App\Models\Sponsor;

class DocumentPageTest extends DuskTestCase
{

    public function setUp()
    {
        parent::setUp();

        $this->document = factory(Document::class)->create([
            'publish_state' => Document::PUBLISH_STATE_PUBLISHED,
        ]);

        $this->user = factory(User::class)->create();

        $this->sponsor = factory(Sponsor::class)->create();
        $this->sponsor->addMember($this->user->id, Sponsor::ROLE_OWNER);

        $this->document->sponsors()->save($this->sponsor);

        $this->note1 = FactoryHelpers::addNoteToDocument($this->user, $this->document);
        $this->note2 = FactoryHelpers::addNoteToDocument($this->user, $this->document, "New");

        $this->comment1 = FactoryHelpers::createComment($this->user, $this->document);
        $this->comment2 = FactoryHelpers::createComment($this->user, $this->document);

        $this->commentReply = FactoryHelpers::createComment($this->user, $this->comment1);
    }

    public function testCanSeeDocumentContent()
    {
        $this->browse(function ($browser) {
            $browser->visit(new DocumentPage($this->document))
                ->assertTitleContains($this->document->title)
                ->assertSee($this->document->title)
                ->assertSee($this->document->content()->first()->content)
                ->assertSeeIn('@sponsorList', $this->document->sponsors()->first()->display_name);
                ;
        });
    }

    public function testCanSeeDocumentStats()
    {
        $this->browse(function ($browser) {
            $browser->visit(new DocumentPage($this->document))
                ->assertSeeIn('@participantCount', '1')
                ->assertSeeIn('@notesCount', '2')
                ->assertSeeIn('@commentsCount', '3')
                ->assertSeeIn('@supportBtn', '0')
                ->assertSeeIn('@opposeBtn', '0')
                ;
        });
    }

    public function testDiscussionHiddenHidesAllTheThings()
    {
        $this->document->update(['discussion_state' => Document::DISCUSSION_STATE_HIDDEN]);

        $this->browse(function ($browser) {
            $browser->visit(new DocumentPage($this->document))
                ->assertDontSee('@participantCount')
                ->assertDontSee('@notesCount')
                ->assertDontSee('@commentsCount')
                ->assertDontSee('@supportBtn')
                ->assertDontSee('@opposeBtn')
                ->assertDontSee('@contentTab')
                ->assertDontSee('@commentsTab')
                ->assertDontSee('@annotationGroups')
                ;
        });
    }

    public function testViewDocumentComments()
    {
        /**
         * Not testing for timestamps here because they end up off by a second or so
         */
        $this->browse(function ($browser) {
            $browser->visit(new DocumentPage($this->document))
                ->click('@commentsTab')
                ->assertVisible('@commentsList')
                ->with('.comment#' . $this->comment1->str_id, function ($commentDiv) {
                    $commentDiv->assertSee($this->comment1->annotationType->content)
                        ->assertSee($this->comment1->user->name)
                        ->assertSeeIn('@likeCount', (string) $this->comment1->likes_count)
                        ->assertSeeIn('@flagCount', (string) $this->comment1->flags_count)
                        ;

                    // Check for the comment reply
                    $commentDiv->with('.comment#' . $this->commentReply->str_id, function ($replyDiv) {
                        $replyDiv->assertSee($this->commentReply->annotationType->content)
                            ->assertSee($this->commentReply->user->name)
                            ->assertSeeIn('@likeCount', (string) $this->commentReply->likes_count)
                            ->assertSeeIn('@flagCount', (string) $this->commentReply->flags_count)
                            ;
                    });
                })
                ->with('.comment#' . $this->comment2->str_id, function ($commentDiv) {
                    $commentDiv->assertSee($this->comment2->annotationType->content)
                        ->assertSee($this->comment2->user->name)
                        ->assertSeeIn('@likeCount', (string) $this->comment2->likes_count)
                        ->assertSeeIn('@flagCount', (string) $this->comment2->flags_count)
                        ;
                })
                ;
        });
    }

    public function testViewDocumentNotes()
    {
        /**
         * Not testing for timestamps here because they end up off by a second or so
         */
        $this->browse(function ($browser) {
            $browser->visit(new DocumentPage($this->document))
                ->waitFor('@noteBubble', 3)
                ->click('@noteBubble')
                ->pause(1000) // Ensure enough time for notes pane to expand
                ->assertVisible('@notesPane')
                ->with('@notesPane', function ($notesPane) {
                    $notesPane->with('.annotation#' . $this->note1->str_id, function ($note) {
                        $note->assertSee($this->note1->annotationType->content)
                            ->assertSee($this->note1->user->name)
                            ->assertSeeIn('@likeCount', (string) $this->note1->likes_count)
                            ->assertSeeIn('@flagCount', (string) $this->note1->flags_count)
                            ;
                    });

                    $notesPane->with('.annotation#' . $this->note2->str_id, function ($note) {
                        $note->assertSee($this->note2->annotationType->content)
                            ->assertSee($this->note2->user->name)
                            ->assertSeeIn('@likeCount', (string) $this->note2->likes_count)
                            ->assertSeeIn('@flagCount', (string) $this->note2->flags_count)
                            ;
                    });
                })
                ;
        });
    }

    public function testCantViewUnpublishedDocument()
    {
        $this->document->update([
            'publish_state' => Document::PUBLISH_STATE_UNPUBLISHED,
        ]);

        $this->browse(function ($browser) {
            $browser->visit(new DocumentPage($this->document))
                // 403 status
                ->assertSee('This action is unauthorized')
                ;
        });
    }
}
