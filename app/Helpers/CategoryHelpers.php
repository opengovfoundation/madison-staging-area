<?php

namespace App\Helpers;

use App\Models\Category;

class CategoryHelpers
{
    /**
     * Returns the current URL with the provided category ID added to the
     * query string.
     */
    public static function urlPlusCategory($request, $categoryId)
    {
        $categories = static::startingCategoryArray($request->query('categories'));

        array_filter($categories);
        array_push($categories, $categoryId);

        return $request->fullUrlWithQuery([
            'categories' => join(',', $categories)
        ]);
    }


    /**
     * Returns the current URL with the provided category ID removed from the
     * query string.
     */
    public static function urlMinusCategory($request, $categoryId)
    {
        $categories = static::startingCategoryArray($request->query('categories'));

        $idIndex = array_search($categoryId, $categories);
        unset($categories[$idIndex]);

        return $request->fullUrlWithQuery([
            'categories' => count($categories) == 0 ? null : join(',', $categories)
        ]);
    }


    /**
     * Starts a category query array, ensuring it's empty if there are no
     * category ID's provided.
     */
    private static function startingCategoryArray($categoryQuery)
    {
        return $categoryQuery == '' ?  [] : explode(',', $categoryQuery);
    }
}
