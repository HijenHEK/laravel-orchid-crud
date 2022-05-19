<?php

namespace App\Orchid\Screens;

use App\Models\Post;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class PostEditScreen extends Screen
{

    public $post;
    /**
     * Query data.
     * @param Post $post
     * @return array
     */
    public function query(Post $post): iterable
    {
        return [
            "post" => $post
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->post->exists ? "Edit Post" : "Create Post";
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create Post')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->post->exists),

            Button::make('Edit')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->post->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('delete')
                ->canSee($this->post->exists),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make("post.title")
                    ->title('Title')
                    ->placeholder('enter title here'),

                Quill::make("post.body")
                    ->title('Content')
                    ->placeholder('enter content here'),

                Cropper::make("post.featuredImage")
                    ->title('Featured Image')->targetId()->required(),
                Upload::make("post.images")
                    ->title("images")
                    ->media(),

            ]),
        ];
    }

    /**
     * Create Or update
     */

    public function createOrUpdate(Post $post, Request $request)
    {



        $fields = [
            "title" => $request->get('post')['title'] ,
            "body" => $request->get('post')['body'],
            "user_id" => $request->user()->id,
            "featured_image_id" => $request->get("post")['featuredImage']
        ];
        $post->fill($fields)->save();

        Alert::info('post created successfully');

        return redirect()->route('platform.posts.list');
    }


        /**
     * delete
     */

    public function delete(Post $post)
    {
        $post->delete();

        Alert::info('post deleted successfully');

        return redirect()->route('platform.posts.list');
    }


}
