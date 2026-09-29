<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Testing;

trait SnapshotAssertions
{
    protected function assertMatchesSnapshot(string $pattern, callable $transform): void
    {
        $files = glob($pattern);

        if ($files === false || $files === []) {
            $this->fail("No snapshot files matched pattern: {$pattern}");
        }

        foreach ($files as $file) {
            [$input, $expected] = $this->readSnapshot($file, $transform);

            $actual = $this->snapshotContent($transform($input));

            $this->assertEquals($expected, $actual, "Snapshot mismatch: {$file}");
        }
    }

    private function readSnapshot(string $file, callable $transform): array
    {
        if (($content = file_get_contents($file)) === false) {
            $this->fail("Unable to read snapshot: {$file}");
        }

        $parts = explode('-----', $content, 2);

        if (count($parts) === 2) {
            return [trim($parts[0]), trim($parts[1])];
        }

        $input = trim($content);
        $actual = $this->snapshotContent($transform($input));

        file_put_contents($file, "{$input}\n-----\n{$actual}\n");

        return [$input, $actual];
    }

    private function snapshotContent(mixed $actual): string
    {
        if (is_string($actual)) {
            return trim($actual);
        }

        return trim(print_r($actual, true));
    }
}
