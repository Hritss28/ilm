<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;

class NewsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, News $news): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, News $news): bool
    {
        if ($user->isAdmin() || $user->isRedaktur()) {
            return true;
        }

        // Author can only update their own news
        return $user->id === $news->author_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, News $news): bool
    {
        if ($user->isAdmin() || $user->isRedaktur()) {
            return true;
        }

        // Author can only delete their own news
        return $user->id === $news->author_id;
    }

    /**
     * Determine whether the user can publish/unpublish the model.
     */
    public function publish(User $user): bool
    {
        return $user->isAdmin() || $user->isRedaktur();
    }

    /**
     * Determine whether the user can toggle breaking news.
     */
    public function toggleBreaking(User $user): bool
    {
        return $user->isAdmin() || $user->isRedaktur();
    }

    /**
     * Determine whether the user can toggle featured status.
     */
    public function toggleFeatured(User $user): bool
    {
        return $user->isAdmin() || $user->isRedaktur();
    }
}
