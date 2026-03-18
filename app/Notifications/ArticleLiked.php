<?php

namespace App\Notifications;

use App\Models\Article;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ArticleLiked extends Notification
{
    use Queueable;

    public function __construct(
        public User $liker,
        public Article $article,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "{$this->liker->name} оценил(а) вашу статью \"{$this->article->title}\"",
            'article_slug' => $this->article->slug,
        ];
    }
}
