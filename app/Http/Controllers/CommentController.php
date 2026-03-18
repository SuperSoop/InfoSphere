<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Notifications\NewCommentOnArticle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'article_id' => ['required', 'exists:articles,id'],
            'parent_id' => ['nullable', 'exists:comments,id'],
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $data['user_id'] = Auth::id();

        $comment = Comment::create($data);

        // Notify article author
        $article = Article::find($data['article_id']);
        if ($article->user_id !== Auth::id()) {
            $article->user->notify(new NewCommentOnArticle($comment, $article));
        }

        return back()->with('success', __('Добавлен комментарий.'));
    }

    public function update(Request $request, Comment $comment): RedirectResponse
    {
        $this->authorize('update', $comment);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $comment->update($data);

        return back()->with('success', __('Комментарий обновлен.'));
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return back()->with('success', __('Комментарий удален.'));
    }
}
