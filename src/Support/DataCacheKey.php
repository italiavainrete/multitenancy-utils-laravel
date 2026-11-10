<?php

namespace IVR\MultiTenancyUtils\Support;

use ReflectionClass;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionType;
use ReflectionUnionType;
use Spatie\LaravelData\Data;

/**
 * Cache keys for serialized Data objects, versioned on the shape of the class.
 *
 * Brand and shops are cached serialized. When a release adds a property to a Data class
 * (FaviconData::$icons_base_url, $has_svg_icon...), the objects already in the cache are
 * unserialized without it: the typed property stays uninitialized and reading it is a fatal
 * Error ("must not be accessed before initialization"), until the TTL expires.
 * With the shape fingerprint in the key, a deploy that changes the shape just misses the
 * cache and fetches fresh data; the old entries expire on their own.
 */
class DataCacheKey
{
    /** @var array<class-string, string> */
    private static array $fingerprints = [];

    /**
     * e.g. DataCacheKey::for('brand', BrandData::class, 'ivr') => "brand:<fingerprint>:ivr"
     *
     * @param  class-string<Data>  $dataClass
     */
    public static function for(string $prefix, string $dataClass, string $key): string
    {
        return $prefix.':'.self::fingerprint($dataClass).':'.$key;
    }

    /**
     * Short hash of the public properties (name and type) of the class and of the Data
     * classes it nests, recursively. Memoized per process.
     *
     * @param  class-string<Data>  $dataClass
     */
    public static function fingerprint(string $dataClass): string
    {
        if (! isset(self::$fingerprints[$dataClass])) {
            $seen = [];
            $shape = self::shape($dataClass, $seen);
            ksort($shape);
            self::$fingerprints[$dataClass] = substr(hash('sha256', json_encode($shape)), 0, 12);
        }

        return self::$fingerprints[$dataClass];
    }

    /**
     * @param  array<class-string, true>  $seen
     * @return array<string, string>
     */
    private static function shape(string $class, array &$seen): array
    {
        if (isset($seen[$class])) {
            return [];
        }
        $seen[$class] = true;

        $shape = [];
        foreach ((new ReflectionClass($class))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->isStatic()) {
                continue;
            }
            $type = $property->getType();
            $shape[$class.'::$'.$property->getName()] = (string) $type;

            foreach (self::classNames($type) as $nested) {
                if (is_subclass_of($nested, Data::class)) {
                    $shape += self::shape($nested, $seen);
                }
            }
        }

        return $shape;
    }

    /**
     * @return list<string>
     */
    private static function classNames(?ReflectionType $type): array
    {
        if ($type instanceof ReflectionNamedType) {
            return $type->isBuiltin() ? [] : [$type->getName()];
        }
        if ($type instanceof ReflectionUnionType || $type instanceof ReflectionIntersectionType) {
            return array_merge(...array_map(fn (ReflectionType $t) => self::classNames($t), $type->getTypes()));
        }

        return [];
    }
}
