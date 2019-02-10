<?php declare(strict_types = 1);

namespace Fousky\Component\Debug;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class VarDumperString
{
    public static function dump(): string
    {
        if (!class_exists('Symfony\\Component\\VarDumper\\Cloner\\VarCloner') ||
            !class_exists('Symfony\\Component\\VarDumper\\Dumper\\CliDumper')
        ) {
            throw new \RuntimeException('Missing `symfony/var-dumper` library.');
        }

        $cloner = new VarCloner();
        $dumper = new CliDumper();
        $output = fopen('php://memory', 'r+b');

        foreach (func_get_args() as $arg) {
            $dumper->dump($cloner->cloneVar($arg), $output);
        }

        return stream_get_contents($output, -1, 0);
    }
}
