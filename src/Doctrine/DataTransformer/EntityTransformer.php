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
     * @param D $entity
     *
     * @return A
     */
    public function transform($entity);

    /**
     * @param A $entity
     *
     * @return D
     */
    public function reverseTransform($entity);
}
