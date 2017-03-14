<?php

namespace Tests\Browser\Pages\Admin;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class FeaturedDocumentsPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return route('admin.featured-documents.index', [], false);
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@moveUpBtn' => '.up',
            '@moveUpBtnDisabled' => '.up[disabled]',
            '@moveDownBtn' => '.down',
            '@moveDownBtnDisabled' => '.down[disabled]',
            '@unfeatureBtn' => '.unfeature',
        ];
    }

    public function getDocumentRowSelector($document)
    {
       return "#document-{$document->id}";
    }

    public function onDocumentRow(Browser $browser, $document, $fn)
    {
        $browser
            ->with($this->getDocumentRowSelector($document), $fn)
            ;
    }
}
