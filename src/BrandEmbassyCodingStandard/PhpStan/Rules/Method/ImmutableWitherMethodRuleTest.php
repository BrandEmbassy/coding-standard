<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\PhpStan\Rules\Method;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ImmutableWitherMethodRule>
 */
class ImmutableWitherMethodRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ImmutableWitherMethodRule();
    }


    public function testAnalyseMutableWithers(): void
    {
        $this->analyse(
            [__DIR__ . '/__fixtures__/MutableWithersClass.php'],
            [
                [
                    'Method withStringSetter() is a mutable wither as it calls a setter on $this. The method should return modified clone of $this.',
                    25,
                ],
                [
                    'Method withString() is a mutable wither as it returns $this. The method should return modified clone of $this.',
                    33,
                ],
                [
                    'Method withStringAssignment() is a mutable wither as it writes to own property. The method should return modified clone of $this.',
                    42,
                ],
            ]
        );
    }


    public function testAnalyseImmutableWithers(): void
    {
        $this->analyse(
            [__DIR__ . '/__fixtures__/ImmutableWithersClass.php'],
            []
        );
    }
}
