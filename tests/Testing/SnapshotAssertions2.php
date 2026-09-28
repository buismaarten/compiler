<?php

declare(strict_types=1);

// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

namespace Package\Tests\Testing;

trait SnapshotAssertions2
{
    protected function assertMatchesFixtures(string $pattern, callable $transform): void
    {
        $files = glob($pattern);

        if ($files === false || $files === []) {
            $this->fail("No fixture files matched pattern: {$pattern}");
        }

        foreach ($files as $file) {
            [$input, $expected] = $this->readFixture($file, $transform);

            $actual = $this->fixtureContent($transform($input));

            $this->assertEquals($expected, $actual, "Fixture mismatch: {$file}");
        }
    }

    private function readFixture(string $file, callable $transform): array
    {
        if (($content = file_get_contents($file)) === false) {
            $this->fail("Unable to read fixture: {$file}");
        }

        $parts = explode('-----', $content, 2);

        if (count($parts) === 2) {
            return [trim($parts[0]), trim($parts[1])];
        }

        $input = trim($content);
        $actual = $this->fixtureContent($transform($input));

        file_put_contents($file, "{$input}\n-----\n{$actual}\n");

        return [$input, $actual];
    }

    private function fixtureContent(mixed $actual): string
    {
        if (is_string($actual)) {
            return trim($actual);
        }

        return trim(print_r($actual, true));
    }
}
