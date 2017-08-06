<?php

namespace App\Http\Controllers;

use App\Http\Models\Cameras;
use App\Http\Requests\StoreCam;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

class CamsController extends Controller
{

    public function index()
    {
        $cams = Cameras::all();
        return view('pages.addcamform', compact(['cams']));
    }

    /**
     * Show the form to create a new blog post.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $params = array_filter(Cameras::parseRequest($request));
            Cameras::create($params);
        }
        $cams = Cameras::all();
        return view('pages.addcamform', compact(['cams']));

    }

    /**
     * Store a new blog post.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(StoreCam $request)
    {

        if ($request->isMethod('post')) {
            $params = array_filter(Cameras::parseRequest($request));
            Cameras::create($params);
        }
        $cams = Cameras::all();
        return view('pages.addcamform', compact(['cams']));
//
//        if ($validator->fails()) {
//            return redirect('post/create')
//                ->withErrors($validator)
//                ->withInput();
//        }
    }
}
