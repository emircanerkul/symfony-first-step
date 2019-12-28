<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class HelloCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:hello';

    protected function configure()
    {
        $this
            ->setDescription('Get hi from Alien!')
            ->addArgument('name', InputArgument::OPTIONAL, 'Your name.')
            ->setHelp('This command allows you to talk with aliens');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Output Example
        $output->writeln([
            'Hi From Alien',
            '==============',
            '',
        ]);

        //Input Example
        $output->writeln('Whoa! HI ' . ($input->getArgument('name') !== null ? $input->getArgument('name') : "BRO"));

        //Formatter Example
        $formatter = $this->getHelper('formatter');
        $formattedBlocks = [''];
        array_push($formattedBlocks, $formatter->formatBlock(['Info formatter example'], 'info'));
        array_push($formattedBlocks, $formatter->formatBlock(['Comment formatter example'], 'comment'));
        array_push($formattedBlocks, $formatter->formatBlock(['Question formatter example'], 'question'));
        array_push($formattedBlocks, $formatter->formatBlock(['Error formatter example'], 'error'));
        $output->writeln($formattedBlocks);
        $output->writeln('');

        //Process Example
        $this->getHelper('process')->run($output,  new Process(['ls', './']));
        $output->writeln('');

        //Table Example
        $table = new Table($output);
        $table->setHeaderTitle('Books');
        $table->setFooterTitle('Page 1/2');
        $table
            ->setHeaders(['ISBN', 'Title', 'Author'])
            ->setRows([
                ['99921-58-10-7', 'Divine Comedy', 'Dante Alighieri'],
                ['9971-5-0210-0', 'A Tale of Two Cities', 'Charles Dickens'],
                new TableSeparator(),
                ['960-425-059-0', 'The Lord of the Rings', 'J. R. R. Tolkien'],
                ['80-902734-1-6', 'And Then There Were None', 'Agatha Christie'],
                [new TableCell('This value spans 3 columns.', ['colspan' => 3])],
            ]);
        $table->setStyle('box-double'); //box, borderless, default, compact, box-double
        $table->render();

        return 0;
    }
}
