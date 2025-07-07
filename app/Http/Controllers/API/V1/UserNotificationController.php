<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification; // Important: Use this model for database notifications

class UserNotificationController extends Controller
{
    /**
     * Display a listing of the authenticated user's notifications.
     * Route: GET /api/v1/notifications
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Retrieve notifications for the user.
        // You can filter by read/unread status based on request parameters if needed.
        $notifications = $user->notifications() // Relationship provided by Notifiable trait
                            ->latest() // Order by creation date (newest first)
                            ->paginate(15); // Paginate the results

        // The 'data' column (JSON) will be automatically cast to an array/object when accessed.
        // Consider creating a NotificationResource to format the output consistently,
        // especially the 'data' payload.

        return response()->json($notifications);
    }

    /**
     * Mark a specific notification as read for the authenticated user.
     * Route: PUT /api/v1/notifications/{notification}
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Notifications\DatabaseNotification  $notification // Route model binding for DatabaseNotification
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request, DatabaseNotification $notification)
    {
        $user = $request->user();

        // Ensure the notification belongs to the authenticated user
        if ($user->UserID !== $notification->notifiable_id || get_class($user) !== $notification->notifiable_type) {
             // Although notifiable_type check might be redundant if only User can have notifications via Sanctum
             // It's safer to just check the ID for Sanctum authenticated users
             if ($user->UserID !== $notification->notifiable_id) {
                 return response()->json(['message' => 'Unauthorized to access this notification.'], 403);
             }
        }

        $notification->markAsRead(); // Mark the notification as read

        // Consider returning the updated notification resource
        return response()->json(['message' => 'Notification marked as read']);
    }

    /**
     * Delete a specific notification for the authenticated user.
     * Route: DELETE /api/v1/notifications/{notification}
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Notifications\DatabaseNotification  $notification // Route model binding for DatabaseNotification
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, DatabaseNotification $notification)
    {
        $user = $request->user();

        // Ensure the notification belongs to the authenticated user
         if ($user->UserID !== $notification->notifiable_id) {
             return response()->json(['message' => 'Unauthorized to access this notification.'], 403);
         }

        $notification->delete(); // Delete the notification

        return response()->json(null, 204); // 204 No Content for successful deletion
    }

     /**
      * Mark all unread notifications as read for the authenticated user.
      * Route: POST /api/v1/notifications/mark-all-as-read
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\JsonResponse
      */
     public function markAllAsRead(Request $request)
     {
         $user = $request->user();

         // Get all unread notifications for the user and mark them as read
         $user->unreadNotifications->markAsRead();

         return response()->json(['message' => 'All unread notifications marked as read']);
     }
}