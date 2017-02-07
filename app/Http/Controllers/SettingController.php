<?php

namespace App\Http\Controllers;

use App\Config\Models\Config as ConfigModel;
use App\Models\Doc as Document;
use App\Models\Setting;
use App\Http\Requests\Setting as Requests;
use SiteConfigSaver;

class SettingController extends Controller
{

    /**
     * Admin page for configuring site settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function siteSettingsIndex(Requests\SiteSettings\Index $request)
    {
        $dbSettings = SiteConfigSaver::get();

        $currentSettings = new \stdClass();
        $currentSettings->date_format = isset($dbSettings['madison.date_format']) ? $dbSettings['madison.date_format'] : 'default';
        $currentSettings->time_format = isset($dbSettings['madison.time_format']) ? $dbSettings['madison.time_format'] : 'default';

        $dateFormats = static::addDefaultOption(
            static::validDateFormats(),
            config('madison.date_format')
        );
        $timeFormats = static::addDefaultOption(
            static::validTimeFormats(),
            config('madison.time_format')
        );


        return view('settings.site-settings', compact([
            'currentSettings',
            'dateFormats',
            'timeFormats',
        ]));
    }

    public function siteSettingsUpdate(Requests\SiteSettings\Update $request)
    {
        $settingsToCheck = [
            'date_format',
            'time_format',
        ];

        $group = 'madison';

        foreach ($settingsToCheck as $key) {
            $input = $request->input($key);
            $existingModel = ConfigModel
                ::where('group', $group)
                ->where('key', $key);

            if ((!$input || $input === 'default') && $existingModel) {
                $existingModel->delete();
                SiteConfigSaver::refresh();
            } else {
                SiteConfigSaver::set($group.'.'.$key, $input);
            }
        }

        flash(trans('messages.updated'));
        return redirect()->route('settings.site.index');
    }

    public static function addDefaultOption($choices, $current)
    {
        $value = '';
        if (!isset($choices[$current])) {
            $value = 'Unknown';
        } else {
            $value = 'Default ('.$choices[$current].')';
        }
        return ['default' => $value]+$choices;
    }

    public static function validDateFormats()
    {
        return [
            'Y-m-d' => 'ISO 8601: 2009-06-27',
            'n/j/Y' => 'US: 06/27/2009',
            'd-m-Y' => 'Europe: 27-06-2009',
        ];
    }

    public static function validTimeFormats()
    {
        return [
            'g:i A' => '12 Hour, 1:15 PM',
            'H:i' => '24 Hour, 13:15',
        ];
    }

    /**
     * Show a list of featured documents so an admin can manage them.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexFeaturedDocuments(Requests\FeaturedDocuments\Index $request)
    {
        $documents = Document::getFeatured(false);
        return view('settings.featured-documents', compact([
            'documents'
        ]));
    }

    /**
     * Updates the position of a featured document.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateFeaturedDocuments(Requests\FeaturedDocuments\Update $request, Document $document)
    {
        $action = $request->input('action');

        $featuredSetting = Setting::where('meta_key', '=', 'featured-doc')->first();
        $featuredIds = explode(',', $featuredSetting->meta_value);

        $currentPos = array_search((string) $document->id, $featuredIds);

        if ($currentPos === false) {
            throw new \Exception('Invalid Document ID');
        }

        if ($currentPos === 0 && $action === 'up' || $currentPos === count($featuredIds) - 1 && $action === 'down') {
            throw new \Exception('Invalid move');
        }

        # Create a copy of the original array, to avoid errors from reference
        # assignments as opposed to value assignments.
        $idReferences = array_flip(array_flip($featuredIds));

        if ($action === "up") {
            $featuredIds[$currentPos] = $idReferences[$currentPos - 1];
            $featuredIds[$currentPos - 1] = (string) $document->id;
        } else if ($action === "down") {
            $featuredIds[$currentPos] = $idReferences[$currentPos + 1];
            $featuredIds[$currentPos + 1] = (string) $document->id;
        } else if ($action === "remove") {
            unset($featuredIds[$currentPos]);
        }

        $featuredSetting->meta_value = join(',', $featuredIds);
        $featuredSetting->save();

        flash(trans('messages.setting.updated_featured_documents'));
        return redirect()->route('setings.featured-documents.index');
    }

}
