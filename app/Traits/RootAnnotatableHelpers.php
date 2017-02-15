<?php

namespace App\Traits;

use App\Models\Annotation;

trait RootAnnotatableHelpers
{
    use AnnotatableHelpers;

    public function rootAnnotatableBaseQuery($onlyVisible = true)
    {
        $baseQuery = $onlyVisible ? Annotation::query() : Annotation::withoutGlobalScopes();

        return $baseQuery
            ->where('root_annotatable_type', static::ANNOTATABLE_TYPE)
            ->where('root_annotatable_id', $this->id)
            ;
    }

    public function rootAnnotationTypeBaseQuery($class, $onlyVisible = true)
    {
        return $this
            ->rootAnnotatableBaseQuery($onlyVisible)
            ->where('annotation_type_type', $class)
            ;
    }

    public function allComments()
    {
        return $this
            ->rootAnnotationTypeBaseQuery(Annotation::TYPE_COMMENT)
            ;
    }

    public function getAllCommentsAttribute()
    {
        return $this->allComments()->get();
    }

    public function getAllCommentsCountAttribute()
    {
        return $this->allComments()->count();
    }

    public function allCommentsWithHidden()
    {
        return $this
            ->rootAnnotationTypeBaseQuery(Annotation::TYPE_COMMENT, false)
            ;
    }

    public function getAllCommentsWithHiddenAttribute()
    {
        return $this->allCommentsWithHidden()->get();
    }
}
