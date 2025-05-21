<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoTemplate;
use Illuminate\Http\Request;

class SeoTemplateController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $pageType
     * @return \Illuminate\Http\Response
     */
    public function edit(string $pageType = 'profile')
    {
        $template = SeoTemplate::firstOrCreate(['page_type' => $pageType]);
        return view('admin.seo_templates.edit', compact('template', 'pageType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $pageType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $pageType = 'profile')
    {
        $request->validate([
            'title_template' => 'nullable|string|max:255',
            'meta_description_template' => 'nullable|string',
            'h1_template' => 'nullable|string|max:255',
            'city_override' => 'nullable|string|max:255', // Add validation for city_override
        ]);

        $template = SeoTemplate::firstOrCreate(['page_type' => $pageType]);
        $template->update($request->only(['title_template', 'meta_description_template', 'h1_template', 'city_override'])); // Include city_override in update

        return redirect()->route('admin.seo_templates.edit', $pageType)->with('success', 'SEO-шаблоны успешно обновлены.');
    }
}