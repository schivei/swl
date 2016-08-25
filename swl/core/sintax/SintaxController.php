<?php

namespace swl\core\sintax;

use \Iterator,
    \swl\core\exceptions\SintaxException,
    \swl\core\Token;

/**
 * Description of SintaxController
 *
 * @author schivei
 */
class SintaxController extends \swl\core\types\TypeObject implements \swl\core\sintax\ISintax
{

    /**
     * @var Iterator
     */
    private $tokens;
    private $prefiles = [];
    private $filename = '';

    public function __construct(Iterator $tokens)
    {
        $this->tokens = $tokens;

        $this->prefiles['controller'] = '';
        $this->prefiles['routes']     = '';

        $this->analize();
    }

    /**
     * @assert () == new SintaxException()
     * @throws SintaxException
     */
    private function analize()
    {
        $pos = 0;
        /* @var $token Token */
        foreach ($this->tokens as $token)
        {
            if ($token->getType())
            {
                $this->filename = $token->getFile();
                continue;
            }

            if ($pos === 0)
            {
                if ($token->getType() !== 'T_IDENTIFIER')
                        throw new SintaxException("", $token);
            }
        }
    }

    /**
     * @assert () == []
     * @return array
     */
    public function getParsedFiles()
    {
        return [
            $this->filename => $this->prefiles['controller'],
            'routes'        => $this->prefiles['routes']
        ];
    }

    /**
     * @assert () == ''
     * @return string
     */
    public function getAParsedCommentedFile()
    {
        return "//{$this->filename}\n{$this->prefiles['controller']}\n\n\n"
                . "//routes.php\n{$this->prefiles['routes']}";
    }

}
