<?php

namespace App\Http\Controllers;

use App\Http\Models\Cameras;
use App\Http\Requests\StoreCam;
use App\Http\Requests\UpdateCams;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CamsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cams = Cameras::all();
        return view('pages.cam.index', compact(['cams']));
    }

    public function show($id)
    {
        $cam = Cameras::find($id);
        return view('pages.cam.show', compact(['cam']));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function create(Request $request)
    {
        return view('pages.cam.create');
    }

    /**
     * @param StoreCam $request
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function store(StoreCam $request)
    {

        if ($request->isMethod('post')) {
            $params = array_filter(Cameras::parseRequest($request));
            try {
                $cam = Cameras::create($params);
                Cameras::makePathForCam($cam->realpath);

            } catch (\Exception $exception) {
                if (isset($cam) && $cam) {
                    Cameras::destroy($cam->id);
                }
                Log::alert("Failed to create Cam  " . $exception->getMessage());
                return Redirect::to('cam');
            }
        }
        $cams = Cameras::all();
        return view('pages.cam.index', compact(['cams']));
    }


    public function destroy($id)
    {
        try {
            $camName = Cameras::find($id)->realpath;
            Cameras::destroy($id);
            $res = Cameras::destroyCamFolder($camName);
            if (!$res) throw new \Exception('Problems in moving directory to archive folder');
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage());
            return response()->json([
                'error' => "The record wasn't deleted",
                'exception' => $exception->getMessage()
            ], 500);
        }
        $cams = Cameras::all();
        if (!Cameras::find($id)) {
            return Redirect::to('create');
        }


    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function edit($id)
    {
        $cam = Cameras::find($id);
        return view('pages.cam.edit', compact(['cam']));
    }

    public function update($id, UpdateCams $request)
    {
        $cam = Cameras::find($id);
        if ($cam) {
            $params = array_filter(Cameras::parseRequest($request));
            if ($params['realpath'] && ($cam->realpath != $params['realpath'])) {
                Cameras::renameCamsFolder($cam->realpath, $params['realpath']);
            }
            $cam->update($params);
        }
        return Redirect::to('cam');
    }
}
