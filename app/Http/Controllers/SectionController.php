<?php

namespace App\Http\Controllers;

use App\Http\Requests\Section\CreateRequest;
use App\Http\Resources\SectionResource;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SectionController extends Controller
{
    public function index()
    {
        return SectionResource::collection(Section::all());
    }

    public function create(CreateRequest $request)
    {
        $section = Section::new(
            $request->slug,
            $request->name
        );

        return new SectionResource($section);
    }

    public function get(Section $section)
    {
        return new SectionResource($section);
    }

    public function update(Section $section, Request $request)
    {
        $request->validate([
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sections', 'slug')->ignore($section->slug)
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ]
        ]);

        $section->fill([
            'slug' => $request->slug,
            'name' => $request->name
        ]);

        $section->save();

        return new SectionResource($section);
    }
}
