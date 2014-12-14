<?php

namespace swl\core\sintax;

use \swl\core\exceptions\SintaxException,
    \swl\core\LexerCombinations,
    \swl\core\sintax\ISintax,
    \swl\core\Token;

/**
 * Description of SintaxController
 *
 * @author schivei
 */
class SintaxController implements ISintax
{

    /**
     * @var LexerCombinations
     */
    private $lex;
    private $prefiles = [];
    private $filename = '';

    /**
     * @var Token
     */
    private $name;

    /**
     * @var Token
     */
    private $extends;

    /**
     * @var SintaxAction[]
     */
    private $actions;

    public function __construct(LexerCombinations $lex)
    {
        $this->lex = $lex;

        $this->analize();
    }

    /**
     * @return void
     * @assert () == new SintaxException()
     * @throws SintaxException
     */
    public function analize()
    {
        $this->compileEssentials();
        $this->compileMembers();
        $this->compileEvents();
        $this->compileActions();
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

    private function compileEssentials()
    {
        $tokens = &$this->lex->GetTokens();

        /* @var $last Token */
        $last = null;

        $cont = null;
        $dob  = null;

        /* @var $token Token */
        foreach ($tokens as $token)
        {
            $valid = $token->getType() === 'T_IDENTIFIER' && !is_null($last);
            if (\is_null($this->name) && $valid && $last->getType() === 'T_CONTROLLER')
            {
                $this->name = $token;
            }
            else if (\is_null($this->extends) && $valid && $last->getType() === 'T_DOUBLECOMMA')
            {
                $this->extends = $token;
            }
            else if ($token->getType() === 'T_CONTROLLER')
            {
                $cont = $token;
            }
            else if ($token->getType() === 'T_DOUBLECOMMA')
            {
                $dob = $token;
            }

            $last = $token;
        }

        if (\is_null($this->name))
                throw new SintaxException("The declared controller dont has a identifier.",
                                          $cont);

        if (\is_null($this->extends))
        {
            if (!\is_null($dob))
                    throw new SintaxException("The declared controller dont has a extends identifier.",
                                              $dob);

            $this->extends = new Token("T_IDENTIFIER", "\silly\core\Controller",
                                       $this->name->getLine(),
                                       $this->name->getPosition() + 3,
                                       $cont->getFile());
        }
    }

    private function compileMembers()
    {

    }

    private function compileEvents()
    {

    }

    private function compileActions()
    {

    }

    public function checkOpenCloseStatements()
    {

    }

}
