<?php

class ImportStatement
{
    public string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }
}

class PrintStatement
{
    public string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }
}

class Parser
{
    public static function parse(string $json): array
    {
        $nodes = json_decode($json, true);
        $statements = [];

        foreach ($nodes as $node) {
            switch ($node['type']) {
                case 'ImportStatement':
                    $statements[] = new ImportStatement($node['path']);
                break;

                case 'PrintStatement':
                    $statements[] = new PrintStatement($node['value']);
                break;
            }
        }

        return $statements;
    }
}
