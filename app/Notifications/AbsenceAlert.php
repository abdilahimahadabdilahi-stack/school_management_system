<?php

namespace App\Notifications;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AbsenceAlert extends Notification
{
    use Queueable;

    public function __construct(public Student $student) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Three-Day Absence Alert')
            ->greeting('Attendance Alert')
            ->line('Your child has been absent from school for three consecutive days.');
    }
}
