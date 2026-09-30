<?php

namespace Thrivecart\Server;

abstract class Controller
{
    protected function request(): array|object|null
    {
        return json_decode(file_get_contents('php://input') ?: null);
    }
}
