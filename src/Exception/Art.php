<?php

/**
 * Copyright 2014 SURFnet bv
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Surfnet\StepupBundle\Exception;

use Exception;
use Symfony\Component\Debug\Exception\FlattenException;

class Art
{
    /**
     * @param Exception $exception
     * @return string
     */
    public static function forException(Exception $exception)
    {
        return self::calculateArt(get_class($exception), $exception->getMessage());
    }

    /**
     * @param FlattenException $exception
     * @return string
     */
    public static function forFlattenException(FlattenException $exception)
    {
        return self::calculateArt($exception->getClass(), $exception->getMessage());
    }

    /**
     * @param string $className
     * @param string $message
     * @return string
     */
    private static function calculateArt($className, $message)
    {
        return $art = substr(abs(crc32(md5($className . $message))), 0, 4);
    }
}
