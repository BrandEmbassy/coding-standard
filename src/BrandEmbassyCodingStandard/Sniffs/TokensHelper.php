<?php declare(strict_types = 1);

namespace BrandEmbassyCodingStandard\Sniffs;

use PHP_CodeSniffer\Files\File;

final class TokensHelper
{
    public static function getPointerContent(File $phpcsFile, int $pointer): string
    {
        $tokens = $phpcsFile->getTokens();

        return $tokens[$pointer]['content'];
    }
}
