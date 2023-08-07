<?php 

namespace App\User\Domain\Entity\ValueObjects;

use Symfony\Component\Uid\Uuid as UuidGenerator;
use Symfony\Component\Uid\UuidV4;

final class Uuid {

    /**
     * Generate uuid v4
     *
     * @return UuidV4
     */
    public static function generate(): UuidV4 {
        return UuidGenerator::v4();
    }

}
