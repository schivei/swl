<?php

namespace swl\management\_private;

/**
 * Description of Commands
 *
 * @author schivei
 */
class Commands
{

    private $_cmd;

    public function __construct($cmd)
    {
        $this->_cmd = $cmd;
    }

    public function create_project($name)
    {
        if (\file_exists($name))
                throw new \BadMethodCallException("The '{$name}' file already exists.");

        if ($this->projectExists($name, realpath("./{$name}")))
                throw new \BadMethodCallException("The project '{$name}' already exists.");

        $dirs = [
            '/bin',
            '/configs',
            '/controllers',
            '/libraries',
            '/models',
            '/public',
            '/vendor',
            '/views/home'
        ];

        foreach ($dirs as $dir) \mkdir($name . $dir, 0775, true);

        $tmpl = \swl\FWPATH . '/templates/';

        \touch($name . $dirs[1] . '/databases.swl');

        $content = \file_get_contents($tmpl . 'databasesCFG.swl');

        \file_put_contents($name . $dirs[1] . '/databases.swl', $content);

        \touch($name . $dirs[2] . '/Home.swl');

        $content = \file_get_contents($tmpl . 'HomeController.swl');

        \file_put_contents($name . $dirs[2] . '/Home.swl', $content);

        \touch($name . $dirs[7] . '/index.html');

        $content = \file_get_contents($tmpl . 'indexView.html');

        \file_put_contents($name . $dirs[7] . '/index.html', $content);

        \touch($name . "/{$name}.swlproj");

        $content = \file_get_contents($tmpl . 'project.swlproj');

        \file_put_contents($name . "/{$name}.swlproj",
                           \sprintf($content, $content));

        $this->makeProject($name, \realpath("./{$name}"));

        return true;
    }

    public function run()
    {
        if (preg_match('/^(create project )([_a-zA-Z][A-Za-z0-9_]+)$/',
                       $this->_cmd))
        {
            $this->create_project(preg_replace('/^(create project )([_a-zA-Z][A-Za-z0-9_]+)$/',
                                               '$2', $this->_cmd));
        }
        else if (preg_match('/^(compile)$/', $this->_cmd))
        {
            $this->compile();
        }
        else if (preg_match('/^(check )(.+)$/', $this->_cmd))
        {
            $this->check_file(\preg_replace('/^(check )(.+)$/', '$2',
                                            $this->_cmd));
        }
        else if (preg_match('/^(publish)$/', $this->_cmd))
        {
            $this->publish();
        }
        else if (preg_match('/^(rebind projects)$/', $this->_cmd))
        {
            $this->rebind();
        }
        else
        {
            throw new \BadMethodCallException("Comando inválido!");
        }
    }

    public function compile()
    {

    }

    public function check_file($file)
    {

    }

    public function publish()
    {

    }

    private function projectExists($name, $location)
    {
        $db = $this->prjDB(true);

        $_ = function ($str)
        {
            return \SQLite3::escapeString($str);
        };

        $total = $db->querySingle(
                \sprintf("select count(*) as total from prj where name = '%s' and directory = '%s'",
                         $_($name), $_($location)));

        $db->close();

        return ((is_numeric($total) && $total > 1) || (is_array($total) && count($total) >
                1));
    }

    /**
     * @return \SQLite3
     */
    private function prjDB($readonly = false)
    {
        if (!class_exists('\SQLite3'))
        {
            throw new \Exception("Please, install the sqlite extension for php!");
        }

        $flags = (\file_exists(\swl\FWPATH . '/.db')) ? ($readonly ? 1 : 2) : (2 |
                4);

        $db = new \SQLite3(\swl\FWPATH . '/.db', $flags);
        $db->enableExceptions(true);

        if ($flags === (2 | 4))
        {
            $result = $db->query(<<<'QRY'
create table prj {
    name varchar[120];
    directory varchar[255];
};
QRY
            );

            $mesg = $db->lastErrorMsg();
            $code = $db->lastErrorCode();

            $db->close();


            if (!$mesg)
            {
                throw new Exception($mesg, $code);
            }

            $result->finalize();

            $db->close();

            $db = new \SQLite3(\swl\FWPATH . '/.db', ($readonly ? 1 : 2));
            $db->enableExceptions(true);
        }

        return $db;
    }

    public function rebind()
    {
        $db = $this->prjDB();

        $result = $db->query("select * from prj");

        $_ = function ($str)
        {
            return \SQLite3::escapeString($str);
        };

        if ($result)
        {
            $deletes = [];

            while ($row       = $result->fetchArray())
                    if (!\file_exists($row['directory']))
                        $deletes[] = (object) $row;

            $result->finalize();

            for ($i = 0; $i < count($deletes); $i++)
            {
                $db->exec(sprintf("delete from prj where name = '%s' and directory = '%s'",
                                  $_($deletes[$i][0]), $_($deletes[$i][1])));
            }
        }

        $db->close();
    }

    private function makeProject($name, $directory)
    {
        $db = $this->prjDB();

        $_ = function ($str)
        {
            return \SQLite3::escapeString($str);
        };

        $ok = $db->exec(
                \sprintf("insert into prj values ('%s', '%s')", $_($name),
                                                                   $_($directory)));

        $db->close();

        return $ok;
    }

}
