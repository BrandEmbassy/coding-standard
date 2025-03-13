<?php

namespace BrandEmbassyCodingStandard\Rector\DisallowConstantsInTestsRector\Fixture;

final class FixStringConstant extends \PHPUnit\Framework\TestCase
{
    public function baz(): void
    {
        echo SomeDisallowedStringConstant::FOO;
    }
}

final class SomeDisallowedStringConstant {
    public const FOO = '1';
}
?>
-----
<?php

namespace BrandEmbassyCodingStandard\Rector\DisallowConstantsInTestsRector\Fixture;

final class FixStringConstant extends \PHPUnit\Framework\TestCase
{
    public function baz(): void
    {
        echo '1';
    }
}

final class SomeDisallowedStringConstant {
    public const FOO = '1';
}
?>
