<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Age;
use App\Models\HairColor;
use App\Models\Height;
use App\Models\Price;
use App\Models\Size;
use App\Models\Weight;
use Illuminate\Http\Request;

class FilterManageController extends Controller
{
    // Age Methods
    public function ageIndex()
    {
        $ages = Age::all();
        return view('admin.filters.age.index', compact('ages'));
    }

    public function ageEdit($id)
    {
        $age = Age::findOrFail($id);
        return view('admin.filters.age.edit', compact('age'));
    }

    public function ageUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ages,name,' . $id,
            'title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'h1_header' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'status' => 'nullable|boolean',

        ]);

        $age = Age::findOrFail($id);
        $age->update($request->all());

        toastr()->success('Возраст успешно обновлен!');

        return redirect()->route('admin.filters.age.index');
    }


    public function ageChangeStatus(Request $request)
    {
        $age = Age::findOrFail($request->id);
        $age->status = $request->status == 'true' ? 1 : 0;
        $age->save();
        return response(['message' => 'Статус возраста успешно обновлен!']);
    }

    // HairColor Methods
    public function hairColorIndex()
    {
        $hairColors = HairColor::all();
        return view('admin.filters.hair_color.index', compact('hairColors'));
    }

    public function hairColorEdit($id)
    {
        $hairColor = HairColor::findOrFail($id);
        return view('admin.filters.hair_color.edit', compact('hairColor'));
    }

    public function hairColorUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:hair_colors,name,' . $id,
            'title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'h1_header' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'status' => 'nullable|boolean',

            // 'status' field is not in the migration for hair_colors, heights, etc.
        ]);

        $hairColor = HairColor::findOrFail($id);
        $hairColor->update($request->all());

        toastr()->success('Цвет волос успешно обновлен!');

        return redirect()->route('admin.filters.hair-color.index');
    }

    // Height Methods
    public function heightIndex()
    {
        $heights = Height::all();
        return view('admin.filters.height.index', compact('heights'));
    }

    public function heightEdit($id)
    {
        $height = Height::findOrFail($id);
        return view('admin.filters.height.edit', compact('height'));
    }

    public function heightUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:heights,name,' . $id,
            'title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'h1_header' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'status' => 'nullable|boolean',

        ]);

        $height = Height::findOrFail($id);
        $height->update($request->all());

        toastr()->success('Рост успешно обновлен!');

        return redirect()->route('admin.filters.height.index');
    }

    // Weight Methods
    public function weightIndex()
    {
        $weights = Weight::all();
        return view('admin.filters.weight.index', compact('weights'));
    }

    public function weightEdit($id)
    {
        $weight = Weight::findOrFail($id);
        return view('admin.filters.weight.edit', compact('weight'));
    }

    public function weightUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:weights,name,' . $id,
            'title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'h1_header' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ]);

        $weight = Weight::findOrFail($id);
        $weight->update($request->all());

        toastr()->success('Вес успешно обновлен!');

        return redirect()->route('admin.filters.weight.index');
    }

    // Size Methods
    public function sizeIndex()
    {
        $sizes = Size::all();
        return view('admin.filters.size.index', compact('sizes'));
    }

    public function sizeEdit($id)
    {
        $size = Size::findOrFail($id);
        return view('admin.filters.size.edit', compact('size'));
    }

    public function sizeUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name,' . $id,
            'title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'h1_header' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ]);

        $size = Size::findOrFail($id);
        $size->update($request->all());

        toastr()->success('Размер успешно обновлен!');

        return redirect()->route('admin.filters.size.index');
    }

    // Price Methods
    public function priceIndex()
    {
        $prices = Price::all();
        return view('admin.filters.price.index', compact('prices'));
    }

    public function priceEdit($id)
    {
        $price = Price::findOrFail($id);
        return view('admin.filters.price.edit', compact('price'));
    }

    public function priceUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:prices,name,' . $id,
            'value' => 'required|string|max:255|unique:prices,value,' . $id,
            'title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'h1_header' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ]);

        $price = Price::findOrFail($id);
        $price->update($request->all());

        toastr()->success('Цена успешно обновлена!');
        return redirect()->route('admin.filters.price.index');
    }

}