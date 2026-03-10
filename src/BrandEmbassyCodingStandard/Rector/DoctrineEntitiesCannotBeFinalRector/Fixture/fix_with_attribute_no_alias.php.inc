<?php

namespace BrandEmbassyCodingStandard\Rector\DoctrineEntitiesCannotBeFinalRector\Fixture;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity]
final class FinalEntityWithAttributeNoAlias
{
    #[Id]
    #[GeneratedValue(strategy: 'AUTO')]
    #[Column(type: 'integer', name: 'brands_id')]
    public ?int $id;
}
-----
<?php

namespace BrandEmbassyCodingStandard\Rector\DoctrineEntitiesCannotBeFinalRector\Fixture;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity]
class FinalEntityWithAttributeNoAlias
{
    #[Id]
    #[GeneratedValue(strategy: 'AUTO')]
    #[Column(type: 'integer', name: 'brands_id')]
    public ?int $id;
}
