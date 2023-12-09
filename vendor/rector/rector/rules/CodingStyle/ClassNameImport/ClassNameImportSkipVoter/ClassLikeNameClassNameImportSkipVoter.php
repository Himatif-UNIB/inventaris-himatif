<?php

declare (strict_types=1);
namespace Rector\CodingStyle\ClassNameImport\ClassNameImportSkipVoter;

use PhpParser\Node;
use Rector\CodingStyle\ClassNameImport\ShortNameResolver;
use Rector\CodingStyle\Contract\ClassNameImport\ClassNameImportSkipVoterInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
/**
 * Prevents adding:
 *
 * use App\SomeClass;
 *
 * If there is already:
 *
 * class SomeClass {}
 */
final class ClassLikeNameClassNameImportSkipVoter implements \Rector\CodingStyle\Contract\ClassNameImport\ClassNameImportSkipVoterInterface
{
    /**
     * @readonly
     * @var \Rector\CodingStyle\ClassNameImport\ShortNameResolver
     */
    private $shortNameResolver;
    public function __construct(\Rector\CodingStyle\ClassNameImport\ShortNameResolver $shortNameResolver)
    {
        $this->shortNameResolver = $shortNameResolver;
    }
    public function shouldSkip(\Rector\Core\ValueObject\Application\File $file, \Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType $fullyQualifiedObjectType, \PhpParser\Node $node) : bool
    {
        $classLikeNames = $this->shortNameResolver->resolveShortClassLikeNamesForNode($node);
        if ($classLikeNames === []) {
            return \false;
        }
        $shortNameLowered = $fullyQualifiedObjectType->getShortNameLowered();
        foreach ($classLikeNames as $classLikeName) {
            if (\strtolower($classLikeName) === $shortNameLowered) {
                return \true;
            }
        }
        return \false;
    }
}
