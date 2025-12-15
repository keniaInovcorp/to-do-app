<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'priority',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'date',
    ];

    /**
     * Get the relationship with the User model.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the task is completed.
     *
     * @return bool True if status is 'completed', false otherwise
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the task is pending.
     *
     * @return bool True if status is 'pending', false otherwise
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the task is overdue.
     *
     * A task is overdue if:
     * - It has a due date
     * - The due date has passed
     * - The status is still 'pending'
     *
     * @return bool True if the task is overdue, false otherwise
     */
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->isPending();
    }

    /**
     * Get the priority color for display.
     *
     * Returns a color based on the task priority:
     * - 'high' => 'red'
     * - 'medium' => 'yellow'
     * - 'low' => 'blue'
     * - default => 'gray'
     *
     * @return string Priority color
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'high' => 'red',
            'medium' => 'yellow',
            'low' => 'blue',
            default => 'gray',
        };
    }

    /**
     * Get the status color for display.
     *
     * Returns a color based on the task status:
     * - 'completed' => 'green'
     * - 'pending' => 'yellow'
     *
     * @return string Status color
     */
    public function getStatusColorAttribute(): string
    {
        return $this->isCompleted() ? 'green' : 'yellow';
    }
}
