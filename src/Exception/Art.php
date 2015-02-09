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

class Art
{
    /**
     * @param object $exception Not type-hinted against \Exception, as Symfony's FlattenException is not an Exception.
     * @return string
     */
    public static function forException($exception)
    {
        $exceptionDifferentiator = get_class($exception) . $exception->getMessage();
        $art = substr(abs(crc32(md5($exceptionDifferentiator))), 0, 4);

        return $art;
    }
}
