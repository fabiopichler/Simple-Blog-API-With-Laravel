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

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

use App\Dto\PostCreationDto;

class StorePostRequest extends FormRequest
{
    private PostCreationDto $postDto;

    protected function prepareForValidation(): void
    {
        if (empty($this->title))
            return;

        if (!empty($this->postname) && is_string($this->postname))
            $postname = Str::slug($this->postname);
        else
            $postname = Str::slug($this->title);

        $this->merge([
            'postname' => $postname,
        ]);
    }

    public function rules(): array
    {
        return [
            'postname' => 'required|unique:posts|string|max:60',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ];
    }

    public function data(): PostCreationDto
    {
        if (empty($this->postDto)) {
            $validated = $this->validated();

            $this->postDto = new PostCreationDto(
                $validated['postname'],
                $validated['title'],
                $validated['body']
            );
        }

        return $this->postDto;
    }
}
