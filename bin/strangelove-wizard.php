#! /usr/bin/env php
<?php

require getcwd() . '/vendor/autoload.php';

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

(new SingleCommandApplication())
        ->setCode(function (InputInterface $input, OutputInterface $output) {
            $io = new SymfonyStyle($input, $output);
            $io->title('Strangelove Installation Wizard');

            $host = $io->ask("What is the MongoDb server name or IP", 'localhost');
            $port = $io->ask("What is the MongoDb server port", 27017);

            $database = $io->ask("What is the database name", basename(getcwd()));

            $content = Yaml::dump([
                        'strangelove' => [
                            'mongodb' => [
                                'url' => "mongodb://$host:$port",
                                'dbname' => $database
                            ]
                        ]
            ], 3);

            $target = getcwd() . '/config/packages/strangelove.yaml';
            if ($io->confirm("Are you sure you want to create the configuration file '$target' (Warning : any existing file will be overwritten)")) {
                file_put_contents($target, $content);
                $io->success("Configuration successfully created");
            }
        })
        ->run();
