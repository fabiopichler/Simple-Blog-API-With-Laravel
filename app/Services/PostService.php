<?php

/*-------------------------------------------------------------------------------

Copyright (c) 2023 Fábio Pichler

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

-------------------------------------------------------------------------------*/

namespace App\Services;

use Illuminate\Support\Collection;

use App\Models\Post;
use App\Models\User;
use App\Dto\PostDto;
use App\Dto\PostCreationDto;

class PostService
{
    public function findPaginated()
    {
        $posts = Post::with('user')
            ->where('type', 'post')
            ->orderBy('created_at', 'DESC')
            ->paginate(5, ['*'], 'index');

        return $posts->through(PostDto::getConstructor());
    }

    public function searchPaginated(string $q)
    {
        $posts = Post::with('user')
            ->where('type', 'post')
            ->where('body', 'LIKE', '%' . $q . '%')
            ->orderBy('created_at', 'DESC')
            ->paginate(5, ['*'], 'index');

        return $posts->through(PostDto::getConstructor());
    }

    public function findByPostname(string $postname): PostDto
    {
        $post = Post::with(['user', 'comments'])
            ->where('postname', $postname)
            ->first();

        if (empty($post))
            abort(404, 'Page not found');

        return new PostDto($post, true);
    }

    public function getLastPosts(): Collection
    {
        $posts = Post::with('user')
            ->where('type', 'post')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        return $posts->map(PostDto::getConstructor());
    }

    public function create(PostCreationDto $postDto, User $user): PostDto
    {
        $post = new Post();

        $post->user_id = $user->id;
        $post->type = 'post';
        $post->status = 'publish';
        $post->postname = $postDto->postname;
        $post->title = $postDto->title;
        $post->body = $postDto->body;

        $post->save();

        return new PostDto($post);
    }
}
