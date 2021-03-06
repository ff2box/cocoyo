<?php

namespace App\Models;

use App\Services\MarkDown;
use App\Traits\VisitsHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use VisitsHelper;

    /**
     * @var string
     */
    protected $table = 'articles';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'last_user_id', 'category_id', 'title', 'slug', 'page_image',
        'content', 'meta_description', 'is_draft', 'is_original', 'published_at', 'view_count',
    ];

    protected $dates = ['published_at'];

    /**
     * Get the category for the blog article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class , 'category_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get the tag for the blog article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Get the user for the blog article
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Set the title and the readable slug.
     *
     * @param string $value
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;

        $this->attributes['slug'] = translug($value);
    }

    /**
     * Set the content attribute.
     *
     * @param $value
     */
    public function setContentAttribute($value)
    {
        $data = [
            'raw' => $value,
            'html' => (new MarkDown)->convertMarkdownToHtml(emoji($value))
        ];

        $this->attributes['content'] = json_encode($data);
    }

    /**
     * @param Builder $query
     * @return $this
     */
    public function scopeValid(Builder $query)
    {
        return $query->where([
            ['published_at', '<=', date('Y-m-d H:i:s', time())],
            ['is_draft', 0]
        ]);
    }
}
