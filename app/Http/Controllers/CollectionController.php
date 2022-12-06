<?php

namespace App\Http\Controllers;

use App\Http\Resources\all_collection_resource;
use App\Http\Resources\File_collection_resource;
use App\Http\Resources\user_collection;
use App\Models\Collection;
use App\Http\Requests\StoreCollectionRequest;
use App\Http\Requests\UpdateCollectionRequest;
use App\Models\CollectionFile;
use App\Models\File;
use App\Models\FileStatus;
use App\Models\User;
use App\Models\UserCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\While_;
use function Sodium\add;

class CollectionController extends Controller
{

    public function __construct()
    {
        $this->middleware(["auth:sanctum"])->only(["add_user_to_collection", "delete_user_from_collection", "destroy", "add_file_to_collection", "delete_file_from_collection"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreCollectionRequest $request
     */
    public function store(StoreCollectionRequest $request)
    {

        $collection = Collection::create([
            'name' => $request->name,
            'status' => $request->status
        ]);

        if ($collection) {
            $user_collection = UserCollection::create([
                'collection_id' => $collection->id,
                'user_id' => Auth::id(),
                'property' => "owner"
            ]);
            return response()->json($collection, 200);

        } else {
            return response()->json("erorr", 201);
        }
    }

    public function add_file_to_collection($request)
    {
        $collection_file = CollectionFile::create([
            'collection_id' => $request->collection_id,
            'file_id' => $request->file_id,
        ]);

        if ($collection_file) {
            return response()->json($collection_file, 200);

        } else {
            return response()->json("erorr", 201);
        }

    }

    public function delete_file_from_collection($request)
    {

        $collection_file = CollectionFile::where('collection_id', '=', $request->collection_id)->where('file_id', '=', $request->file_id)->first()->delete();
        if ($collection_file) {
            return response()->json($collection_file, 200);

        } else {
            return response()->json("erorr", 201);
        }


    }

    public function add_user_to_collection(Request $request)
    {

        $has_user = UserCollection::where('collection_id', '=', $request->collection_id)->where('user_id', '=', $request->user_id)->first();
        if (!$has_user) {

            $user_collection = UserCollection::create([
                'collection_id' => $request->collection_id,
                'user_id' => $request->user_id,
                'property' => 'user'
            ]);

            if ($user_collection) {
                return response()->json($user_collection, 200);

            } else {
                return response()->json("erorr", 201);
            }
        }

    }

    public function delete_user_from_collection(Request $request)
    {

        $user_collection = UserCollection::where('collection_id', '=', $request->collection_id)->where('user_id', '=', $request->user_id)->first();
            $a = 0;
            $files = File::whereIn('id', CollectionFile::where('collection_id',$request->collection_id)->get('file_id'))->get();


            foreach ($files as $item) {
                if ("محجوز" == FileStatus::where('id', $item->status_id)->value('status')) {

                    if ($item->user_id == $request->user_id) {
                        $a = 1;
                        break;
                    }

                }
            }

            if ($a == 0) {
                $user_collection1 = UserCollection::where('id', '=', $user_collection->id)->delete();
                if ($user_collection1) {
                    return "yse";

                } else {
                    return "no";
                }
            }



    }

    public function destroy(Request $request)
    {

        $a = 0;
        $collection_file = CollectionFile::where('collection_id', $request->collection_id)->get('file_id');
        $files = File::whereIn('id', $collection_file)->get();
        if ($files) {
            foreach ($files as $item) {
                if ("محجوز" == FileStatus::where('id', $item->status_id)->value('status')) {
                    $a = 1;
                    break;

                }
            }
        }

        if ($a == 0) {
            Collection::where('id', '=', $request->collection_id)->first()->delete();
            return "yes";
        }
        return "no";

    }

    public function show_my_collection()
    {
        $user_collection = UserCollection::where('user_id', Auth::id())->where('property', 'owner')->get();

        return response()->json(user_collection::collection($user_collection), 200);
    }

    public function show_all_collection()
    {

        $user_collection = UserCollection::where('user_id', Auth::id())->where('property', 'user')->get();

        $all_collection=all_collection_resource::make($user_collection);


        return response()->json($all_collection, 200);
    }

    public function show_my_collection_file($collection_id)
    {

        $my_collection_file = CollectionFile::where('collection_id', $collection_id)->get();
        return response()->json(File_collection_resource::collection($my_collection_file), 200);
    }

    public function show_all_users_not_in_collection($collection_id)
    {
        $user_collection = User::whereIn('id', UserCollection::where('collection_id', $collection_id)->get('user_id'))->get('id');
        $users = User::whereNotIn('id', $user_collection)->get();
        return $users;
    }

    public function show_all_users_in_collection($collection_id)
    {
        $user_collection = User::whereIn('id', UserCollection::where('collection_id', $collection_id)->where('property','user')->get('user_id'))->get();
        return $user_collection;
    }

    public function all_file_not_in_collection($collection_id){
        $files=File::whereNotIn('id',CollectionFile::where('collection_id',$collection_id)->get('file_id'))->get();
        return $files;
    }

    public function all_file_in_collection($collection_id){
        $files=File::whereIn('id',CollectionFile::where('collection_id',$collection_id)->get('file_id'))->get();
        return $files;
    }


}
