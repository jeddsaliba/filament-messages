<?php

namespace Jeddsaliba\FilamentMessages\Livewire\Messages;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Jeddsaliba\FilamentMessages\Enums\MediaCollectionType;
use Jeddsaliba\FilamentMessages\Livewire\Traits\CanMarkAsRead;
use Jeddsaliba\FilamentMessages\Livewire\Traits\CanValidateFiles;
use Jeddsaliba\FilamentMessages\Livewire\Traits\HasPollInterval;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Messages extends Component implements HasForms
{
    use CanMarkAsRead, CanValidateFiles, HasPollInterval, InteractsWithForms, WithPagination;

    public $selectedConversation;

    public $currentPage = 1;

    public Collection $conversationMessages;

    public ?array $data = [];

    public bool $showUpload = false;

    /**
     * Initialize the Messages component.
     *
     * This method is called when the component is mounted.
     * It sets the polling interval, fills the form state, and 
     * if a conversation is selected, initializes the conversation
     * messages, loads existing messages, and marks them as read.
     * 
     * @return void
     */
    public function mount(): void
    {
        $this->setPollInterval();
        $this->form->fill();
        if ($this->selectedConversation) {
            $this->conversationMessages = collect();
            $this->loadMessages();
            $this->markAsRead();
        }
    }

    /**
     * Poll for new messages in the selected conversation.
     *
     * This method retrieves messages that are newer than the
     * latest message currently loaded in the conversation.
     * If new messages are found, they are prepended to the
     * existing collection of conversation messages.
     *
     * @return void
     */
    public function pollMessages(): void
    {
        $latestId = $this->conversationMessages->pluck('id')->first();
        $polledMessages = $this->selectedConversation->messages()->where('id', '>', $latestId)->latest()->get();
        if ($polledMessages->isNotEmpty()) {
            $this->conversationMessages = collect([
                ...$polledMessages,
                ...$this->conversationMessages
            ]);
        }
    }

    /**
     * Load the next page of messages for the selected conversation.
     *
     * This method appends the messages from the next page to the
     * existing collection of conversation messages and increments
     * the current page number.
     *
     * @return void
     */
    public function loadMessages(): void
    {
        $this->conversationMessages->push(...$this->paginator->getCollection());
        $this->currentPage = $this->currentPage + 1;
    }

    /**
     * Customize the form schema for the Messages component.
     *
     * This method defines the form schema used by the Messages component,
     * which includes support for file uploads and a message textarea.
     * The form state is stored in the 'data' property.
     *
     * - The 'attachments' field allows multiple file uploads and is
     *   conditionally visible based on the 'showUpload' property.
     * - The 'show_hide_upload' action toggles the visibility of the
     *   attachments upload field.
     * - The 'message' field is a textarea that supports live updates
     *   and automatically adjusts its height based on the content.
     *
     * @param \Filament\Forms\Form $form The form instance.
     * @return \Filament\Forms\Form The customized form instance.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\SpatieMediaLibraryFileUpload::make('attachments')
                    ->hiddenLabel()
                    ->collection(MediaCollectionType::FILAMENT_MESSAGES->value)
                    ->multiple()
                    ->panelLayout('grid')
                    ->visible(fn () => $this->showUpload)
                    ->maxFiles(config('filament-messages.attachments.max_files'))
                    ->minFiles(config('filament-messages.attachments.min_files'))
                    ->maxSize(config('filament-messages.attachments.max_file_size'))
                    ->minSize(config('filament-messages.attachments.min_file_size'))
                    ->live(),
                Forms\Components\Split::make([
                    Forms\Components\Actions::make([
                        Forms\Components\Actions\Action::make('show_hide_upload')
                            ->hiddenLabel()
                            ->icon('heroicon-o-paper-clip')
                            ->color('gray')
                            ->tooltip(__('Attach Files'))
                            ->action(fn () => $this->showUpload = !$this->showUpload),
                    ])->grow(false),
                    Forms\Components\Textarea::make('message')
                        ->live()
                        ->hiddenLabel()
                        ->rows(1)
                        ->autosize(),
                ])->verticallyAlignEnd(),
            ])->statePath('data');
    }

    /**
     * Sends a message with attachments in the selected conversation.
     *
     * This method retrieves the form state, including message content and attachments,
     * and saves the message to the database within a transaction. The message is then
     * prepended to the conversation messages collection. Attachments are processed and
     * added to the media collection. The form is reset, the conversation's updated
     * timestamp is refreshed, and the inbox is refreshed. If an exception occurs, a
     * notification is sent to inform the user of the error.
     *
     * @return void
     * @throws \Exception
     */
    public function sendMessage(): void
    {
        $data = $this->form->getState();
        $rawData = $this->form->getRawState();

        try {
            DB::transaction(function () use ($data, $rawData) {
                $this->showUpload = false;

                $newMessage = $this->selectedConversation->messages()->create([
                    'message' => $data['message'] ?? null,
                    'user_id' => Auth::id(),
                    'read_by' => [Auth::id()],
                    'read_at' => [now()],
                    'notified' => [Auth::id()],
                ]);

                $this->conversationMessages->prepend($newMessage);
                collect($rawData['attachments'])->each(function ($attachment) use ($newMessage) {
                    $newMessage->addMedia($attachment)->usingFileName(Str::slug(config('filament-messages.slug'), '_') . '_' . Str::random(20) .'.'.$attachment->extension())->toMediaCollection(MediaCollectionType::FILAMENT_MESSAGES->value);
                });

                $this->form->fill();

                $this->selectedConversation->updated_at = now();

                $this->selectedConversation->save();

                $this->dispatch('refresh-inbox');
            });
        } catch (\Exception $exception) {
            Notification::make()
                ->title(__('Something went wrong'))
                ->body($exception->getMessage())
                ->danger()
                ->persistent()
                ->send();
        }
    }

    /**
     * Computes the paginator for the conversation messages.
     *
     * This method retrieves the latest messages for the selected conversation 
     * and paginates them by 10 messages per page. The pagination starts at
     * the current page index.
     *
     * @return \Illuminate\Contracts\Pagination\Paginator The paginator instance 
     * for the conversation messages.
     */
    #[Computed()]
    public function paginator(): \Illuminate\Contracts\Pagination\Paginator
    {
        return $this->selectedConversation->messages()->latest()->paginate(10, ['*'], 'page', $this->currentPage);
    }

    /**
     * Download an attachment from the given file path and return it as a response.
     *
     * @param string $filePath The file path of the attachment to download.
     * @param string $fileName The file name to send with the attachment.
     * @return \Illuminate\Http\Response The response containing the attachment.
     */
    public function downloadAttachment(string $filePath, string $fileName)
    {
        return response()->download($filePath, $fileName);
    }

    /**
     * Determines if the message input is valid.
     *
     * @return bool
     */
    public function validateMessage(): bool
    {
        $rawData = $this->form->getRawState();
        if (empty($rawData['attachments']) && !$rawData['message']) {
            return true;
        }
        return false;
    }

    /**
     * Render the messages view for the Livewire component.
     *
     * This method returns the view responsible for displaying
     * the messages interface, which includes the chat box and
     * input area for sending messages.
     *
     * @return Application|Factory|View|\Illuminate\View\View
     */
    public function render(): Application | Factory | View | \Illuminate\View\View
    {
        return view('filament-messages::livewire.messages.messages');
    }
}
