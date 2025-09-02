<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Traits\ApiResponse;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // La mala practica porque tenemos un Model.
        // return response()->json(DB::table("posts")->get());
        return $this->ok("Todo ok, como dijo el Pibe", Post::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();

        if($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        }

        $newPost = Post::create($data);

        if(!empty($data['category_ids'])) {
            $newPost->categories()->sync($data['category_ids']);
        }

        return $this->ok("Todo melo mor", [$newPost]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result = Post::find($id);

        if($result) {
            return $this->ok("Todo ok, como dijo el Pibe", $result);
        } else {
            return $this->success("Todo mal, como NO dijo el Pibe", [], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();

        if($request->hasFile('cover_image')) {
            // Borrado (Opcional)
            if($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }

            $data['cover_image'] = $request->file('cover_image')->store('posts', 'slug');
        }

        $post->update($data);
        if(array_key_exists('category_ids', $data)) {
            $post->categories()->sync($data['category_ids'] ?? []);
        }

        return $this->o("Nuevos cambios, upa!", [$post]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete(); // Utiliza softDeletes, borrado logico, pero sigue en la db

        // return response()->noContent();
        return $this->ok("Todo ok con la eliminacion", [$post]);
    }
}
