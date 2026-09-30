<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Testing;

trait SnapshotAssertions
{
    private const SNAPSHOT_SEPARATOR = '-----';

    protected function assertSnapshot(string $pattern, callable $callback): void
    {
        $update = self::shouldUpdateSnapshots();
        $files = glob($pattern);

        if ($update) {
            $this->expectNotToPerformAssertions();
        }

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

            [$input, $expected] = explode(self::SNAPSHOT_SEPARATOR, $content);
            $input = trim($input);
            $expected = self::normalizeSnapshot($expected);
            $actual = self::normalizeSnapshot($callback($input));

            if ($update || !$expected) {
                file_put_contents($file, self::buildSnapshot($input, $actual));
            }

            if (!$update) {
                $this->assertEquals($expected, $actual, "Snapshot mismatch: {$file}");
            }
        }
    }

    private static function buildSnapshot(string $input, string $actual): string
    {
        $output  = "{$input}\n";
        $output .= self::SNAPSHOT_SEPARATOR."\n";
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
