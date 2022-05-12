<?php

namespace App\Orchid\Resources;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Orchid\Crud\Resource;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Sight;


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
    public function fields(): array
    {
        return [
            Input::make("title")
                ->title("Title")
                ->placeholder("Enter Post title here"),

                TextArea::make("body")
                ->title("Body")
                ->placeholder("Enter Post title here"),
            Upload::make('images')
                //->groups('photo')
                // ->maxFiles(10)
                // ->parallelUploads(2)
                // ->maxFileSize(4)
                // ->acceptedFiles('image/*')
                // ->media()

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
                if($post->featuredImage) {
                    return "<img src='{$post->featuredImage->url}' height='80' alt='{$post->featuredImage->alt}' title='{$post->featuredImage->title}' />";
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
        return ['owner', 'featuredImage'];
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

    public static function onSave($request, $model) {

        $data = $request->all();
        $data["user_id"] = $request->user()->id;
        $images = $data["images"];
        $data["featured_image_id"] = $images[0];
        unset($data["images"]);
        $model->forceFill($data)->save();
        $model->attachment()->sync($images);
    }
}
