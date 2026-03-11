<?php

namespace BrandEmbassyCodingStandard\Rector\DoctrineEntitiesCannotBeFinalRector\Fixture;

use Doctrine\ORM\Mapping as DoctrineMapping;

#[DoctrineMapping\Entity]
final class FinalEntityWithNonStandardAlias
{
    #[DoctrineMapping\Id]
    #[DoctrineMapping\GeneratedValue(strategy: 'AUTO')]
    #[DoctrineMapping\Column(type: 'integer', name: 'brands_id')]
    public ?int $id;
}
-----
<?php

namespace BrandEmbassyCodingStandard\Rector\DoctrineEntitiesCannotBeFinalRector\Fixture;

use Doctrine\ORM\Mapping as DoctrineMapping;

#[DoctrineMapping\Entity]
class FinalEntityWithNonStandardAlias
{
    #[DoctrineMapping\Id]
    #[DoctrineMapping\GeneratedValue(strategy: 'AUTO')]
    #[DoctrineMapping\Column(type: 'integer', name: 'brands_id')]
    public ?int $id;
}
