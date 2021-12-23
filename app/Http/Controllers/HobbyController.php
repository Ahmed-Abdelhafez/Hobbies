<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="Hobbies API", version="1.0")
 */

use App\Models\Hobby;
use App\Http\Requests\StoreHobbyRequest;
use App\Http\Requests\UpdateHobbyRequest;
use App\Models\Tag;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class HobbyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * @OA\Get(
     *      path=":8000/hobby",
     *      operationId="getHobbiesList",
     *      tags={"Hobbies"},
     *      summary="Get list of hobbies",
     *      description="Returns list of hobbies",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     * )
     */

    public function index()
    {
        //$hobbies = Hobby::all();
        $hobbies = Hobby::orderBy('created_at', 'DESC')->paginate(10);
        //return $hobbies;
        return view(view: 'hobby.index')->with([
            'hobbies' => $hobbies
        ]);
    }

    public function create()
    {
        return view(view: 'hobby.create');
    }
    /**
     * @OA\Post(
     *      path=":8000/hobby",
     *      operationId="CreateHobby",
     *      tags={"Hobbies"},
     *      summary="Add new Hobby",
     *      description="User Add new hobby",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  required={"name", "description"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer",
     *                 )),     
     *      ),
     * ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="This User dosen't exist.",
     *       ),   
     * )
     */

    public function store(StoreHobbyRequest $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'description' => 'required|min:7',
            'image' => 'mimes:jpg,jpeg,bmp,png,gif'
        ]);
        $hobby = new Hobby([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => auth()->id()
        ]);
        $hobby->save();
        if ($request->image) {
            $this->saveImages($request->image, $hobby->id);
        }
        return redirect('/hobby/' . $hobby->id)->with([
            'message_warning' => 'Please assign some tags now.',
        ]);
        //return $hobby;
    }

    public function show(Hobby $hobby)
    {
        $allTags = Tag::all();
        $usedTags = $hobby->tags;
        $availableTags = $allTags->diff($usedTags);

        return view('hobby.show')->with([
            'hobby' => $hobby,
            'availableTags' => $availableTags,
            'message_success' => Session::get('message_success'),
            'message_warning' => Session::get('message_warning')
        ]);
    }

    public function edit(Hobby $hobby)
    {
        return view(view: 'hobby.edit')->with([
            'hobby' => $hobby,
            'message_success' => Session::get('message_success'),
            'message_warning' => Session::get('message_warning')
        ]);
    }

    public function update(UpdateHobbyRequest $request, Hobby $hobby)
    {
        $request->validate([
            'name' => 'required|min:3',
            'description' => 'required|min:7',
            'image' => 'mimes:jpg,jpeg,bmp,png,gif',
        ]);

        if ($request->image) {
          $this->saveImages($request->image, $hobby->id);
        }
        $hobby->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return $this->index()->with([
            'message_success' => 'The hobby <b>' . $hobby->name . '</b> was updated.',
        ]);
    }

    public function destroy(Hobby $hobby)
    {
        $oldName = $hobby->name;
        $hobby->delete();
        return $this->index()->with([
            'message_success' => 'The hobby <b>' . $oldName . '</b> was deleted.',
        ]);
    }

    public function saveImages($imagInput, $hobby_id)
    {
        $image = Image::make($imagInput);
        if ($image->width() > $image->height()) {
            $image->widen(1400)
                ->save(public_path() . "/img/hobbies/" . $hobby_id . '_large.jpg')
                ->widen(60)
                ->save(public_path() . "/img/hobbies/" . $hobby_id . '_thumb.jpg');
        } else {
            $image->heighten(900)
                ->save(public_path() . "/img/hobbies/" . $hobby_id . '_large.jpg')
                ->heighten(60)
                ->save(public_path() . "/img/hobbies/" . $hobby_id . '_thumb.jpg');
        }
    }

    public function deleteImages($hobby_id)
    {
        if (file_exists(public_path() . "/img/hobbies/" . $hobby_id . '_large.jpg'))
            unlink(public_path() . "/img/hobbies/" . $hobby_id . '_large.jpg');
        if (file_exists(public_path() . "/img/hobbies/" . $hobby_id . '_thumb.jpg'))
            unlink(public_path() . "/img/hobbies/" . $hobby_id . '_thumb.jpg');

        return back()->with([
            'message_success' => 'The Image was deleted.',
        ]);
    }
}
