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
    public static function parse(string $path): array
    {
        $nodes = json_decode(file_get_contents(__DIR__.'/'.$path), true);
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

class ModuleResolver
{
    private string $rootPath;

    public function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;
    }

    public function resolve(array $statements): array
    {
        // TODO
        return [];
    }
}


print_r(Parser::parse('./test1.json'));
exit;
