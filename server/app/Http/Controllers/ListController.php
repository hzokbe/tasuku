<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateListRequest;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function create(CreateListRequest $request)
    {
        $id = $request->user()->id;

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'user not found'], 404);
        }

        $data = $request->validated();

        $list = new TaskList([
            'title' => $data['title'],
            'description' => $data['description'],
        ]);

        $user->lists()->save($list);

        return response()->json($list, 201);
    }

    public function getAll(Request $request)
    {
        $id = $request->user()->id;

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'user not found'], 404);
        }

        return $user->lists;
    }
}
