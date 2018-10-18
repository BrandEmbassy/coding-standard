<?php declare(strict_types = 1);

namespace BEIntegrationCodingStandard\Sniffs\Classes;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ClassHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use SlevomatCodingStandard\Helpers\UseStatementHelper;
use const T_ANON_CLASS;
use const T_CLASS;
use const T_OPEN_TAG;
use const T_STRING;
use const T_TRAIT;

class MockeryTraitSniff implements Sniff
{
    private const CODE_MISSING_MOCKERY_TRAIT = 'MissingMockeryTrait';
    private const CODE_SUPERFLUOUS_MOCKERY_TRAIT = 'SuperfluousMockeryTrait';


    /**
     * @return int[]
     */
    public function register(): array
    {
        return [T_OPEN_TAG];
    }


    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     *
     * @param int $pointer
     */
    public function process(File $phpcsFile, $pointer): void
    {
        $isMockeryTraitPresent = false;
        $classPointer = TokenHelper::findNext($phpcsFile, [T_CLASS, T_ANON_CLASS, T_TRAIT], $pointer);

        if ($classPointer === null) {
            return;
        }

        $usePointers = ClassHelper::getTraitUsePointers($phpcsFile, $classPointer);
        $tokens = $phpcsFile->getTokens();

        foreach ($usePointers as $usePointer) {
            $traitName = $tokens[TokenHelper::findNext($phpcsFile, [T_STRING], $usePointer)]['content'];

            if ($traitName === 'MockeryPHPUnitIntegration') {
                $isMockeryTraitPresent = true;

                break;
            }
        }

        $doesClassUseMockery = $this->doesClassUseMockery($phpcsFile, $pointer);

        if (!$isMockeryTraitPresent && $doesClassUseMockery) {
            $phpcsFile->addError(
                'Missing ' . MockeryPHPUnitIntegration::class . ' trait in test class',
                $classPointer,
                self::CODE_MISSING_MOCKERY_TRAIT
            );
        }

        if ($isMockeryTraitPresent && !$doesClassUseMockery) {
            $phpcsFile->addError(
                'Superfluous ' . MockeryPHPUnitIntegration::class . ' trait in test class',
                $classPointer,
                self::CODE_SUPERFLUOUS_MOCKERY_TRAIT
            );
        }
    }


    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     *
     * @param int $pointer
     */
    private function doesClassUseMockery(File $phpcsFile, $pointer): bool
    {
        $useStatements = UseStatementHelper::getUseStatements($phpcsFile, $pointer);

        $mockeryUse = null;
        foreach ($useStatements as $useStatement) {
            if ($useStatement->getFullyQualifiedTypeName() === 'Mockery') {
                $mockeryUse = $useStatement;

                break;
            }
        }

        return $mockeryUse !== null;
    }
}
