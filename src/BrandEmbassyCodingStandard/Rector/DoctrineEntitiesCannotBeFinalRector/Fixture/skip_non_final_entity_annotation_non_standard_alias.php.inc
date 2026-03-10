<?php

namespace BrandEmbassyCodingStandard\Rector\DoctrineEntitiesCannotBeFinalRector\Fixture;

use Doctrine\ORM\Mapping as DoctrineMapping;

/**
 * @DoctrineMapping\Entity(repositoryClass="BE\SomeEntityRepository")
 */
class NonFinalEntityWithAnnotationNonStandardAlias
{
    /**
     * @DoctrineMapping\Id()
     * @DoctrineMapping\GeneratedValue(strategy="AUTO")
     * @DoctrineMapping\Column(type="integer", name="brands_id")
     */
    public ?int $id;
}
