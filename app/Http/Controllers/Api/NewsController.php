<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\News;

class NewsController extends Controller
{
    public function index()
    {
        return NewsResource::collection(News::all());
    }
    
    public function show(News $news)
    {
        return $news;
    }

    public function store(Request $request)
    {
        // \Log::info($request->all());
        $request->validate([
            'title' => 'required',
            'author_id' => 'required',
            'article' => 'required',
            'is_published' => 'required',
        ]);

        $news = new News([
            'title' => $request->title,
            'author_id' => $request->author_id,
            'article' => $request->article,
            'is_published' => $request->is_published,
        ]);

        $news->save();
        
        
        return response()->json([
            'message' => 'Successfully created news!'
        ], 201);
    }

    
    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required',
            'author_id' => 'required',
            'article' => 'required',
            'is_published' => 'required',
        ]);


        $newsData = ([
            'title' => $request->title,
            'author_id' => $request->author_id,
            'article' => $request->article,
            'is_published' => $request->is_published,
        ]);
        
        $news->update($newsData);

        return response()->json($news, 200);
    }

    public function destroy(News $news)
    {
        $news->delete();

        return response()->json(null, 204);
    }


}
