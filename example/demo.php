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
    public function parse(string $path): array
    {
        $nodes = json_decode(file_get_contents($path), true);
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
    private Parser $parser;
    private string $rootPath;
    private array $resolvedModules = [];

    public function __construct(Parser $parser, string $rootPath)
    {
        $this->parser = $parser;
        $this->rootPath = $rootPath;
    }

    public function resolve(array $statements): array
    {
        $this->resolvedModules[] = $statements;
        $this->resolveModule($statements);

        return $this->resolvedModules;
    }

    private function resolveModule(array $statements): void
    {
        foreach ($statements as $statement) {
            if ($statement instanceof ImportStatement) {
                $importedStatements = $this->parser->parse("{$this->rootPath}/{$statement->path}");

                $this->resolvedModules[$statement->path] = $importedStatements;
                $this->resolveModule($importedStatements);
            }
        }
    }
}


$parser = new Parser;
$resolver = new ModuleResolver($parser, __DIR__);

print_r($resolver->resolve($parser->parse(__DIR__.'/test1.json')));
exit;
