<?php
declare(strict_types=1);

namespace Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class ReplaceLatestWithHashCommand extends Command
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure():void
    {
        $this->setName('replace')
            ->addArgument('input', InputArgument::REQUIRED, 'docker-compose.yml file to convert')
            ->addArgument('output', InputArgument::REQUIRED, 'docker-compose.yml file to output')
            ->setDescription('Replace all latest tag with hashed name one');
    }

    public function execute(InputInterface $input, OutputInterface $output):int
    {
        $array = Yaml::parseFile($input->getArgument('input'));
//        unset($array['services']['app']);
        foreach ($array['services'] as $service => $config) {
            $image = $config['image'] ?? null;
            if ($image === null) {
                $output->isDebug() && $output->writeln(sprintf('Skip service "%s" not image', $service));
                continue;
            }
            $imageParts = explode(':', $image);
            if (count($imageParts) === 2) {
                [$_, $tag] = $imageParts;
            } else {
                $tag = 'latest';
                $image .= ':' . $tag;
            }

            if ($tag === 'latest' || $tag === null)  {
                $sha256Image = shell_exec(sprintf("docker image inspect %s | jq '.[0].RepoDigests | .[0]'", $image));
                $sha256Image = trim($sha256Image, " \t\n\r\0\x0B\"");
                $array['services'][$service]['image'] = $sha256Image;
            }
        }

        $yaml = Yaml::dump($array, 7, 3, Yaml::DUMP_OBJECT_AS_MAP  | Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);

        file_put_contents($input->getArgument('output'), $yaml);
        return self::SUCCESS;
    }
}