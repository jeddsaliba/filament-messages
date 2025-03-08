<?php

namespace Jeddsaliba\FilamentMessages\Models\Traits;

use Jeddsaliba\FilamentMessages\Models\Inbox;

trait HasFilamentMessages
{
    public function allConversations()
    {
        return Inbox::whereJsonContains('user_ids', $this->id)->orderBy('updated_at', 'desc');
    }
}
