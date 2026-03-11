<?php

namespace BrandEmbassyCodingStandard\Rector\DoctrineEntitiesCannotBeFinalRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="BE\SomeEntityRepository")
 */
class NonFinalEntityWithAnnotationAlias
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="brands_id")
     */
    public ?int $id;
}
