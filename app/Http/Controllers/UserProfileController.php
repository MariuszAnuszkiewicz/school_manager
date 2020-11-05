<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UpdatePictureRequest;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function show(Request $request, $id)
    {
        $authUser = auth()->user();
        $user = User::find($id);
        if ($authUser->id === $user->id) {
            foreach ($user->roles as $role) {
                if ($request->ajax()) {
                    return response()->json([
                        'user' => $user,
                        'userRole' => ucfirst($role->name),
                    ]);
                }
            }
        } else {
            return redirect('/user_profile/user/' . $authUser->id);
        }
        return view('user_profile.user_profile');
    }

    public function updateAvatar(UpdatePictureRequest $request)
    {
        $request->validated();
        $authUser = auth()->user();
        if ($request->ajax()) {
            if ($request->hasFile('picture')) {
                $fileName = $request->picture;
                $avatarName = $authUser->id . '_avatar' . time() . '.' . $fileName->getClientOriginalExtension();
                $fileName->move(public_path('/images/user/avatars'), $avatarName);
                $authUser->avatar = $avatarName;
                $authUser->save();
                $folder = public_path() . '/images/user/avatars/';
                $arr = [];
                foreach (glob($folder . "*") as $filename) {
                    $pattern = $authUser->id . '_avatar';
                    if (preg_match("/{$pattern}/", $filename)) {
                        $path[] = $filename;
                    }
                }
                asort($arr);
                for ($i = 0; $i < count($path) - 1; $i++) {
                    if (count($path) > 1) {
                        unlink($path[$i]);
                    }
                }
            }
        }
    }

    public function updateProfile(Request $request, $id)
    {
        if ($request->ajax()) {
            User::where('id', $id)->update($request->all());
        }
    }
}
