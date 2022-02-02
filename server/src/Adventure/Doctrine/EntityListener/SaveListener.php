<?php

declare(strict_types=1);

namespace App\Adventure\Doctrine\EntityListener;

use App\Adventure\Entity\Save;

final class SaveListener
{
    public function prePersist(Save $save): void
    {
        $this->populateSave($save);
    }

    public function preUpdate(Save $save): void
    {
        $this->populateSave($save);
    }

    private function populateSave(Save $save): void
    {
        if (null !== $save->getCheckpoint()) {
            $save->setQuest($save->getCheckpoint()->getQuest());
        }

        if (null !== $save->getQuest()) {
            $save->setRegion($save->getQuest()->getRegion());
        }

        if (null !== $save->getRegion()) {
            $save->setContinent($save->getRegion()->getContinent());
        }

        if (null !== $save->getContinent()) {
            $save->setWorld($save->getContinent()->getWorld());
        }
    }
}
