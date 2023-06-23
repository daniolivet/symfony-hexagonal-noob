<?php 

namespace App\User\Application\DTO\ValueObjects;

use Symfony\Component\Uid\Uuid as UuidGenerator;

final class Uuid {

    /**
     * Generate uuid v4
     *
     * @return string
     */
    public static function generate(): string {
        return UuidGenerator::v4();
    }

}
