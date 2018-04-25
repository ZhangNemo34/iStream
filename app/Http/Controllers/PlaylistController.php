<?php

namespace App\Http\Controllers;

use App\Playlist;
use App\Videoclip;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PlaylistController extends Controller
{
    //
    public function index() {
        $user = Auth::user();
        $playlists = $user->playlists;

        foreach ($playlists as $playlist) {
            $weekdays = explode(',', $playlist->schedule->days);
            $months = explode(',', $playlist->schedule->months);

            foreach($weekdays as $weekday) {
                $week = Config::get('constants.weekdays')[$weekday];
            }
        }

        return view('pages.playlist.index', compact('playlists'));
    }

    public function create() {
        $user = Auth::user();
        $messages = $user->messages;
        $videoclips = $user->videoclips;

        return view('pages.playlist.create', compact(['messages', 'videoclips']));
    }

    public function store(Request $request) {
        $playlist = new Playlist();

        try {
            $this->validate($request, [
                'title'  => 'required'
            ]);
        }catch (ValidationException $e) {
            $data = $e->getResponse()->getOriginalContent();
            return response()->json([
                "result" => Config::get('constants.status.validation'),
                "data" => $data
            ]);
        }

        try {
            $playlist->fill($request->all());
            $playlist->user_id = Auth::user()->id;

            if($playlist->save()) {
                $videoclip = Videoclip::find(1);
                $playlist->videoclips()->save($videoclip);

                return response()->json([
                    "result" => Config::get('constants.status.success'),
                    "id" => $playlist->id
                ]);
            } else {
                return response()->json([
                    "result" => Config::get('constants.status.error'),
                ]);
            }
        }
        catch(Exception $e){
            return response()->json([
                "result" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }
}
