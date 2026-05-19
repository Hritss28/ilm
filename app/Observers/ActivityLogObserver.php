<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogObserver
{
    /**
     * Handle the "created" event.
     */
    public function created(Model $model): void
    {
        $this->logActivity($model, 'created');
    }

    /**
     * Handle the "updated" event.
     */
    public function updated(Model $model): void
    {
        $this->logActivity($model, 'updated');
    }

    /**
     * Handle the "deleted" event.
     */
    public function deleted(Model $model): void
    {
        $this->logActivity($model, 'deleted');
    }

    /**
     * Log the activity to the activity_logs table.
     */
    protected function logActivity(Model $model, string $action): void
    {
        // Only log if there's an authenticated user
        if (!Auth::check()) {
            return;
        }

        $modelName = class_basename($model);
        $description = $this->generateDescription($model, $action, $modelName);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $modelName,
            'model_id' => $model->getKey(),
            'description' => $description,
        ]);
    }

    /**
     * Generate a human-readable description for the activity.
     */
    protected function generateDescription(Model $model, string $action, string $modelName): string
    {
        $userName = Auth::user()->name ?? 'Unknown';
        $identifier = $this->getModelIdentifier($model);

        return match ($action) {
            'created' => "{$userName} membuat {$modelName} baru: {$identifier}",
            'updated' => "{$userName} memperbarui {$modelName}: {$identifier}",
            'deleted' => "{$userName} menghapus {$modelName}: {$identifier}",
            default => "{$userName} melakukan {$action} pada {$modelName}: {$identifier}",
        };
    }

    /**
     * Get a human-readable identifier for the model.
     */
    protected function getModelIdentifier(Model $model): string
    {
        // Try common identifier fields
        if (isset($model->title)) {
            return $model->title;
        }

        if (isset($model->name)) {
            return $model->name;
        }

        if (isset($model->email)) {
            return $model->email;
        }

        return (string) $model->getKey();
    }
}
