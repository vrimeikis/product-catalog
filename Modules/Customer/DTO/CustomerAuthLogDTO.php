<?php

declare(strict_types = 1);

namespace Modules\Customer\DTO;

use Modules\Core\DTO\DTO;
use Modules\Customer\Entities\UserAuthLog;
use Modules\Customer\Enum\CustomerAuthLogTypeEnum;
use Modules\Core\Exceptions\EnumNotFoundException;
use ReflectionException;

/**
 * Class CustomerAuthLogDTO
 * @package Modules\Customer\DTO
 */
class CustomerAuthLogDTO extends DTO
{
    /**
     * @var UserAuthLog
     */
    private UserAuthLog $authLog;


    /**
     * CustomerAuthLogDTO constructor.
     * @param UserAuthLog $authLog
     */
    public function __construct(UserAuthLog $authLog)
    {
        $this->authLog = $authLog;
    }

    /**
     * @return array
     * @throws EnumNotFoundException
     * @throws ReflectionException
     */
    protected function jsonData(): array
    {
        return [
            'token_id' => $this->authLog->token_id,
            'event_time' => $this->authLog->event_time->timestamp,
            'type' => CustomerAuthLogTypeEnum::from($this->authLog->type)->name(),
        ];
    }
}