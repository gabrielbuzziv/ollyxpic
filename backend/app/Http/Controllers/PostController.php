<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Post;

class PostController extends ApiController
{

    /**
     * List all news.
     *
     * @return mixed
     */
    public function index()
    {
        $news = Post::with('author')->latest()->take(50)->get();

        return $this->respond($news->toArray());
    }

    /**
     * Store in database.
     *
     * @param PostRequest $request
     * @return array
     */
    public function store(PostRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;

        $post = Post::create($data);

        return $this->respondCreated($post->toArray());
    }

    /**
     * Store in database.
     *
     * @param Post $post
     * @param PostRequest $request
     * @return array
     */
    public function update(Post $post, PostRequest $request)
    {
        $data = $request->all();

        $post->update($data);

        return $this->respond($post->toArray());
    }

    /**
     * Destroy post.
     *
     * @param Post $post
     * @return mixed
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return $this->respond(['removed' => true]);
    }

    /**
     * Show post
     *
     * @param Post $post
     * @return mixed
     */
    public function show(Post $post)
    {
        return $this->respond($post->toArray());
    }
}
