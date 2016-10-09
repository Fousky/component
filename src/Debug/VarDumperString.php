<?php

namespace Fousky\Component\Debug;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

/**
 * @author Lukáš Brzák <lukas.brzak@aquadigital.cz>
 */
class VarDumperString
{
    /**
     * @return string
     */
    public static function dump()
    {
        $cloner = new VarCloner();
        $dumper = new CliDumper();
        $output = fopen('php://memory', 'r+b');

        foreach (func_get_args() as $arg) {
            $dumper->dump($cloner->cloneVar($arg), $output);
        }

        return stream_get_contents($output, -1, 0);
    }
}
