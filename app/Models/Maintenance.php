<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $table = 'maintenance';

    protected $fillable = [
        'tenant_id',
        'apartment_id',
        'staff_id',
        'issue_description',
        'priority',
        'status',
        'category',
        'staff_notes',
        'cost',
        'assigned_at',
        'started_at',
        'completed_at',
        'completion_notes',
        'photos',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cost' => 'decimal:2',
        'photos' => 'array',
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->whereIn('status', ['assigned', 'in_progress']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeAssignedToStaff($query, $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    // Accessors
    public function getFormattedCostAttribute()
    {
        return $this->cost ? '₱' . number_format((float) $this->cost, 2) : 'N/A';
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'assigned' => 'blue',
            'in_progress' => 'indigo',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray'
        };
    }

    public function getPriorityColorAttribute()
    {
        return match ($this->priority) {
            'low' => 'gray',
            'medium' => 'blue',
            'high' => 'orange',
            'urgent' => 'red',
            default => 'gray'
        };
    }

    public function getStatusLabelAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    public function getPriorityLabelAttribute()
    {
        return ucfirst($this->priority);
    }

    public function getResponseTimeAttribute()
    {
        if ($this->assigned_at) {
            return $this->created_at->diffInHours($this->assigned_at);
        }
        return $this->created_at->diffInHours(now());
    }

    public function getResolutionTimeAttribute()
    {
        if ($this->completed_at) {
            return $this->created_at->diffInHours($this->completed_at);
        }
        return null;
    }

    // Helper Methods
    public function assignToStaff($staffId, $notes = null)
    {
        $this->update([
            'staff_id' => $staffId,
            'status' => 'assigned',
            'assigned_at' => now(),
            'staff_notes' => $notes,
        ]);
    }

    public function startWork($notes = null)
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
            'staff_notes' => $this->staff_notes . ($notes ? "\n\nStarted: $notes" : ""),
        ]);
    }

    public function markCompleted($cost = null, $notes = null)
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'cost' => $cost,
            'completion_notes' => $notes,
        ]);
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'staff_notes' => $this->staff_notes . ($reason ? "\n\nCancelled: $reason" : ""),
        ]);
    }

    public function addPhoto($photoUrl)
    {
        $photos = $this->photos ?? [];
        $photos[] = $photoUrl;
        $this->update(['photos' => $photos]);
    }
}
