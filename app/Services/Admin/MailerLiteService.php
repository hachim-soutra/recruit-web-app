<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class MailerLiteService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.mailerlite.api_key');
        $this->baseUrl = 'https://connect.mailerlite.com/api';
    }

    /**
     * Get HTTP client with proper headers
     */
    private function getHttpClient()
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }

    /**
     * Create or get existing group by name
     */
    public function createOrGetGroup($groupName)
    {
        try {
            // First, try to find existing group
            $existingGroup = $this->findGroupByName($groupName);

            if ($existingGroup) {
                return $existingGroup;
            }

            // Create new group if it doesn't exist
            $response = $this->getHttpClient()->post("{$this->baseUrl}/groups", [
                'name' => $groupName
            ]);

            if ($response->successful()) {
                $group = $response->json()['data'];
                return $group;
            }

            throw new Exception("Failed to create group: " . $response->body());

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Create or update subscriber with custom fields
     */
    public function createOrUpdateSubscriber($subscriberData)
    {
        try {
            $payload = [
                'email' => $subscriberData['email'],
                'fields' => [
                    'name' => $subscriberData['first_name'],
                    'last_name' => $subscriberData['last_name'],
                    'salary_range' => $subscriberData['salary_range'],
                    'salary_period' => $subscriberData['salary_period'],
                    'job_location' => $subscriberData['job_location'],
                    'employment_type' => $subscriberData['employment_type'],
                    'industry' => $subscriberData['industry']
                ]
            ];

            $response = $this->getHttpClient()->post("{$this->baseUrl}/subscribers", $payload);

            if ($response->successful()) {
                $subscriber = $response->json()['data'];
                return $subscriber;
            }

            // Handle specific error cases
            if ($response->status() === 422) {
                $errors = $response->json()['errors'] ?? [];
                $responseData = $response->json();

                if (isset($errors['email'])) {
                    $emailError = $errors['email'][0] ?? '';

                    // Check if subscriber already exists
                    if (str_contains($emailError, 'already exists')) {
                        return $this->updateSubscriber($subscriberData);
                    }

                    // Check if subscriber is unsubscribed
                    if (str_contains($emailError, 'unsubscribed and cannot be imported')) {
                        // Try multiple approaches to handle unsubscribed user
                        $subscriberId = $responseData['subscriber'] ?? null;

                        if ($subscriberId) {
                            // Approach 1: Try to resubscribe first
                            try {
                                $this->resubscribeSubscriber($subscriberId);
                                sleep(1); // Wait for status update
                                return $this->updateSubscriberById($subscriberId, $subscriberData);
                            } catch (Exception $resubscribeError) {
                                Log::error("MailerLite: Resubscribe failed: " . $resubscribeError->getMessage());
                            }

                            // Approach 2: Try to update subscriber directly without resubscribe
                            try {
                                return $this->updateSubscriberById($subscriberId, $subscriberData);
                            } catch (Exception $updateError) {
                                Log::error("MailerLite: Direct update failed: " . $updateError->getMessage());
                            }

                            // Approach 3: Try force resubscribe method
                            try {
                                return $this->createSubscriberWithForceResubscribe($subscriberData);
                            } catch (Exception $forceError) {
                                Log::error("MailerLite: Force resubscribe failed: " . $forceError->getMessage());
                            }
                        }

                        // If all approaches fail, return detailed error info
                        return [
                            'status' => 'unsubscribed',
                            'message' => 'Subscriber is unsubscribed and could not be resubscribed automatically',
                            'email' => $subscriberData['email'],
                            'subscriber_id' => $subscriberId,
                            'requires_manual_consent' => true,
                            'suggestions' => [
                                'Ask user to manually resubscribe via email',
                                'Send double opt-in email',
                                'Contact MailerLite support if needed'
                            ]
                        ];
                    }
                }
            }

            throw new Exception("Failed to create subscriber: " . $response->body());

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Attempt to resubscribe a subscriber using their ID
     */
    private function resubscribeSubscriber($subscriberId)
    {
        try {
            // Try different resubscribe endpoints
            $endpoints = [
                "{$this->baseUrl}/subscribers/{$subscriberId}/resubscribe",
                "{$this->baseUrl}/subscribers/{$subscriberId}",
            ];

            foreach ($endpoints as $endpoint) {
                if (str_contains($endpoint, '/resubscribe')) {
                    // Standard resubscribe endpoint
                    $response = $this->getHttpClient()->post($endpoint);
                } else {
                    // Try updating status directly
                    $response = $this->getHttpClient()->put($endpoint, [
                        'status' => 'active'
                    ]);
                }

                if ($response->successful()) {
                    return $response->json()['data'] ?? $response->json();
                }
            }
            throw new Exception("Failed to resubscribe using all endpoints. Last response: " . $response->body());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update existing subscriber by ID (when we already have the subscriber ID)
     */
    private function updateSubscriberById($subscriberId, $subscriberData)
    {
        try {
            $payload = [
                'fields' => [
                    'name' => $subscriberData['first_name'],
                    'last_name' => $subscriberData['last_name'],
                    'salary_range' => $subscriberData['salary_range'],
                    'salary_period' => $subscriberData['salary_period'],
                    'job_location' => $subscriberData['job_location'],
                    'employment_type' => $subscriberData['employment_type'],
                    'industry' => $subscriberData['industry']
                ]
            ];

            $response = $this->getHttpClient()->put("{$this->baseUrl}/subscribers/{$subscriberId}", $payload);

            if ($response->successful()) {
                $updatedSubscriber = $response->json()['data'] ?? $response->json();
                return $updatedSubscriber;
            }

            throw new Exception("Failed to update subscriber by ID: " . $response->body());

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update existing subscriber
     */
    private function updateSubscriber($subscriberData)
    {
        try {
            // First, get the subscriber to get their ID
            $getResponse = $this->getHttpClient()->get("{$this->baseUrl}/subscribers/{$subscriberData['email']}");

            if (!$getResponse->successful()) {
                throw new Exception("Failed to get subscriber: " . $getResponse->body());
            }

            $responseData = $getResponse->json();
            // Handle different response structures
            $subscriber = null;
            if (isset($responseData['data'])) {
                $subscriber = $responseData['data'];
            } elseif (isset($responseData['id'])) {
                $subscriber = $responseData;
            } else {
                throw new Exception("Unexpected response structure: " . json_encode($responseData));
            }

            // Check for subscriber ID in different possible keys
            $subscriberId = $subscriber['id'] ?? $subscriber['subscriber_id'] ?? null;

            if (!$subscriberId) {
                throw new Exception("No subscriber ID found in response: " . json_encode($subscriber));
            }

            return $this->updateSubscriberById($subscriberId, $subscriberData);

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Alternative method: Create subscriber with force resubscribe
     */
    public function createSubscriberWithForceResubscribe($subscriberData)
    {
        try {
            // Try different payload structures
            $payloads = [
                [
                    'email' => $subscriberData['email'],
                    'status' => 'active',
                    'resubscribe' => true,
                    'fields' => [
                        'name' => $subscriberData['first_name'],
                        'last_name' => $subscriberData['last_name'],
                        'salary_range' => $subscriberData['salary_range'],
                        'salary_period' => $subscriberData['salary_period'],
                        'job_location' => $subscriberData['job_location'],
                        'employment_type' => $subscriberData['employment_type'],
                        'industry' => $subscriberData['industry']
                    ]
                ],
                [
                    'email' => $subscriberData['email'],
                    'type' => 'subscribed',
                    'fields' => [
                        'name' => $subscriberData['first_name'],
                        'last_name' => $subscriberData['last_name'],
                        'salary_range' => $subscriberData['salary_range'],
                        'salary_period' => $subscriberData['salary_period'],
                        'job_location' => $subscriberData['job_location'],
                        'employment_type' => $subscriberData['employment_type'],
                        'industry' => $subscriberData['industry']
                    ]
                ]
            ];

            foreach ($payloads as $payload) {
                $response = $this->getHttpClient()->post("{$this->baseUrl}/subscribers", $payload);

                if ($response->successful()) {
                    $subscriber = $response->json()['data'] ?? $response->json();
                    return $subscriber;
                }
            }

            throw new Exception("Failed to create subscriber with force resubscribe using all methods");

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Add subscriber to group
     */
    public function addSubscriberToGroup($subscriberId, $groupId)
    {
        try {
            $response = $this->getHttpClient()->post("{$this->baseUrl}/subscribers/{$subscriberId}/groups/{$groupId}");

            if ($response->successful()) {
                return true;
            }

            throw new Exception("Failed to add subscriber to group: " . $response->body());

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Main method to handle the complete flow - all actions in one method
     * This method will:
     * 1. Check if "Alerts" group exists, create if not
     * 2. Create or update subscriber based on email
     * 3. Add subscriber to the "Alerts" group
     */
    public function processSubscriberToAlerts($subscriberData) {
        try {
            $required = ['first_name', 'last_name', 'email'];
            foreach ($required as $field) {
                if (empty($subscriberData[$field])) {
                    throw new Exception("Missing required field: {$field}");
                }
            }

            $optionalFields = ['salary_range', 'salary_period', 'job_location', 'employment_type', 'industry'];
            foreach ($optionalFields as $field) {
                if (!array_key_exists($field, $subscriberData)) {
                    $subscriberData[$field] = null;
                }
            }

            // Step 1: Create or get the "Alerts" group
            $group = $this->createOrGetGroup('Alerts');

            // Step 2: Create or update subscriber
            $subscriber = $this->createOrUpdateSubscriber($subscriberData);

            // Handle unsubscribed user response - FIXED: Added proper checks
            if (is_array($subscriber) && isset($subscriber['status']) && $subscriber['status'] === 'unsubscribed') {
                return [
                    'success' => false,
                    'message' => $subscriber['message'] ?? 'Subscriber is unsubscribed and could not be processed',
                    'subscriber' => $subscriber,
                    'group' => $group,
                    'requires_manual_consent' => $subscriber['requires_manual_consent'] ?? false,
                    'suggestions' => $subscriber['suggestions'] ?? [],
                    'actions_performed' => [
                        'group_created_or_found' => true,
                        'subscriber_created_or_updated' => false,
                        'added_to_group' => false
                    ]
                ];
            }

            // Step 3: Add subscriber to the group
            $subscriberId = $subscriber['id'] ?? $subscriber['subscriber_id'] ?? null;
            if (!$subscriberId) {
                throw new Exception("No subscriber ID found in response");
            }

            $this->addSubscriberToGroup($subscriberId, $group['id']);

            return [
                'success' => true,
                'message' => 'Subscriber successfully processed and added to Alerts group',
                'subscriber' => $subscriber,
                'group' => $group,
                'actions_performed' => [
                    'group_created_or_found' => true,
                    'subscriber_created_or_updated' => true,
                    'added_to_group' => true
                ]
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'subscriber' => null,
                'group' => null,
                'actions_performed' => [
                    'group_created_or_found' => false,
                    'subscriber_created_or_updated' => false,
                    'added_to_group' => false
                ]
            ];
        }
    }

    /**
     * Handle unsubscribed user with explicit consent
     * Use this method when you have explicit consent from the user to resubscribe
     */
    public function resubscribeWithConsent($subscriberData, $consentConfirmed = false)
    {
        if (!$consentConfirmed) {
            throw new Exception("Explicit consent is required to resubscribe an unsubscribed user");
        }

        try {
            // Try to create subscriber with explicit consent flags
            $payload = [
                'email' => $subscriberData['email'],
                'status' => 'active',
                'opt_in_type' => 'single', // or 'double' based on your preference
                'signup_timestamp' => now()->timestamp,
                'fields' => [
                    'name' => $subscriberData['first_name'],
                    'last_name' => $subscriberData['last_name'],
                    'salary_range' => $subscriberData['salary_range'],
                    'salary_period' => $subscriberData['salary_period'],
                    'job_location' => $subscriberData['job_location'],
                    'employment_type' => $subscriberData['employment_type'],
                    'industry' => $subscriberData['industry']
                ]
            ];

            $response = $this->getHttpClient()->post("{$this->baseUrl}/subscribers", $payload);

            if ($response->successful()) {
                $subscriber = $response->json()['data'] ?? $response->json();
                return $subscriber;
            }

            throw new Exception("Failed to resubscribe with consent: " . $response->body());

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find group by name
     */
    private function findGroupByName($groupName)
    {
        try {
            $response = $this->getHttpClient()->get("{$this->baseUrl}/groups", [
                'filter[name]' => $groupName,
                'limit' => 25
            ]);

            if ($response->successful()) {
                $groups = $response->json()['data'];

                foreach ($groups as $group) {
                    if (strtolower($group['name']) === strtolower($groupName)) {
                        return $group;
                    }
                }
            }

            return null;

        } catch (Exception $e) {
            Log::error("MailerLite: Error finding group: " . $e->getMessage());
            return null;
        }
    }
}
