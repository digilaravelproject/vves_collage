<?php

namespace App\Mail;

use App\Models\PendingAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WorkflowPendingMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public PendingAction $pendingAction;

    /**
     * Create a new message instance.
     */
    public function __construct(PendingAction $pendingAction)
    {
        $this->pendingAction = $pendingAction->load(['maker', 'institution']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $institutionName = $this->pendingAction->institution ? ' [' . $this->pendingAction->institution->name . ']' : '';
        return new Envelope(
            subject: 'Workflow Action Required: ' . $this->pendingAction->action . ' ' . class_basename($this->pendingAction->model_type) . $institutionName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.workflow-pending',
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
