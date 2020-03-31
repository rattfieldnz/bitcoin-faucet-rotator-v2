<?php

namespace App\Libraries\Laroute\Generators;

use Illuminate\Filesystem\Filesystem;
use App\Libraries\Laroute\Compilers\CompilerInterface as Compiler;

interface GeneratorInterface
{
    /**
     * Create a new template generator instance.
     *
     * @param $compiler   \App\Libraries\Laroute\Compilers\CompilerInterface
     * @param $filesystem \Illuminate\Filesystem\Filesystem
     *
     */
    public function __construct(Compiler $compiler, Filesystem $filesystem);

    /**
     * Compile the template.
     *
     * @param $templatePath
     * @param $templateData
     * @param $filePath
     *
     * @return string
     */
    public function compile($templatePath, Array $templateData, $filePath);
}
