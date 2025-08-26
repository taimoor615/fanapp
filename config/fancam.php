<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Fancam Module Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains all the configuration options for the fancam module
    |
    */

    // Upload settings
    'upload' => [
        'max_photos_per_game' => 5,
        'max_file_size' => 2048, // KB
        'allowed_extensions' => ['jpeg', 'jpg', 'png', 'gif'],
        'image_quality' => 85,
        'thumbnail_size' => [300, 300],
    ],

    // Points system
    'points' => [
        'default_points_per_photo' => 10,
        'min_points' => 0,
        'max_points' => 100,
        'bonus_points_for_approved' => 5, // Additional points when photo is approved
    ],

    // Status options
    'status_options' => [
        'pending' => 'Pending Review',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ],

    // Photo storage settings
    'storage' => [
        'disk' => 'public',
        'path' => 'fancams',
        'thumbnails_path' => 'fancams/thumbnails',
    ],

    // Admin settings
    'admin' => [
        'photos_per_page' => 12,
        'enable_bulk_actions' => true,
        'enable_auto_approval' => false, // Auto approve photos from trusted users
        'trusted_user_ids' => [], // Users whose photos are auto-approved
    ],

    // User settings
    'user' => [
        'photos_per_page' => 12,
        'enable_photo_editing' => true,
        'allow_delete_approved_photos' => false, // Allow users to delete approved photos
    ],

    // Notification settings
    'notifications' => [
        'notify_admin_on_upload' => true,
        'notify_user_on_status_change' => true,
        'admin_email' => env('FANCAM_ADMIN_EMAIL', 'admin@example.com'),
    ],

    // Image processing
    'image_processing' => [
        'enable_watermark' => false,
        'watermark_text' => 'Your App Name',
        'watermark_position' => 'bottom-right',
        'enable_resize' => true,
        'max_width' => 1920,
        'max_height' => 1080,
    ],

    // Security settings
    'security' => [
        'enable_image_validation' => true,
        'scan_for_inappropriate_content' => false, // Requires additional service
        'enable_virus_scan' => false, // Requires ClamAV or similar
        'rate_limit_uploads' => 10, // Max uploads per hour per user
    ],
];
