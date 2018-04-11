<?php declare(strict_types=1);

namespace AppBundle\Command;

use AppBundle\Widget\WidgetPreparer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WidgetCommand extends Command
{
    /** @var WidgetPreparer $widgetPreparer */
    protected $widgetPreparer;

    public function __construct(?string $name = null, WidgetPreparer $widgetPreparer)
    {
        $this->widgetPreparer = $widgetPreparer;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('fahrradstadt:widget')
            ->setDescription('Refresh widgets')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->widgetPreparer->prepare();
    }
}
