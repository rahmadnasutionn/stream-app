<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::all();

        return view('admin.movies', ['movies' => $movies]);
    }

    public function create()
    {
        return view('admin.movie-create');
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');

        $request->validate([
            'title' => 'required|string',
            'small_thumbnail' => 'required|image|mimes:jpeg,jpg,png',
            'large_thumbnail' => 'required|image|mimes:jpeg,jpg,png',
            'trailer' => 'required|url',
            'movie' => 'required|url',
            'casts' => 'required|string',
            'categories' => 'required|string',
            'release_date' => 'required|string',
            'about' => 'required|string',
            'short_about' => 'required|string',
            'duration' => 'required|string',
            'features' => 'required|string',
        ]);

        $smallThumbnail = $request->small_thumbnail;
        $largeThumbnail = $request->large_thumbnail;

        $originalSmallThumbnailName = Str::random(10).
        $smallThumbnail->getClientOriginalName();

        $originalLargeThumbnailName = Str::random(10).$largeThumbnail->getClientOriginalName();

        $smallThumbnail->storeAs('public/thumbnail', $originalSmallThumbnailName);
        $largeThumbnail->storeAs('public/thumbnail', $originalLargeThumbnailName);

        $data['small_thumbnail'] = $originalSmallThumbnailName;
        $data['large_thumbnail'] = $originalLargeThumbnailName;

        Movie::create($data);

        return redirect()->route('admin.movie')->with('success', 'Movie created');

    }

    public function edit($id)
    {
        $movie = Movie::find($id);
        return view('admin.movie-edit', ["movie" => $movie]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->except('_token');

        $request->validate([
            'title' => 'required|string',
            'small_thumbnail' => 'image|mimes:jpeg,jpg,png',
            'large_thumbnail' => 'image|mimes:jpeg,jpg,png',
            'trailer' => 'required|url',
            'movie' => 'required|url',
            'casts' => 'required|string',
            'categories' => 'required|string',
            'release_date' => 'required|string',
            'about' => 'required|string',
            'short_about' => 'required|string',
            'duration' => 'required|string',
            'features' => 'required|string',
        ]);

        $movie = Movie::find($id);

        if ($request->small_thumbnail) {
        $smallThumbnail = $request->small_thumbnail;

        $originalSmallThumbnailName = Str::random(10).
        $smallThumbnail->getClientOriginalName();     
        $smallThumbnail->storeAs('public/thumbnail', $originalSmallThumbnailName);
        $data['small_thumbnail'] = $originalSmallThumbnailName;

        Storage::delete('public/thumbnail/'.$movie->small_thumbnail);
        }

        if ($request->large_thumbnail) {

        $largeThumbnail = $request->large_thumbnail;
        $originalLargeThumbnailName = Str::random(10).$largeThumbnail->getClientOriginalName();
        $largeThumbnail->storeAs('public/thumbnail', $originalLargeThumbnailName);
        $data['large_thumbnail'] = $originalLargeThumbnailName;

        Storage::delete('public/thumbnail'.$movie->large_thumbnail);
        }

        $movie->update($data);

        return redirect()->route('admin.movie')->with('success', 'Movie updated');
    }

    public function destroy($id) 
    {
        Movie::find($id)->delete();

        return redirect()->route('admin.movie')->with('success', 'Deleted success');
    }
}
