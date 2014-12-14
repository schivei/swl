<?php

namespace swl\core;

/**
 * The Parser translate all SWL files to PHP files based on Silly Framework
 *
 * @author schivei
 */
class Parser
{

    /**
     * @var string
     * @coverageIgnore
     */
    private $project;

    /**
     * @var sintax\ISintax[]
     * @coverageIgnore
     */
    private $sintaxes = [];

    /**
     * @var \SplFileObject[]
     * @coverageIgnore
     */
    private $swl_files = [];

    /**
     * @param string $project
     * @throws \InvalidArgumentException
     * @assert (__DIR__) == false
     * @assert ($this->projdir) == true
     * @assert ($this->invalid_projdir) == \InvalidArgumentException
     * @covers \swl\core\Parser::IsProject
     */
    public function __construct($project)
    {
        if ($this->IsProject($project)) $this->project = $project;
        else
                throw new \InvalidArgumentException("The directory is not a valid project.");
    }

    /**
     * Read all files from the SWL project
     * @param \SplFileInfo $di
     * @return void
     * @assert (new \SplFileInfo($this->projdir)) == null
     */
    private function readFiles(\SplFileInfo $di)
    {
        if ($di->isDot()) return;

        if ($di->getPathInfo()->isDir())
        {
            $this->readFiles($di->getFileInfo());
            return;
        }

        if ($di->getExtension() !== '.swl') return;

        \array_push($this->swl_files, new \SplFileObject($di->getFilename()));
    }

    public function Parse()
    {
        $this->readFiles(new \SplFileInfo($this->project));
        $this->compileSintaxes();
    }

    /**
     * Verify if the directory is a valid project
     * @param string $project
     * @return boolean
     * @throws \InvalidArgumentException
     * @assert (__DIR__) == false
     * @assert ($this->projdir) == true
     * @assert ($this->invalid_projdir) == \InvalidArgumentException
     */
    public function IsProject($project = null)
    {
        try
        {
            $project = $project? : $this->project;

            $doc = new \DOMDocument();
            return \is_string($project) && \is_dir($project) && \is_file($project . '/swlproject.xml') &&
                    !\is_dir($project . '/swlproject.xml')
                    && $doc->load($project . '/swlproject.xml')
                    && $doc->schemaValidate(__DIR__ . '/SWLProject.xsd');
        }
        catch (\Exception $ex)
        {
            throw new \InvalidArgumentException("The directory is not a valid project.",
                                                $ex->getCode(), $ex);
        }
    }

    private function compileSintaxes()
    {
        foreach ($this->swl_files as $file)
        {
            $lex = new Lexer($file);
        }
    }

}
