<?php

namespace BrandEmbassyCodingStandard\Rector\DisallowConstantsInTestsRector\Fixture;

final class FixIntConstant extends \PHPUnit\Framework\TestCase
{
    public function baz(): void
    {
        echo SomeDisallowedIntConstant::FOO;
    }
}

final class SomeDisallowedIntConstant {
    public const FOO = 1;
}
?>
-----
<?php

namespace BrandEmbassyCodingStandard\Rector\DisallowConstantsInTestsRector\Fixture;

final class FixIntConstant extends \PHPUnit\Framework\TestCase
{
    public function baz(): void
    {
        echo 1;
    }
}

final class SomeDisallowedIntConstant {
    public const FOO = 1;
}
?>
