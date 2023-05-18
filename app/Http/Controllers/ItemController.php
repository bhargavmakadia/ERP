<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    //
    public function index(){
        $items = Item::paginate(5);
        return view ('item.index', compact('items'));
    }

    public function form()
    {
        $item=null;
        if(request('id')){
          $item=Item::find(request('id'));
        }
        return view('item.form', compact('item'));
    }

}
