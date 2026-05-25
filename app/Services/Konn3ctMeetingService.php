<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\RequestException;

class Konn3ctMeetingService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        // Typically, you would pull these from your config or .env file
        // e.g., config('services.konn3ct.base_url')
        $this->baseUrl = env('KONN3CT_BASE_URL', 'http://localhost:3000');
        $this->apiKey = env('KONN3CT_API_KEY', 'default-insecure-key-change-me');
    }

    /**
     * Get a pre-configured HTTP client with the Authorization header.
     */
    protected function client()
    {
        return Http::withToken($this->apiKey)
            ->baseUrl($this->baseUrl)
            ->acceptJson();
    }

    /**
     * Create a new meeting room.
     *
     * @param string|null $name Optional meeting title
     * @param string|null $ownerEmail Email of the meeting owner
     * @param bool $isWaitingRoomEnabled Whether to enable the waiting room
     * @return array
     * @throws RequestException
     */
    public function createMeeting(?string $name = null, ?string $ownerEmail = null, bool $isWaitingRoomEnabled = true): array
    {
        $response = $this->client()->post('/api/external/v1/meetings', [
            'name' => $name,
            'ownerEmail' => $ownerEmail,
            'isWaitingRoomEnabled' => $isWaitingRoomEnabled,
        ]);

        return $response->throw()->json();
    }

    /**
     * Generate a secure join link and token for an existing room.
     *
     * @param string $roomId The room slug
     * @param string $name The display name of the joining user
     * @param string $email The email of the joining user
     * @param string $role The role (e.g. 'host' or 'attendee')
     * @param bool $isMobile Is the user on mobile?
     * @param bool $camera Start with camera enabled?
     * @param bool $mic Start with mic enabled?
     * @return array
     * @throws RequestException
     */
    public function joinMeeting(
        string $roomId,
        string $name,
        string $email,
        string $role = 'attendee',
        bool $isMobile = false,
        bool $camera = true,
        bool $mic = true
    ): array {
        $response = $this->client()->post('/api/external/v1/meetings/join', [
            'roomId' => $roomId,
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'isMobile' => $isMobile,
            'camera' => $camera,
            'mic' => $mic,
        ]);

        return $response->throw()->json();
    }

    /**
     * Convenience method to create a room and instantly generate a host join link.
     *
     * @param string $name The display name of the joining user
     * @param string $email The email of the joining user (will be the owner)
     * @param string|null $roomName Optional meeting title
     * @return array
     * @throws RequestException
     */
    public function createAndJoin(string $name, string $email, ?string $roomName = null, ?string $roomUrl = null): array
    {
        $response = $this->client()->post('/api/external/v1/meetings/create-and-join', [
            'name' => $name,
            'email' => $email,
            'roomName' => $roomName,
            'role' => 'host',
            'meetingSlug' => $roomUrl,
        ]);

        return $response->throw()->json();
    }

    /**
     * Check if a meeting is running and list active participants.
     *
     * @param string $roomId The room slug
     * @return array
     * @throws RequestException
     */
    public function getMeetingStatus(string $roomId): array
    {
        $response = $this->client()->get("/api/external/v1/meetings/{$roomId}/status");

        return $response->throw()->json();
    }

    /**
     * Helper to generate the absolute URL for the frontend meeting join link.
     *
     * @param string $joinPath The relative joinPath returned by the API
     * @return string
     */
    public function getFullJoinUrl(string $joinPath): string
    {
        return rtrim($this->baseUrl, '/') . $joinPath;
    }
}
