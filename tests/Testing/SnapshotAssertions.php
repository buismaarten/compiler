<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Testing;

trait SnapshotAssertions
{
    private const SEPARATOR = '-----';

    protected function assertMatchesSnapshot(string $pattern, callable $transform): void
    {
        $files = glob($pattern);

        if (!$files) {
            $this->fail("No snapshot files found for pattern: {$pattern}");
        }

        foreach ($files as $file) {
            $content = file_get_contents($file);

            if ($content === false) {
                $this->fail("Unable to read snapshot: {$file}");
            }

            [$input, $expected] = explode(self::SEPARATOR, $content);
            $expected = self::normalize($expected);
            $actual = self::normalize($transform($input));

            if (!$expected) {
                file_put_contents($file, "{$input}\n".self::SEPARATOR."\n{$actual}\n");
                continue;
            }

            $this->assertEquals($expected, $actual, "Snapshot mismatch: {$file}");
        }
    }

    private static function normalize(mixed $value): string
    {
        $value = print_r($value, true);
        $value = trim($value);

        return $value;
    }
}
