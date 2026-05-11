<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BioController extends Controller
{
    public function edit(): View
    {
        $sections = config('bio.sections');
        $user = auth()->user();

        return view('bio.edit', [
            'sections' => $sections,
            'bioData' => $user->bio_data ?? [],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $sections = config('bio.sections');
        $rules = [];

        foreach ($sections as $section) {
            foreach ($section['questions'] as $q) {
                $key = $q['key'];
                if ($q['type'] === 'integer') {
                    $rules["bio_data.{$key}"] = ['nullable', 'integer'];
                    if (isset($q['min'])) {
                        $rules["bio_data.{$key}"][] = "min:{$q['min']}";
                    }
                    if (isset($q['max'])) {
                        $rules["bio_data.{$key}"][] = "max:{$q['max']}";
                    }
                } elseif ($q['type'] === 'boolean') {
                    $rules["bio_data.{$key}"] = ['nullable', 'integer', 'in:0,1'];
                }
            }
        }

        $validated = $request->validate($rules);

        $user = $request->user();
        $user->bio_data = $validated['bio_data'] ?? [];
        $user->save();

        return redirect()->route('bio.edit')->with('success', 'Bio berhasil disimpan.');
    }
}
