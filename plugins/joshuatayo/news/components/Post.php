<?php namespace JoshuaTayo\News\Components;


use Str;
use Lang;
use Redirect;
use Carbon\Carbon;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use JoshuaTayo\News\Models\Post as PostModel;
use JoshuaTayo\News\Models\Category;
use Illuminate\Support\Facades\Request;

class Post extends ComponentBase
{
    public  $post;

    public function componentDetails()
    {
        return [
            'name'        => 'Post',
            'description' => 'Display Post'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'Slug',
                'type'        => 'string',
                'default'     => '{{ :slug }}'
            ],
        ];
    }

    public function onRun()
    {
        $post = $this->loadPost();

        $this->post = $this->page['post'] = $post;
        PostModel::find($post->id)->increment('views');
        PostModel::find($post->id)->update(['last_viewed' => Carbon::now()]);
        $this->page['url'] = Request::url();
    }

    protected function loadPost()
    {

        $slug = $this->property('slug');
        $post = PostModel::isPublished()->where('slug', $slug)->first();

        return $post;
    }
}
