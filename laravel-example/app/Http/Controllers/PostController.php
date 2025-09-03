<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

// //TODO: Eliminar
// use Illuminate\Support\Facades\DB;


class PostController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        // La mala practica porque tenemos un Model
        // return response()->json(DB::table('posts')->get());
        // return $this->ok("Todo ok, como dijo el Pibe", Post::get());

        $posts = Post::with('categories')->get();
        //use App\Http\Resources\PostResource;
        return $this->success(PostResource::collection($posts));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): JsonResponse
    {

        $data = $request->validated();

        if($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('posts','public');
        }
        $newPost = Post::create($data);

        if(!empty($data['category_ids'])) {
            $newPost->categories()->sync($data['category_ids']);
        }

        return $this->success(new PostResource($newPost), "Post creado correctamente", 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $result = Post::find($id);
        if ($result) {
            return $this->success(new PostResource($result), "Todo ok, como dijo el Pibe");
        } else {
            return $this->error("Todo mal, como NO dijo el Pibe", 404, ["id" => ["No se encontro el recurso con el id: $id"]]);
    }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        Log::debug('method: @update');
        Log::debug('all:', $request->all());
        Log::debug('files:', array_keys($request->allFiles()));
        $data = $request->validated();

        if($request->hasFile('cover_image')) {
            //Borrado (Opcional)
            if($post->cover_image) {
                //use illuminate\Support\Facades\Storage;
                Storage::disk('public')->delete($post->cover_image);
            }

            $data['cover_image'] = $request->file('cover_image')->store('posts','public');
        }

        $post->update($data);
        if(array_key_exists('category_ids', $data)) {
            $post->categories()->sync($data['category_ids'] ?? []);
        }

        return $this->success(new PostResource ($post));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): JsonResource
    {
        $post->delete(); // Soft delete

        return $this->success(null, 'Post eliminado', 204);
    }

    public function restore(int $id): JsonResponse
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();
        return $this->success($post, 'Post restaurado correctamente');
    }
}