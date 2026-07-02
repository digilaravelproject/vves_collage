<?php

namespace App\Mail;

use App\Models\PendingAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WorkflowStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public PendingAction $pendingAction;
    public string $status;
    public ?string $note;

    /**
     * Create a new message instance.
     */
    public function __construct(PendingAction $pendingAction, string $status, ?string $note = null)
    {
        $this->pendingAction = $pendingAction->load(['maker', 'checker', 'institution']);
        $this->status = $status;
        $this->note = $note;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $institutionName = $this->pendingAction->institution ? ' [' . $this->pendingAction->institution->name . ']' : '';
        return new Envelope(
            subject: 'Workflow Update: ' . ucfirst($this->status) . ' - ' . $this->pendingAction->action . ' ' . class_basename($this->pendingAction->model_type) . $institutionName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.workflow-status',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
