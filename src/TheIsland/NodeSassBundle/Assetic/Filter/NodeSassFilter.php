<?php
namespace TheIsland\NodeSassBundle\Assetic\Filter;

use Assetic\Asset\AssetInterface;
use Assetic\Exception\FilterException;
use Assetic\Filter\Sass\BaseSassFilter;
use Symfony\Component\Process\Process;

class NodeSassFilter extends BaseSassFilter
{
    const STYLE_NESTED     = 'nested';
    const STYLE_EXPANDED   = 'expanded';
    const STYLE_COMPACT    = 'compact';
    const STYLE_COMPRESSED = 'compressed';

    private $sassPath;
    private $nodePath;

    private $style;
    private $debugInfo;
    private $sourceMap;

    public function __construct($sassPath = '/usr/bin/node-sass', $nodePath = null)
    {
        $this->sassPath = $sassPath;
        $this->nodePath = $nodePath;
    }

    public function setStyle($style)
    {
        $this->style = $style;
    }

    public function setSourceMap($sourceMap)
    {
        $this->sourceMap = $sourceMap;
    }

    public function setDebugInfo($debugInfo)
    {
        $this->debugInfo = $debugInfo;
    }

    public function filterLoad(AssetInterface $asset)
    {
        $commandline = array();
        if (null !== $this->nodePath) {
            $commandline[] = $this->nodePath;
        }

        $commandline[] = $this->sassPath;

        if ($dir = $asset->getSourceDirectory()) {
            array_push($commandline, '--include-path', $dir);
        }

        if ($this->style) {
            array_push($commandline, '--output-style', $this->style);
        }

        if ($this->sourceMap) {
            array_push($commandline, '--source-map true');
        }

        if ($this->debugInfo) {
            array_push($commandline, '--source-comments');
        }

        foreach ($this->loadPaths as $loadPath) {
            array_push($commandline, '--include-path', $loadPath);
        }

        // input
        array_push($commandline, $input = tempnam(sys_get_temp_dir(), 'assetic_sass'));
        file_put_contents($input, $asset->getContent());

        array_push($commandline, '--stdout');

        $proc = new Process($commandline);
        $code = $proc->run();
        unlink($input);

        if (0 !== $code) {
            throw FilterException::fromProcess($proc)->setInput($asset->getContent());
        }

        $asset->setContent($proc->getOutput());
    }

    public function filterDump(AssetInterface $asset)
    {
    }
}
