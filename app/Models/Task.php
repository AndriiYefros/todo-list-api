<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'status',
        'priority',
        'title',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => TaskStatus::class,
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'parent_id' => 0,
        'status' => TaskStatus::TODO,
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

    /**
     * Set global scopes for all queries
     */
    protected static function boot(): void
    {
        parent::boot();

        self::creating(function ($model) {
            if (Auth::check()) {
                $model->user_id = Auth::id();
            }
            $model->created_at = now();
        });

        self::addGlobalScope(function (Builder $builder) {
            $builder->where('user_id', Auth::id());
        });
    }

    /**
     * Get all ids subtasks by parent task id
     *
     * @param int $taskId
     * @param bool $withParent
     * @param ?string $status
     * @return array
     */
    public function getSubTaskIds(int $taskId, bool $withParent, ?string $status = null): array
    {
        $children = [];
        if ($withParent) {
            $children[] = $taskId;
        }

        $query = $this::select('id');
        $query->where('parent_id', $taskId);
        if ($status) {
            $query->where('status', $status);
        }
        $parentTasks = $query->get();

        foreach ($parentTasks as $task) {
            $children[] = $this->getSubTaskIds($task->id, true);
        }
        return Arr::flatten($children);
    }

    /**
     * Scope a query to fulltext search
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, string $search): Builder
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
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $sortStrValues
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSorting(Builder $query, string $sortStrValues): Builder
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
