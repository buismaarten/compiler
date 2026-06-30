<?php

class DeclareVariable
{
    public string $name;
    public string $value;

    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
}

class PrintVariable
{
    public string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }
}

class Parser
{
    public function parse(string $path): array
    {
        $nodes = json_decode(file_get_contents($path), true);
        $statements = [];

        foreach ($nodes as $node) {
            switch ($node['_type']) {
                case 'DeclareVariable':
                    $statements[] = new DeclareVariable($node['name'], $node['value']);
                break;

                case 'PrintVariable':
                    $statements[] = new PrintVariable($node['name']);
                break;
            }
        }

        return $statements;
    }
}


$parser = new Parser;

print_r($parser->parse(__DIR__.'/demo.json'));
exit;
