<?php

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
    public function parse(string $path): array
    {
        $nodes = json_decode(file_get_contents($path), true);
        $statements = [];

        foreach ($nodes as $node) {
            switch ($node['type']) {
                case 'PrintStatement':
                    $statements[] = new PrintStatement($node['value']);
                break;
            }
        }

        return $statements;
    }
}


$parser = new Parser;

print_r($parser->parse(__DIR__.'/test1.json'));
exit;
