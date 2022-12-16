<?php

namespace Spatie\StructureDiscoverer\DiscoverConditions;

use Spatie\StructureDiscoverer\Data\DiscoveredClass;
use Spatie\StructureDiscoverer\Data\DiscoveredStructure;
use Spatie\StructureDiscoverer\Data\DiscoveredEnum;
use Spatie\StructureDiscoverer\Data\DiscoveredInterface;

class ImplementsDiscoverCondition extends DiscoverCondition
{
    /** @var string[] */
    private array $interfaces;

    public function __construct(
        string ...$interfaces
    ) {
        $this->interfaces = $interfaces;
    }

    public function satisfies(DiscoveredStructure $discoveredData): bool
    {
        if ($discoveredData instanceof DiscoveredClass || $discoveredData instanceof DiscoveredEnum) {
            $foundImplements = array_filter(
                $discoveredData->implementsChain ?? $discoveredData->implements,
                fn (string $interface) => in_array($interface, $this->interfaces)
            );

            return count($foundImplements) > 0;
        }

        if ($discoveredData instanceof DiscoveredInterface) {
            $foundExtends = array_filter(
                $discoveredData->extendsChain ?? $discoveredData->extends,
                fn (string $class) => in_array($class, $this->interfaces)
            );

            return count($foundExtends) > 0;
        }

        return false;
    }
}
