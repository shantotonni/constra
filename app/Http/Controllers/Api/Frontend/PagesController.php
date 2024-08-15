<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function getPages(Request $request){
        $slug = $request->slug;
        $pages = Page::where('slug',$slug)->first();
        return response()->json([
           'pages' => $pages
        ]);
    }
}
