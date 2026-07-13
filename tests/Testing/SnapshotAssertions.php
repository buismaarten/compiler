<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Testing;

use ReflectionClass;

trait SnapshotAssertions
{
    private int $snapshotAssertionIndex = 0;

    protected function assertMatchesSnapshot(mixed $actual, ?string $snapshotName = null): void
    {
        $path = $this->snapshotPath($snapshotName);
        $content = $this->snapshotContent($actual);

        if (!is_file($path)) {
            $this->writeSnapshot($path, $content);
        }

        if (($expected = file_get_contents($path)) === false) {
            $this->fail("Unable to read snapshot: {$path}");
        }

        $this->assertEquals(
            expected: $expected,
            actual: $content,
            message: "Snapshot mismatch: {$path}",
        );
    }

    private function snapshotPath(?string $snapshotName = null): string
    {
        $reflection = new ReflectionClass(static::class);

        $name = $this->snapshotName($snapshotName);
        $name = preg_replace('/[^A-Za-z0-9._-]+/', '_', $name);

        return sprintf(
            '%s/__snapshots__/%s__%s.txt',
            dirname($reflection->getFileName()),
            $reflection->getShortName(),
            trim($name, '_'),
        );
    }

    private function snapshotName(?string $snapshotName): string
    {
        if ($snapshotName === null) {
            $snapshotName = $this->name();
        }

        return sprintf('%s__%d', $snapshotName, ++$this->snapshotAssertionIndex);
    }

    private function snapshotContent(mixed $actual): string
    {
        if (is_string($actual)) {
            return rtrim($actual, "\n")."\n";
        }

        return print_r($actual, true);
    }

    private function writeSnapshot(string $path, string $content): void
    {
        $directory = dirname($path);

        if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
            $this->fail("Unable to create snapshot directory: {$directory}");
        }

        if (file_put_contents($path, $content) === false) {
            $this->fail("Unable to write snapshot: {$path}");
        }
    }
}
