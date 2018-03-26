<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Videoclip;
use Illuminate\Support\Facades\Auth;

class VideoclipController extends Controller
{
    //

    public function index() {
        $user = Auth::user();
        $videoclips = $user->videoclips;

        return view('pages.videoclip.index', compact('videoclips'));
    }

    public function create(Request $request) {
        return view('pages.videoclip.create');
    }

    public function edit(Request $request, $id) {
        $user = Auth::user();
        $result = $user->videoclips;
        //$ressult = User::videoclips();
        return response()->json([
            "result" => $result,
            "message" => "update order successfully"
        ]);

        //return view('pages.edit-videoclip');
    }

    public function doCreate(Request $request) {
        $videoclip = new Videoclip();

        $this->validate($request, [
            'title'  => 'required',
            'url' => 'required'
        ]);

        $videoclip->fill($request->all());

        try{
            if($videoclip->save())
                return view('pages.create-videoclip');
            else
                return $this->response->error('could_not_create_order', 500);
        }
        catch(Exception $e){
            return $this->response->error('could_not_create_order', 500);
        }
    }
}