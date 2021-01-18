<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UpdatePictureRequest;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function show(Request $request, $id)
    {
        $user = User::find($id);
        if (auth()->user()->id === $user->id) {
            foreach ($user->roles as $role) {
                if ($request->ajax()) {
                    return response()->json([
                        'user' => isset($user) ? $user : null,
                        'userRole' => ucfirst($role->name),
                    ]);
                }
            }
        }
        return view('user_profile.user_profile');
    }

    public function updateAvatar(UpdatePictureRequest $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('post')) {
                if ($request->hasFile('picture')) {
                    $authUser = auth()->user();
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
    }

    public function updateProfile(Request $request, $id)
    {
        if ($request->ajax()) {
            User::where('id', $id)->update($request->all());
        }
        return response()->json(['message' => 'Your personal data has been updated successfully.']);
    }
}
