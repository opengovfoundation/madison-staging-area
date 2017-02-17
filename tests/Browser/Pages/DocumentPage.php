<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class DocumentPage extends BasePage
{

    public function __construct($document)
    {
        $this->document = $document;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/documents/' . $this->document->slug;
    }

    /**
     * Assert that the browser is on the page.
     *
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
        $browser->assertTitleContains($this->document->title);
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@sponsorList' => '.page-header .sponsors',
            '@stats' => '.document-stats',
            '@participantCount' => '.participants-count',
            '@commentsCount' => '.comments-count',
            '@notesCount' => '.notes-count',
            '@supportBtn' => '.support-btn button',
            '@opposeBtn' => '.oppose-btn button',
            '@contentTab' => '.nav-tabs a[href="#content"]',
            '@commentsTab' => '.nav-tabs a[href="#comments"]',
            '@noteBubble' => '.annotation-group',
            '@notesPane' => '.annotation-pane',
            '@commentsList' => '#comments.comments',
            '@likeCount' => '.activity-actions a[data-action-type="likes"] .action-count',
            '@flagCount' => '.activity-actions a[data-action-type="flags"] .action-count',
        ];
    }
}
