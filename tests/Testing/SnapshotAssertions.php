<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Testing;

trait SnapshotAssertions
{
    private const SEPARATOR = '-----';

    protected function assertMatchesSnapshot(string $pattern, callable $callback): void
    {
        $files = glob($pattern);

        if (!$files) {
            $this->fail("Unable to find snapshots for pattern: {$pattern}");
            return;
        }

        foreach ($files as $file) {
            $content = file_get_contents($file);

            if ($content === false) {
                $this->fail("Unable to read snapshot: {$file}");
                break;
            }

            [$input, $expected] = explode(self::SEPARATOR, $content);
            $expected = self::normalizeSnapshot($expected);
            $actual = self::normalizeSnapshot($callback($input));

            if (!$expected || self::shouldUpdateSnapshots()) {
                file_put_contents($file, self::buildSnapshot($input, $actual));
            }

            $this->assertEquals($expected, $actual, "Snapshot mismatch: {$file}");
        }
    }

    private static function buildSnapshot(string $input, string $actual): string
    {
        $output  = trim($input)."\n";
        $output .= self::SEPARATOR."\n";
        $output .= "{$actual}\n";

        return $output;
    }

    private static function normalizeSnapshot(mixed $value): string
    {
        $value = print_r($value, true);
        $value = trim($value);

        return $value;
    }

    private static function shouldUpdateSnapshots(): bool
    {
        if (($value = getenv('UPDATE_SNAPSHOTS')) !== false) {
            return preg_match('/^(1|true)$/i', $value) === 1;
        }

        return false;
    }
}
