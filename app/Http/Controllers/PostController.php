<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        return PostResource::collection(Post::latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'file' => 'required|image',
            'body' => 'required',
            'category_id' => 'required'
        ]);

        $title = $request->title;
        $category_id = $request->category_id;

        // get the post id to add in slug
        if(!Post::count()) {
            $postId = 1;
        }else {
            $postId = Post::latest()->first()->id + 1;
        }

        $slug = Str::slug($title, '-') . '-' . $postId;
        $user_id = auth()->user()->id;
        $body = $request->body;

        // store the image in the public disk under postsImages folder and get the file url path
        $imageUrlPath = $request->file('file')->store('postsImages', 'public');

        $imageRootPath = 'storage/' . $imageUrlPath;

        // create the post
        $post = new Post();
        $post->title = $title;
        $post->category_id = $category_id;
        $post->slug = $slug;
        $post->user_id = $user_id;
        $post->body = $body;
        $post->imagePath = $imageRootPath;
        $post->save();

        return response([
            'successMessage' => 'The post has been deleted successfully!'
        ]);
    }

    public function show(Post $post)
    {
        return new PostResource($post);
    }

    public function update(Post $post, Request $request)
    {
        $request->validate([
            'title' => 'required',
            'file' => 'nullable|image',
            'body' => 'required',
            'category_id' => 'required',
        ]);

        $title = $request->title;
        $category_id = $request->category_id;

        $slug = Str::slug($title, '-') . '-' . $post->id;
        $body = $request->body;

        // if new image is attached in the quest
        if($request->file('file')) {
            File::delete($post->imagePath); // deletes old image
            $imageUrlPath = $request->file('file')->store('postsImages', 'public');
            $imageRootPath = 'storage/' . $imageUrlPath;
            $post->imagePath = $imageRootPath; 
        }

        // save the edit
        $post->title = $title;
        $post->category_id = $category_id;
        $post->slug = $slug;
        $post->body = $body;
        
        return $post->save();
    }
}
