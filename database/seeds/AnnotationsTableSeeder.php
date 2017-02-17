<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Annotation;
use App\Models\AnnotationTypes;
use App\Models\Doc;
use App\Models\User;

class AnnotationsTableSeeder extends Seeder
{
    public function run()
    {
        $regularUser = User::find(1);
        $adminUser = User::find(2);
        $users = collect([$regularUser, $adminUser]);
        $docs = Doc::all();

        foreach ($docs as $doc) {
            // All the comments
            $comments = factory(Annotation::class, rand(1, 50))->create([
                'user_id' => $users->random()->id,
            ])->each(function ($ann) use ($doc, $users) {
                $doc->annotations()->save($ann);
                $doc->allAnnotations()->save($ann);
                $comment = factory(AnnotationTypes\Comment::class)->create();
                $comment->annotation()->save($ann);
                $ann->user()->associate($users->random());
            });

            // Make some of them notes
            $notes = $comments->random(round($comments->count() / 3))->each(function ($comment) use ($doc) {
                $content = $doc->content()->first()->content;
                $contentLines = preg_split('/\\n\\n/', $content);
                $paragraphNumber = rand(1, count($contentLines));
                $endParagraphOffset = strlen($contentLines[$paragraphNumber - 1]);
                $startOffset = rand(1, $endParagraphOffset);
                $endOffset = rand($startOffset, $endParagraphOffset);

                // create range annotation
                $annotation = factory(Annotation::class)->create([
                    'user_id' => $comment->user->id,
                ]);
                $range = factory(AnnotationTypes\Range::class)->create([
                    'start' => '/p['.$paragraphNumber.']',
                    'end' => '/p['.$paragraphNumber.']',
                    'start_offset' => $startOffset,
                    'end_offset' => $endOffset,
                ]);
                $range->annotation()->save($annotation);

                // mark comment with range
                $comment->annotations()->save($annotation);
                $doc->allAnnotations()->save($annotation);
                $comment->annotation_subtype = 'note';
                $comment->save();
            });

            // Reply to some of them
            $replies = $comments->random(round($comments->count() / 5))->each(function ($comment) use ($doc, $users) {
                // create comment annotation
                $annotation = factory(Annotation::class)->create([
                    'user_id' => $users->random()->id,
                ]);
                $reply = factory(AnnotationTypes\Comment::class)->create();
                $reply->annotation()->save($annotation);

                // mark comment with reply
                $comment->annotations()->save($annotation);
                $doc->allAnnotations()->save($annotation);
                if ($comment->isNote()) {
                    $comment->annotation_subtype = 'note';
                }
                $comment->save();
            });
        }
    }
}
