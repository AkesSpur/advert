<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TopMenu;
use App\Models\CustomCategory;
use Illuminate\Http\Request;

class TopMenuController extends Controller
{

    public function index(){
        $menus = TopMenu::all();
        $customCategories = CustomCategory::where('status', 1)->orderBy('name')->get();
        return view('admin.top-menu.index', compact('menus', 'customCategories'));
    }

    public function edit($id)
{
    $menu = TopMenu::findOrFail($id);
    return view('admin.top-menu.edit', compact('menu'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $topMenu = TopMenu::findOrFail($id);
    $topMenu->name = $request->input('name');
    $topMenu->save();

    return redirect()->route('admin.top-menu.index')->with('success', 'Название меню успешно обновлено.');
}

public function changeStatus(Request $request){
        
    $meun = TopMenu::findOrFail($request->id);
    $meun->status = $request->status == 'true' ?  1 : 0;
    $meun->save();

    return response(['message' => 'Статус был обновлен!']);
}
}
