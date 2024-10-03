<?php

namespace App\Lib\Helper;

use App\Services\FileService;
use App\Services\MeetingIdeaService;
use App\Services\MeetingService;
use App\Services\UserMeetingService;
use App\Services\UserService;
use Psr\Container\ContainerInterface;

class MapService
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function userService(): UserService
    {
        return c(UserService::class);
    }

    public function fileService(): FileService
    {
        return c(FileService::class);
    }

    public function meetingService(): MeetingService
    {
        return c(MeetingService::class);
    }

    public function userMeetingService(): UserMeetingService
    {
        return c(UserMeetingService::class);
    }

    public function meetingIdeaService(): MeetingIdeaService
    {
        return c(MeetingIdeaService::class);
    }


}
