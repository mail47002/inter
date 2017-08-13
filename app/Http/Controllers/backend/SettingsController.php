<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
use App\Services\SettingsService;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function edit()
    {
        $result = array();
        $settings = Setting::all();
        if($settings) {
            foreach ($settings as $value) {
               $result[$value->key] = $value->value;
            }
        }

        return view('backend.settings.edit',
            [
            'settings' => $result,
            ]);
    }

    public function update(Request $request)
    {
        $settings = $request->settings;

        if ($settings) {
            foreach ($settings as $key => $value) {
                Setting::updateOrCreate(
                    ['key'      => $key],
                    ['value'    => $value]
                );
            }

            SettingsService::makeSettings();

            return redirect()
                ->back()
                ->withStatus(trans('settings.update'));
        }

        return redirect()->back();
    }
}
