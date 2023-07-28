<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the given article can be updated by the user.
     */
    public function update(User $user, Article $article): bool
    {
        return $user->id === $article->author_id;
    }

    /**
     * Determine if the given article can be deleted by the user.
     */
    public function delete(User $user, Article $article): bool
    {
        return $user->id === $article->author_id;
    }
}
