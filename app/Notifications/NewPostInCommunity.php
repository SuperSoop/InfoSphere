<?php

namespace App\Notifications;

use App\Models\Article;
use App\Models\Community;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewPostInCommunity extends Notification
{
    use Queueable;

    public function __construct(
        public Article $article,
        public Community $community,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Новая статья \"{$this->article->title}\" в сообществе \"{$this->community->name}\"",
            'article_slug' => $this->article->slug,
            'community_slug' => $this->community->slug,
        ];
    }
}
