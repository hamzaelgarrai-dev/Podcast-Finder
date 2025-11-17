<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Podcast;
use Illuminate\Http\Request;

class SearchController extends Controller
{
   
    public function SearchPodcasts(Request $request){

      $query = $request->query('query'); 

    $podcasts = Podcast::query()
        ->where('titre', 'LIKE', "%{$query}%")
        ->orWhere('category', 'LIKE', "%{$query}%")
        ->orWhereHas('user', function ($q) use ($query) {
            $q->where('nom', 'LIKE', "%{$query}%");
        })->get();

        if ($podcasts->isEmpty()) {
        
            return [
                "message" => "no podcast found"
            ];
       }
        return response()->json($podcasts);
    
}

public function SearchEpisodes(Request $request){

    $query = $request->query('query');

    $episode = Episode::query()
    ->where('titre', 'LIKE', "%{$query}%")
    ->orWhere('created_at' , 'LIKE', "%{$query}%")
    ->orWhereHas('podcast' , function ($q) use ($query){
        $q->where('titre', 'LIKE', "%{$query}%");
    } )->get();

    if ($episode->isEmpty()) {
        
            return [
                "message" => "no episode found"
            ];
       }
        return response()->json($episode);

}

}
