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
        $title = substr($data['title'], 0, 100);
        $date = Carbon::today()->format('d-m-Y');
        $data['slug'] = strtolower(str_slug("{$title} {$date}"));

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
     * Return news to guest users.
     *
     * @return mixed
     */
    public function news()
    {
        $take = request('take') ?: 1;
        $skip = request('skip') ?: 0;
        $posts = Post::with('author')
            ->where('created_at', '<=', Carbon::now())
            ->where('active', 1)
            ->where('hotnews', 0)
            ->latest()
            ->take($take)
            ->skip($skip)
            ->get();

        return $this->respond($posts->toArray());
    }

    /**
     * Get hotnews
     *
     * @return mixed
     */
    public function hotnews()
    {
        $posts = Post::with('author')
            ->where('created_at', '<=', Carbon::now())
            ->where('active', 1)
            ->where('hotnews', 1)
            ->latest()
            ->take(10)
            ->get();

        return $this->respond($posts->toArray());
    }

    /**
     * News List
     *
     * @return mixed
     */
    public function show()
    {
        $post = (new Post)
            ->where(function ($query) {
                if (request('slug'))
                    $query->where('slug', request('slug'));
            })
            ->latest()
            ->first();

        $next = (new Post)
            ->where('id', '>', $post->id)
            ->orderBy('id', 'asc')
            ->where('hotnews', 0)
            ->first();

        $previous = (new Post)
            ->where('id', '<', $post->id)
            ->orderBy('id', 'desc')
            ->where('hotnews', 0)
            ->first();

        return $this->respond([
            'post'     => $post,
            'next'     => $next,
            'previous' => $previous
        ]);
    }

    /**
     * Get post object.
     *
     * @param Post $post
     * @return mixed
     */
    public function showOne(Post $post)
    {
        return $this->respond($post->toArray());
    }
}
