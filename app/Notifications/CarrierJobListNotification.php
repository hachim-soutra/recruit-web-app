<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
class CarrierJobListNotification extends Notification
{
    use Queueable;

    protected $report;

    /**
     * Create a new notification instance.
     *
     * @param array $report
     */
    public function __construct(array $report)
    {
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Your Assigned Jobs (Daily Summary)')
            ->greeting('Hello ' . $this->report['agent'] . ',')
            ->line('Here is the list of jobs that were assigned to you yesterday:')
            ->line('Total number of jobs: **' . $this->report['total_jobs'] . '**')
            ->line('Details:');

        foreach ($this->report['jobs'] as $job) {
            $jobUrl = route('common.job-detail', ['id' => $job['job_id']]);
            $mail->line("- [{$job['created_at']}] [ID: {$job['job_id']} - {$job['job_title']}]({$jobUrl})");
        }

        $mail->line('')
            ->line('Thank you for your commitment!');

        return $mail;
    }

    /**
     * Get the array representation of the notification (for database or broadcast, if needed).
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->report;
    }
}
