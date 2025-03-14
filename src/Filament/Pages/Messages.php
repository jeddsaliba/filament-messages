<?php

namespace Jeddsaliba\FilamentMessages\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Jeddsaliba\FilamentMessages\Models\Inbox;

class Messages extends Page
{
    protected static string $view = 'filament-messages::filament.pages.messages';

    public ?Inbox $selectedConversation;

    /**
     * Get the slug for the messages page.
     *
     * @return string The slug with an optional ID placeholder.
     */
    public static function getSlug(): string
    {
        return config('filament-messages.slug') . '/{id?}';
    }

    /**
     * Determines whether the messages page should be registered in the navigation menu.
     *
     * Returns the value of the `filament-messages.navigation.show_in_menu` config option.
     * Defaults to `true` if the option is not set.
     *
     * @return bool
     */
    public static function shouldRegisterNavigation(): bool
    {
        return config('filament-messages.navigation.show_in_menu', true);
    }

    /**
     * Determines the navigation group for the messages page.
     *
     * Retrieves the value of the `filament-messages.navigation.navigation_group` 
     * configuration option, which defines the navigation group in the sidebar.
     * Defaults to `null` if the configuration option is not set.
     *
     * @return string|null The navigation group or null if not set.
     */
    public static function getNavigationGroup(): ?string
    {
        return __(config('filament-messages.navigation.navigation_group'));
    }

    /**
     * Get the label for the messages page navigation item.
     *
     * Retrieves the value of the `filament-messages.navigation.navigation_label` 
     * configuration option, which defines the label displayed in the sidebar.
     * Defaults to `'Messages'` if the option is not set.
     *
     * @return string The label for the messages page navigation item.
     */
    public static function getNavigationLabel(): string
    {
        return __(config('filament-messages.navigation.navigation_label'));
    }

    /**
     * Returns the color of the navigation badge.
     *
     * Defaults to the value of the `filament.badge_color` config option.
     *
     * @return string|array|null The color of the navigation badge.
     */
    public static function getNavigationBadgeColor(): string | array | null
    {
        return parent::getNavigationBadgeColor();
    }

    /**
     * Retrieves the unread message count badge for the user in the sidebar.
     *
     * If the `filament-messages.navigation.navigation_display_unread_messages_count` 
     * config option is `true`, this method returns the count of unread messages 
     * for the authenticated user. Otherwise, it returns the parent's navigation badge.
     *
     * @return string|null The unread message count or null if not displayed.
     */
    public static function getNavigationBadge(): ?string
    {
        if (config('filament-messages.navigation.navigation_display_unread_messages_count')) {
            return Inbox::whereJsonContains('user_ids', Auth::id())
            ->whereHas('messages', function ($query) {
                $query->whereJsonDoesntContain('read_by', Auth::id());
            })->get()->count();
        }

        return parent::getNavigationBadge();
    }

    /**
     * Returns the icon for the navigation item.
     *
     * Retrieves the value of the `filament-messages.navigation.navigation_icon` 
     * configuration option, which defines the icon used in the sidebar.
     * Defaults to `null` if the configuration option is not set.
     *
     * @return string|Htmlable|null The navigation icon or null if not set.
     */
    public static function getNavigationIcon(): string | Htmlable | null
    {
        return config('filament-messages.navigation.navigation_icon');
    }

    /**
     * Retrieves the navigation sort value for the messages page.
     *
     * Returns the value of the `filament-messages.navigation.navigation_sort` 
     * configuration option, which defines the navigation sort order in the sidebar.
     * Defaults to `null` if the option is not set.
     *
     * @return int|null The navigation sort value or null if not set.
     */
    public static function getNavigationSort(): ?int
    {
        return config('filament-messages.navigation.navigation_sort');
    }

    /**
     * Mount the component.
     *
     * If an ID is provided, set the `selectedConversation` property to the matching Inbox model.
     *
     * @param int|null $id The ID of the Inbox model to select.
     * @return void
     */
    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->selectedConversation = Inbox::findOrFail($id);
        }
    }

    /**
     * Gets the title of the messages page.
     *
     * Defaults to the value of the `filament-messages.navigation.navigation_label` config option.
     *
     * @return string The title of the messages page.
     */
    public function getTitle(): string
    {
        return __(config('filament-messages.navigation.navigation_label'));
    }

    /**
     * Get the maximum content width of the messages page.
     *
     * This method retrieves the maximum content width setting for the messages page
     * from the configuration file. It defaults to the value specified in the
     * `filament-messages.max_content_width` configuration option.
     *
     * @return \Filament\Support\Enums\MaxWidth|string|null The maximum content width.
     */
    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return config('filament-messages.max_content_width');
    }

    /**
     * Gets the heading of the messages page.
     *
     * @return string|Htmlable
     */
    public function getHeading(): string | Htmlable
    {
        return '';
    }
}
