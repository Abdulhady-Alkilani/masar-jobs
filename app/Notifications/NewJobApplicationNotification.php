<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage; // Import DatabaseMessage
use Illuminate\Notifications\Notification;
use App\Models\JobApplication; // Import the JobApplication model
use App\Models\User; // Import the User model

class NewJobApplicationNotification extends Notification // Implement ShouldQueue if you want to queue notifications
{
    use Queueable;

    protected $jobApplication;
    protected $applicant; // User who applied

    /**
     * Create a new notification instance.
     */
    public function __construct(JobApplication $jobApplication, User $applicant)
    {
        //
        $this->jobApplication = $jobApplication;
        $this->applicant = $applicant;
        // If you want to queue this notification, implement ShouldQueue
        // and configure your queue driver (.env: QUEUE_CONNECTION=database/redis/sync etc.)
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // We only want to store this notification in the database
        return ['database'];
    }

    /**
     * Get the array representation of the notification for storing in the database.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        // Data to store in the 'data' column of the notifications table
        return [
            'job_application_id' => $this->jobApplication->ID, // Assuming JobApplication has an ID
            'job_id' => $this->jobApplication->JobID,
            'job_title' => $this->jobApplication->jobOpportunity->{'Job Title'}, // Get job title from the job relationship
            'applicant_user_id' => $this->applicant->UserID,
            'applicant_name' => $this->applicant->first_name . ' ' . $this->applicant->last_name,
            'message' => 'قام ' . $this->applicant->first_name . ' ' . $this->applicant->last_name . ' بالتقديم على وظيفة: ' . $this->jobApplication->jobOpportunity->{'Job Title'},
            // You can add URLs for deep linking if needed, e.g.:
            // 'url' => '/company-manager/applications/' . $this->jobApplication->ID,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         // You can customize the array representation if needed for other channels (e.g., broadcasting)
    //     ];
    // }
}