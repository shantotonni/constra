<?php

namespace App\Http\Controllers;

use App\Http\Requests\Page\PageStoreRequest;
use App\Http\Resources\Page\PageCollection;
use App\Http\Resources\Page\PageResource;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::latest()->paginate(15);

        return new PageCollection($pages);
    }

    public function store(PageStoreRequest $request)
    {
        $input = $request->all();
        $input['slug'] = Str::slug($input['title']);
        $input['status'] = 'Y';

        Page::create($input);

        return response()->json(['message', 'Page created successfully.', 200]);
    }

    public function update(PageStoreRequest $request, Page $page)
    {

        $input = $request->all();

        $input['slug'] = Str::slug($input['title']);
        $input['status'] = $input['status'];
        $page->update($input);

        return response()->json(['message', 'Page updated successfully', 200]);
    }

    public function show($id)
    {

        $pages = Page::Where('id',$id)->first();
        return response()->json([
           'data'=>$pages
        ]);

    }

    public function destroy(Page $page)
    {
        $page->delete();
        return response()->json(['message','Page deleted successfully',200]);
    }

    public function search($query)
    {
        return new PageCollection(Page::Where('title', 'like', "%$query%")
            ->paginate(10));
    }
}
