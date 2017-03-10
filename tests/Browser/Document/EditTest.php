<?php

namespace Tests\Browser\Document;

use App\Models\Doc as Document;
use App\Models\DocContent;
use App\Models\Sponsor;
use App\Models\User;
use Tests\Browser\Pages\Document\EditPage;
use Tests\DuskTestCase;
use Tests\FactoryHelpers;

class EditTest extends DuskTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->sponsorOwner = factory(User::class)->create();
        $this->sponsor = FactoryHelpers::createActiveSponsorWithUser($this->sponsorOwner);

        $this->document = factory(Document::class)->create([
            'publish_state' => Document::PUBLISH_STATE_UNPUBLISHED,
        ]);
        $this->document->content()->save(factory(DocContent::class)->make());
        $this->document->sponsors()->save($this->sponsor);
        $this->page = new EditPage($this->document);
    }

    public function testAdminCanEditDocument()
    {
        $admin = factory(User::class)->create()->makeAdmin();
        $this->assertEditDoc($admin);
    }

    public function testSponsorOwnerCanEditDocument()
    {
        $this->assertEditDoc($this->sponsorOwner);
    }

    public function testSponsorEditorCanEditDocument()
    {
        $editor = factory(User::class)->create();
        $this->sponsor->addMember($editor->id, Sponsor::ROLE_EDITOR);
        $this->assertEditDoc($editor);
    }

    public function testSponsorStaffCannotEditDocument()
    {
        $staff = factory(User::class)->create();
        $this->sponsor->addMember($staff->id, Sponsor::ROLE_STAFF);

        $this->browse(function ($browser) use ($staff) {
            $browser
                ->loginAs($staff)
                ->visit($this->page)
                // 403 status
                ->assertSee('Whoops, looks like something went wrong')
                ;
        });
    }

    protected function assertEditDoc($user)
    {
        $this->browse(function ($browser) use ($user) {
            $browser
                ->loginAs($user)
                ->visit($this->page)
                ->type('title', 'test')
                ->type('introtext', 'Some introtext')
                ->select('publish_state', Document::PUBLISH_STATE_PUBLISHED)
                ->select('discussion_state', Document::DISCUSSION_STATE_CLOSED)
                ->click('@submitBtn')
                ->assertPathIs($this->page->url())
                ->assertVisible('.alert.alert-info')
                ->assertInputValue('title', 'test')
                ->assertInputValue('introtext', 'Some introtext')
                ->assertSelected('publish_state', Document::PUBLISH_STATE_PUBLISHED)
                ->assertSelected('discussion_state', Document::DISCUSSION_STATE_CLOSED)
                ;

            $this->document = $this->document->fresh();
            $this->assertEquals('test', $this->document->title);
            $this->assertEquals('Some introtext', $this->document->introtext);
            $this->assertEquals(Document::PUBLISH_STATE_PUBLISHED, $this->document->publish_state);
            $this->assertEquals(Document::DISCUSSION_STATE_CLOSED, $this->document->discussion_state);

            $browser
                ->click('@addPageBtn')
                ->assertPathIs($this->page->url())
                ->assertQueryStringHas('page', '2')
                ->assertVisible('.document-pages-toolbar .pagination')
                ->assertInputValue('page_content', '')
                ->type('page_content', 'Page 2 content')
                ->click('@submitBtn')
                ->assertPathIs($this->page->url())
                ->assertQueryStringHas('page', '2')
                ->assertVisible('.alert.alert-info')
                ->assertInputValue('page_content', 'Page 2 content')
                ;

            $this->assertEquals('Page 2 content', $this->document->content()->where('page', 2)->first()->content);

            $browser
                ->type('slug', 'my-document')
                ->click('@submitBtn')
                ->assertInputValue('slug', 'my-document')
                ->assertPathIs('/documents/my-document/edit')
                ;

            $this->document = $this->document->fresh();
            $this->assertEquals('my-document', $this->document->slug);
        });
    }
}
