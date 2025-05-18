<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FooterMenu;
use App\Models\CustomCategory;
use Illuminate\Http\Request;

class FooterMenuController extends Controller
{
    public function index(){
        $menus = FooterMenu::all();
        $customCategories = CustomCategory::where('status', 1)->orderBy('name')->get();
        return view('admin.footer-menu.index', compact('menus', 'customCategories'));
    }

    public function edit($id)
{
    $menu = FooterMenu::findOrFail($id);
    return view('admin.footer-menu.edit', compact('menu'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $topMenu = FooterMenu::findOrFail($id);
    $topMenu->name = $request->input('name');
    $topMenu->save();

    return redirect()->route('admin.footer-menu.index')->with('success', 'Название меню успешно обновлено.');
}

public function changeStatus(Request $request){
        
    $meun = FooterMenu::findOrFail($request->id);
    $meun->status = $request->status == 'true' ? 1 : 0;
    $meun->save();

    return response(['message' => 'Статус был обновлен!']);
}
}
