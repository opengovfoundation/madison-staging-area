<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Annotation;
use App\Models\Doc;
use App\Models\User;

class AnnotationsTableSeeder extends Seeder
{
    public function run()
    {
        $user = User::find(1);
        $admin = User::find(2);
        $doc = Doc::find(1);
        $commentService = App::make('App\Services\Comments');

        $note1 = [
            'quote' => 'Document',
            'text' => 'Note!',
            'uri' => '/docs/example-document',
            'tags' => [],
            'comments' => [],
            'ranges' => [
                [
                    'start' => '/p[1]',
                    'end' => '/p[1]',
                    'startOffset' => 4,
                    'endOffset' => 12
                ]
            ]
        ];

        $commentService->createFromAnnotatorArray($doc, $user, $note1);

        $note2 = [
            'quote' => 'Content',
            'text' => 'Another Note!',
            'uri' => '/docs/example-document',
            'tags' => [],
            'comments' => [],
            'ranges' => [
                [
                    'start' => '/p[1]',
                    'end' => '/p[1]',
                    'startOffset' => 13,
                    'endOffset' => 20
                ]
            ]
        ];

        $commentService->createFromAnnotatorArray($doc, $user, $note2);

        $comment1 = [
            'text' => 'This is a comment'
        ];

        $comment1Result = $commentService
            ->createFromAnnotatorArray($doc, $admin, $comment1);

        $comment1Reply = [
            'text' => 'Comment reply',
        ];

        $commentTarget = Annotation::find($comment1Result['id']);
        $commentService->createFromAnnotatorArray($commentTarget, $user, $comment1Reply);

        $comment2 = [
            'text' => 'Yet another comment'
        ];

        $commentService->createFromAnnotatorArray($doc, $admin, $comment2);
    }
}
