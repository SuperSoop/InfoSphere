<?php

namespace App\Notifications;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewCommentOnArticle extends Notification
{
    use Queueable;

    public function __construct(
        public Comment $comment,
        public Article $article,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "{$this->comment->user->name} прокомментировал(а) вашу статью \"{$this->article->title}\"",
            'article_slug' => $this->article->slug,
        ];
    }
}
