<?php

namespace Jeddsaliba\FilamentMessages\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Jeddsaliba\FilamentMessages\Models\Inbox;

trait HasFilamentMessages
{
    /**
     * Retrieves all conversations for the current user.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function allConversations(): Builder
    {
        return Inbox::whereJsonContains('user_ids', $this->id)->orderBy('updated_at', 'desc');
    }
}
