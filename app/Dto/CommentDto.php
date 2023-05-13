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

use App\Models\Comment;

class CommentDto extends Dto
{
    public readonly int $id;
    public readonly string $authorName;
    public readonly string $body;
    public readonly UserDto | null $user;
    public readonly string $createdAt;
    public readonly string $createdAtFormatted;

    public function __construct(Comment $comment)
    {
        $this->id = $comment->id;
        $this->authorName = $comment->author_name;
        $this->body = $comment->body;

        $this->user = $comment->user ? new UserDto($comment->user) : null;

        $this->createdAt = $comment->created_at;
        $this->createdAtFormatted = $comment->created_at_formatted;
    }
}
