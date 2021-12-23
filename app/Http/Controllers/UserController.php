<?php

namespace App\Http\Controllers;

use App\Models\Hobby;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *      path=":8000/user/{userId}",
     *      operationId="getUser",
     *      tags={"users"},
     *      summary="Get User By Id",
     *      description="Returns User information",
     *      @OA\Parameter(
     *         in="path",
     *         name="userId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
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
    public function show(User $user)
    {
        $hobbies = Hobby::select()
            ->where('user_id', $user->id)
            ->orderBy('updated_at', "DESC")
            ->get();
        return view(view: 'user.show')->with([
            'user' => $user,
            'hobbies' => $hobbies
        ]);
        //return [$user, $hobbies];
    }

    public function edit(User $user)
    {
        return view(view: 'user.edit')->with([
            'user' => $user,
            'message_success' => Session::get('message_success'),
            'message_warning' => Session::get('message_warning')
        ]);
    }

    public function update(Request $request, User $user){
        $request->validate([
            'motto' => 'required|min:3',
            'image' => 'mimes:jpg,jpeg,bmp,png,gif',
        ]);

        if ($request->image) {
          $this->saveImages($request->image, $user->id);
        }
        $user->update([
            'motto' => $request->motto,
            'about_me' => $request['about_me'],
        ]);
        return redirect('/home')->with([
            'message_success' => 'Your profile updated successfully.',
        ]);
    }

    public function saveImages($imagInput, $user_id)
    {
        $image = Image::make($imagInput);
        if ($image->width() > $image->height()) {
            $image->widen(500)
                ->save(public_path() . "/img/users/" . $user_id . '_large.jpg')
                ->widen(60)
                ->save(public_path() . "/img/users/" . $user_id . '_thumb.jpg');
        } else {
            $image->heighten(500)
                ->save(public_path() . "/img/users/" . $user_id . '_large.jpg')
                ->heighten(60)
                ->save(public_path() . "/img/users/" . $user_id . '_thumb.jpg');
        }
    }

    public function deleteImages($user_id)
    {
        if (file_exists(public_path() . "/img/users/" . $user_id . '_large.jpg'))
            unlink(public_path() . "/img/users/" . $user_id . '_large.jpg');
        if (file_exists(public_path() . "/img/users/" . $user_id . '_thumb.jpg'))
            unlink(public_path() . "/img/users/" . $user_id . '_thumb.jpg');

        return back()->with([
            'message_success' => 'The Image was deleted.',
        ]);
    }
}
