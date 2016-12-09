<?php

namespace App\Providers;

use App\Models\Annotation;
use App\Models\AnnotationTypes;
use App\Models\Doc;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Form;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            Annotation::ANNOTATABLE_TYPE => Annotation::class,
            Doc::ANNOTATABLE_TYPE => Doc::class,

            Annotation::TYPE_COMMENT => AnnotationTypes\Comment::class,
            Annotation::TYPE_FLAG => AnnotationTypes\Flag::class,
            Annotation::TYPE_LIKE => AnnotationTypes\Like::class,
            Annotation::TYPE_RANGE => AnnotationTypes\Range::class,
            Annotation::TYPE_SEEN => AnnotationTypes\Seen::class,
            Annotation::TYPE_TAG => AnnotationTypes\Tag::class,
        ]);

        Form::component('mInput', 'components.form.input', [
            'type',
            'name',
            'displayName',
            'value' => null,
            'attributes' => [],
            'helpText' => null,
        ]);
        Form::component('mSubmit', 'components.form.submit', [
            'text' => 'Submit',
            'displayName',
            'attributes' => [],
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
