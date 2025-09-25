<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Image;
use App\Models\Post;
use App\Models\Category;

class PostController extends Controller
{
    public function template()
    {
        $posts = Post::select('posts.*', 'images.url as image_url')
            ->join('images', 'posts.id', '=', 'images.imageable_id')
            ->where('imageable_type', Post::class)
            ->orderBy('posts.id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'posts'   => $posts
        ], 200);
    }

    public function index()
    {
        $categories = Category::all();
        $posts = Post::with('image')->get();

        return response()->json([
            'success'    => true,
            'categories' => $categories,
            'posts'      => $posts
        ], 200);
    }

    public function show(Post $post)
    {
        $similares = Post::where('category_id', $post->category_id)
            ->where('status', 2)
            ->where('id', '!=', $post->id)
            ->latest('id')
            ->take(4)
            ->get();

        return response()->json([
            'success'   => true,
            'post'      => $post->load('image'),
            'similares' => $similares
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'foto'        => 'required|image|max:40960',
            'category_id' => 'required',
            'txtTitulo'   => 'required|string|max:255',
            'txtDescripcion' => 'required|string',
        ]);

        $post = new Post();
        $post->title       = $request->txtTitulo;
        $post->slug        = Str::slug($request->txtTitulo);
        $post->body        = $request->txtDescripcion;
        $post->user_id     = Auth::id();
        $post->category_id = $request->category_id;
        $post->save();

        if ($request->hasFile('foto')) {
            $file   = $request->file('foto');
            $nombre = time() . "_" . $file->getClientOriginalName();
            $ruta   = $file->storeAs('uploads', $nombre, 'public');
            $url    = 'storage/' . $ruta;

            $image = Image::create([
                'url'            => $url,
                'imageable_id'   => $post->id,
                'imageable_type' => Post::class
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'post'    => $post->load('image')
        ], 201);
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => "Post with id $id not found"
            ], 404);
        }

        if ($post->image) {
            $path = str_replace('storage/', '', $post->image->url);
            Storage::disk('public')->delete($path);
            $post->image->delete();
        }

        $post->tags()->detach();
        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully'
        ], 200);
    }
}
