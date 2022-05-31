<?php

namespace App\Orchid\Resources;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Orchid\Attachment\Models\Attachment;
use Orchid\Crud\Resource;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Exceptions\FieldRequiredAttributeException;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class PostResource extends Resource
{

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Post::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */


    private $post;

    public function __construct(Request $request)
    {
        $this->post =  (static::$model)::find($request->id);
    }
    public function fields(): array
    {

        return [
            Input::make("title")
                ->title("Title")
                ->placeholder("Enter Post title here")->required(),

            Quill::make("body")
                ->title("Body")
                ->placeholder("Enter Post title here")->required(),
            Cropper::make('featured_image')
                ->title('Featured  Image')
                ->width(500)
                ->height(300)
                ->horizontal()
                ->value('featured_image')->required(),

                Upload::make('attachment')
                //->groups('photo')
                ->maxFiles(10)
                ->parallelUploads(2)
                ->maxFileSize(0.5)
                ->acceptedFiles('image/*')
                ->media()
                ->value("attachment")->required()
            // ->value(function ($post) {dd ($post) ;return $post->attachment();})

        ];
    }

    /**
     * Get the columns displayed by the resource.
     *
     * @return TD[]
     */
    public function columns(): array
    {

        return [
            TD::make('id'),

            TD::make('title'),

            TD::make('Featured Image')->render(function ($post) {

                if ($post->featured_image) {
                    return "<img src='{$post->featured_image}' height='80'  />";
                }
            }),

            TD::make('owner')->render(function ($post) {
                return $post->owner->name;
            }),


            TD::make('created_at', 'Date of creation')
                ->render(function ($model) {
                    return $model->created_at->diffForHumans();
                }),


        ];
    }

    /**
     * Get the sights displayed by the resource.
     *
     * @return Sight[]
     */
    public function legend(): array
    {
        return [
            Sight::make('id'),
            Sight::make('title'),
            Sight::make('body')->render(function($post) {
                return $post->body;
            }),
            Sight::make('Featured Image')->render(function ($post) {

                if ($post->featured_image) {
                    return "<img src='{$post->featured_image}' />";
                }
            }),
            Sight::make('images')->render(function ($post) {
                $images = "";
                foreach ($post->attachment as $key => $image) {

                     $images .= "<img src='{$image->url}' height='200' style='margin : 5px;' alt='{$image->alt}' title='{$image->title}' />";

                }

                return "<div>" . $images . "</div>";

            }),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(): array
    {
        return [];
    }


    /**
     * Get the validation rules that apply to save/update.
     *
     * @return array
     */
    public function rules(Model $model): array
    {
        return [
            // 'slug' => [
            //     'required',
            //     Rule::unique(self::$model, 'slug')->ignore($model),
            // ],
        ];
    }

    /**
     * Get the resource should be displayed in the navigation
     *
     * @return bool
     */
    public static function displayInNavigation(): bool
    {
        return true;
    }

    /**
     * Get relationships that should be eager loaded when performing an index query.
     *
     * @return array
     */
    public function with(): array
    {
        return ['owner', "attachment"];
    }

    public function attributes(): array
    {
        return [];
    }


    /**
     * Get the number of models to return per page
     *
     * @return int
     */
    public static function perPage(): int
    {
        return 10;
    }

    /**
     * Get the permission key for the resource.
     *
     * @return string|null
     */
    public static function permission(): ?string
    {
        return "manage.posts";
    }

    /**
     * Indicates whether should check for modifications
     * between viewing and updating a resource.
     *
     * @return  bool
     */
    public static function trafficCop(): bool
    {
        return true;
    }

    /**
     * @throws \Throwable
     */

    public static function onSave($request, $model)
    {


        $data = $request->all();
        $images = $data["attachment"];
        $featured_image = $data["featured_image"];
        unset($data["attachment"]);

        $data["user_id"] = $request->user()->id;


        $model->forceFill($data)->save();

        if (isset($images[0]) && $images[0] != "undefined") {
            $model->attachment()->sync($images);
        }
    }
}
