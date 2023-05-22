<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function create_news(Request $request) {
        $incoming_fields = $request->validate([
            'title' => ['required'],
            'body' => ['required']
        ]);

        $incoming_fields['author'] = auth()->user()->first_name . ' ' . auth()->user()->last_name;

        $news = News::create($incoming_fields);

        return back()->with('success', 'News Article Created Successfully!');
    }

    public function home_news() {
        $news = DB::table('news')->latest('created_at')->first();

        return $news;
    }

    public function news_page() {
        $news = DB::table('news')->latest()->paginate(3);
        return view('news_page', ['news' => $news]);
    }
}
