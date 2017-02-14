<?php

namespace App\Traits;

use App\Models\Annotation;

trait RootAnnotatableHelpers
{
    use AnnotatableHelpers;

    public function rootAnnotatableBaseQuery()
    {
        return Annotation
            ::where('root_annotatable_type', static::ANNOTATABLE_TYPE)
            ->where('root_annotatable_id', $this->id)
            ;
    }

    public function rootAnnotationTypeBaseQuery($class)
    {
        return $this
            ->rootAnnotatableBaseQuery()
            ->where('annotation_type_type', $class)
            ;
    }

    public function allComments()
    {
        return $this
            ->rootAnnotationTypeBaseQuery(Annotation::TYPE_COMMENT)
            ;
    }

    public function allVisibleComments()
    {
        return $this
            ->rootAnnotationTypeBaseQuery(Annotation::TYPE_COMMENT)
            // Don't include comments that have a hidden annotation
            ->whereNotIn('id', function ($query) {
                $query
                    ->select('annotatable_id')
                    ->from('annotations')
                    ->where('annotation_type_type', '=', Annotation::TYPE_HIDDEN)
                    ->whereNull('deleted_at')
                    ;
            })
            // Don't include comments that are replies to hidden annotations
            ->whereNotIn('annotatable_id', function ($query) {
                $query
                    ->select('id')
                    ->from('annotations')
                    ->whereIn('id', function ($query) {
                        $query
                            ->select('annotatable_id')
                            ->from('annotations')
                            ->where('annotation_type_type', '=', Annotation::TYPE_HIDDEN)
                            ->whereNull('deleted_at')
                            ;
                    })
                    ->whereNull('deleted_at')
                    ;
            })
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
}
