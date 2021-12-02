<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Doctrine\ORM;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<EntityNotFinalRule>
 */
final class EntityNotFinalRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new EntityNotFinalRule();
    }


    public function testCheckingIfEntityIsNotFinal(): void
    {
        $this->analyse(
            [__DIR__ . '/__fixtures__/FinalEntityClass.php'],
            [
                [
                    'Entity class BrandEmbassyCodingStandard\PhpStan\Rules\Doctrine\ORM\__fixtures__\FinalEntityClass is final which can cause problems with proxies.',
                    9,
                ],
            ]
        );
    }


    public function testCheckingThatAnyOtherClassCanBeFinal(): void
    {
        $this->analyse(
            [__DIR__ . '/__fixtures__/FinalClass.php'],
            []
        );
    }
}
