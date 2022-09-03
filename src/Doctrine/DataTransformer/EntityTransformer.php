<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\DataTransformer;

/**
 * @template A
 * @template D
 */
interface EntityTransformer
{
    /**
     * @param D|null $entity
     * @return A|null
     */
    public function transform($entity): ?object;

    /**
     * @param A|null $entity
     * @return D|null
     */
    public function reverseTransform($entity): ?object;
}
