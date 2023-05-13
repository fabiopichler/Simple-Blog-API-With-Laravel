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

namespace App\Dto;

use App\Models\Post;

class PostDto extends Dto
{
    public readonly int $id;
    public readonly UserDto $user;
    public readonly array $comments;
    public readonly int $commentsCount;
    public readonly string $postname;
    public readonly string | null $type;
    public readonly string | null $status;
    public readonly string | null $commentStatus;
    public readonly string $title;
    public readonly string | null $description;
    public readonly string $body;
    public readonly string $createdAt;
    public readonly string $createdAtFormatted;
    public readonly string $updatedAt;
    public readonly string $updatedAtFormatted;

    public function __construct(Post $post, bool $withComments = false)
    {
        $this->id = $post->id;
        $this->user = new UserDto($post->user);

        if ($withComments)
            $this->comments = $post->comments->map(CommentDto::getConstructor())->toArray();
        else
            $this->comments = [];

        $this->commentsCount = $post->comments_count;
        $this->postname = $post->postname;
        $this->type = $post->type;
        $this->status = $post->status;
        $this->commentStatus = $post->comment_status;
        $this->title = $post->title;
        $this->description = $post->description;
        $this->body = $post->body;
        $this->createdAt = $post->created_at;
        $this->createdAtFormatted = $post->created_at_formatted;
        $this->updatedAt = $post->updated_at;
        $this->updatedAtFormatted = $post->updated_at_formatted;
    }
}
