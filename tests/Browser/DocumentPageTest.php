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
        $this->user->attachRole(Role::adminRole());

        $this->sponsor = factory(Sponsor::class)->create();
        $this->sponsor->addMember($this->user->id, Sponsor::ROLE_OWNER);

        $this->document->sponsors()->save($this->sponsor);

        $this->note1 = FactoryHelpers::addNoteToDocument($this->user, $this->document);
        $this->note2 = FactoryHelpers::addNoteToDocument($this->user, $this->document, "New");

        $this->comment1 = FactoryHelpers::addCommentToDocument($this->user, $this->document);
        $this->comment2 = FactoryHelpers::addCommentToDocument($this->user, $this->document);
    }

    public function testCanSeeDocumentContent()
    {
        $this->browse(function ($browser) {
            $browser->visit(new DocumentPage($this->document))
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
                ->assertSeeIn('@commentsCount', '2')
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

    // TODO: test can view document comments, plus action counts, replies show by default
    public function testsViewDocumentComments()
    {
        // browse to the doc page
        $this->browse(function ($browser) {
            $browser->visit(new DocumentPage($this->document))
                ->click('@commentsTab')
                ->assertVisible('@commentsList')
                // expect to see comment1 and comment2
                // -- author, like/flag counts, permalink??, content, datetime
                ->assertSeeIn('@commentsList', $this->comment1->annotationType->content)
                ->assertSeeIn('@commentsList', $this->comment2->annotationType->content)
                ;
        });
    }

    // TODO: test can see notes bubbles, and click to see notes pane, plus note details
}
