<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Post;
use Carbon\Carbon;

class PostController extends ApiController
{

    /**
     * List all news.
     *
     * @return mixed
     */
    public function index()
    {
        $posts = Post::with('author')->latest()->take(50)->get();

        return $this->respond($posts->toArray());
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

    /**
     * Return news to guest users.
     *
     * @return mixed
     */
    public function news()
    {
        if (request('id')) {
            $post = Post::with('author')
                ->where('created_at', '<=', Carbon::now())
                ->where('active', 1)
                ->find(request('id'));

            return $this->respond($post->toArray());
        }

        $post = Post::with('author')
            ->where('created_at', '<=', Carbon::now())
            ->where('active', 1)
            ->latest()
            ->first();

        return $this->respond($post->toArray());
    }

    /**
     * News List
     *
     * @return mixed
     */
    public function newsList()
    {
        $news = Post::with('author')
            ->latest()
            ->where('created_at', '<=', Carbon::now())
            ->where('active', 1)
            ->take(20)
            ->get();

        return $this->respond($news->toArray());
    }
}
