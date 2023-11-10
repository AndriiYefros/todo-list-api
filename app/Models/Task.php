<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public $timestamps = false;

    public const TODO = 'todo';
    public const DONE = 'done';

    /**
     * @var array
     */
    public static array $statusValues = [
        self::TODO,
        self::DONE,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'user_id',
        'status',
        'priority',
        'title',
        'description',
        'created_at'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'parent_id' => 0,
        'status' => self::TODO,
        'priority' => 1,
    ];

    /**
     * @var array
     */
    public array $sortColumns = [
        'priority',
        'completed_at',
        'created_at',
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function($model) {
            $model->user_id = auth()->id();
            $model->created_at = now();
        });

        self::addGlobalScope(function (Builder $builder) {
            $builder->where('user_id', auth()->id());
        });
    }

    /**
     * Scope a query to fulltext search
     */
    public function scopeSearch(Builder $query, $search = '')
    {
        $query->when($search, function ($query, $search) {
            $query->whereFullText(['title', 'description'], $search);
        }, function ($query) {
            $query->latest();
        });

        return $query;
    }

    /**
     * Scope a query to sort
     */
    public function scopeSorting(Builder $query, $sortStrValues = [])
    {
        $sortValues = array_filter(array_map('trim', explode(',', $sortStrValues)));
        if (!empty($sortValues)) {
            foreach ($sortValues as $columnWithOrder) {
                $columnWithOrderArr = array_map('trim', explode(':', $columnWithOrder));
                $column = $columnWithOrderArr[0] ?? '';
                $direction = $columnWithOrderArr[1] ?? 'asc';
                if (!in_array($column, $this->sortColumns)) {
                    continue;
                }
                if (!in_array($direction, ['asc', 'desc'])) {
                    $direction = 'asc';
                }
                $query->orderBy($column, $direction);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }
}
