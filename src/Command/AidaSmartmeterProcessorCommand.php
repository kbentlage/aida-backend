<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Wrep\Daemonizable\Command\EndlessContainerAwareCommand;

use App\Service\DeviceService;

class AidaSmartmeterProcessorCommand extends EndlessContainerAwareCommand
{
    protected static $defaultName = 'app:aida-smartmeter-processor';

    protected $em;

    public function __construct($name = null, EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;

        parent::__construct($name);
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setDescription('Processor for raw Smartmeter data')
            ->setTimeout(0)
        ;
    }

    /**
     * Execute
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->success('Aida Smartmeter Processor is started.');

        // initialize mosquitto client
        $mosquittoClient = new \Mosquitto\Client;

        // initialize mosquitto connection
        $mosquittoClient->setCredentials(getenv('MQTT_USERNAME'), getenv('MQTT_PASSWORD'));
        $mosquittoClient->connect(getenv('MQTT_HOST'));

        // subscribe to topic
        $mosquittoClient->subscribe(getenv('MQTT_TOPIC_SMARTMETER').'/#', 1);

        // connect callback
        $mosquittoClient->onConnect(function($code, $message)
        {
            $this->onConnect($code, $message);
        });

        // message callback
        $mosquittoClient->onMessage(function($message)
        {
            $this->onMessage($message);
        });

        // run code in infinite loop
        $mosquittoClient->loopForever();
    }

    /**
     * On Connect
     *
     * @param $code
     * @param $message
     */
    protected function onConnect($code, $message)
    {

    }

    /**
     * On Message
     *
     * @param $message
     */
    protected function onMessage($message)
    {
        $deviceService = $this->getContainer()->get(DeviceService::class);

        $baseTopic = getenv('MQTT_TOPIC_SMARTMETER');

        switch($message->topic)
        {
            case $baseTopic.'/electricity/kwh-low':
                $deviceService->update(1, $message->payload);
                break;

            case $baseTopic.'/electricity/kwh-high':
                $deviceService->update(2, $message->payload);
                break;

            case $baseTopic.'/electricity/kwh-generated-low':
                $deviceService->update(3, $message->payload);
                break;

            case $baseTopic.'/electricity/kwh-generated-high':
                $deviceService->update(4, $message->payload);
                break;

            case $baseTopic.'/electricity/kwh-indicator':
                $deviceService->update(5, $message->payload);
                break;

            case $baseTopic.'/electricity/current-watt':
                $deviceService->update(6, $message->payload);
                break;

            case $baseTopic.'/electricity/current-watt-generated':
                $deviceService->update(7, $message->payload);
                break;

            case $baseTopic.'/gas/m3':
                $deviceService->update(8, $message->payload);
                break;
        }

        $this->em->flush();
    }
}
