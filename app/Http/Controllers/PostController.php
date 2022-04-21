<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Post as PostRessources;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;

class PostController extends  BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return $this->sendResponse(PostRessources::collection($posts) , 'all posts sent successfully');
    }

    public function userPosts($id)
    {
    $posts = Post::where('user_id' , $id)->get();
    return $this->sendResponse(PostRessources::collection($posts), 'Posts retrieved Successfully!' );
    }

    public function store(Request $request , Post $post)
    {
        $data = $request->all();
        $validator = Validator::make($data , [
            'title' => 'required',
            'content' => 'required',
        ]);
        if($validator->fails()) {
            return $this->sendError('check fields ',$validator->errors());
        }
        if($post->user_id != Auth::id()) {
            return $this->sendError('you dont have permission to create' , $validator->errors());
        }

        $user = Auth::user();
        $data['user_id'] = $user->id;
        $post = Post::create($data);
        return $this->sendResponse($post,'post created successfully');

    }

    public function show($id , Post $post)
    {
        $errorMessage = [];
        if($post->user_id != Auth::id()) {
            return $this->sendError('you dont have permission to see' , $errorMessage);
        }
        $post = Post::find($id);
        if (is_null($post)) {
            return $this->sendError($post,'Post not found!' );
        }
        return $this->sendResponse(new PostRessources($post), 'Post retireved Successfully!' );

    }


    public function update(Request $request , Post $post)
    {
        $data = $request->all();
        $validator = Validator::make($data , [
            'title' => 'required',
            'content' => 'required',
        ]);
        if($validator->fails()) {
            return $this->sendError('check fields ',$validator->errors());
        }
          if($post->user_id != Auth::id()) {
            return $this->sendError('you dont have permission to update',$validator->errors());
          }
        $post->title = $data['title'];
        $post->content = $data['content'];
        $post->save();

        return $this->sendResponse(new PostRessources($post),'post updated successfully');

    }


    public function destroy(Post $post)
    {
        $errorMessage = [];
         if($post->user_id != Auth::id()) {
             return $this->sendError('you dont have permission to delete' , $errorMessage);
         }
        $post->delete();
        return $this->sendResponse(new PostRessources($post) ,'post deleted successfully');

    }
}
