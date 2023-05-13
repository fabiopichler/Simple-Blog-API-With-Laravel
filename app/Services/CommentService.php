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

use App\Dto\CommentCreationDto;
use App\Dto\CommentDto;
use App\Models\User;
use App\Models\Comment;

class CommentService
{
    public function create(CommentCreationDto $commentDto, ?User $user): CommentDto
    {
        $comment = new Comment();
        $comment->post_id = $commentDto->postId;

        if (!empty($user)) {
            $comment->user_id = $user->id;
            $comment->author_name = $user->name;
            $comment->author_email = $user->email;
        } else {
            $comment->author_name = $commentDto->authorName;
            $comment->author_email = $commentDto->authorEmail;
        }

        $comment->body = $commentDto->body;
        $comment->save();

        return new CommentDto($comment);
    }
}
