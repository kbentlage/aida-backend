<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use Wrep\Daemonizable\Command\EndlessContainerAwareCommand;

use App\Entity\DeviceValue;
use App\Entity\LogicalDevice;

use App\Entity\Floor;

class AidaSmartmeterProcessorCommand extends EndlessContainerAwareCommand
{
    protected static $defaultName = 'app:aida-smartmeter-processor';

    private $entityManager;

    public function __construct($name = null, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

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
        $baseTopic = getenv('MQTT_TOPIC_SMARTMETER');

        switch($message->topic)
        {
            case $baseTopic.'/electricity/kwh-low':
                $this->updateDeviceValue(1, 'kwh_low', $message->payload);
                break;

            case $baseTopic.'/electricity/kwh-high':
                $this->updateDeviceValue(1, 'kwh_high', $message->payload);
                break;

            case $baseTopic.'/electricity/kwh-generated-low':
                $this->updateDeviceValue(1, 'kwh_generated_low', $message->payload);
                break;

            case $baseTopic.'/electricity/kwh-generated-high':
                $this->updateDeviceValue(1, 'kwh_generated_high', $message->payload);
                break;

            case $baseTopic.'/electricity/kwh-indicator':
                $this->updateDeviceValue(1, 'kwh_indicator', $message->payload);
                break;

            case $baseTopic.'/electricity/current-watt':
                $this->updateDeviceValue(1, 'watt', $message->payload);
                break;

            case $baseTopic.'/electricity/current-watt-generated':
                $this->updateDeviceValue(1, 'watt_generated', $message->payload);
                break;

            case $baseTopic.'/gas/m3':
                $this->updateDeviceValue(1, 'gas', $message->payload);
                break;
        }
    }

    /**
     * Update Device Value
     *
     * @param $id
     * @param $property
     * @param $value
     */
    protected function updateDeviceValue($id, $property, $value)
    {
        // initialize repositories
        $logicalDeviceRepository    = $this->getContainer()->get('doctrine')->getRepository(LogicalDevice::class);
        $deviceValueRepository      = $this->getContainer()->get('doctrine')->getRepository(DeviceValue::class);

        // find logical device
        $logicalDevice = $logicalDeviceRepository->findOneByIdAndProperty($id, $property);

        if($logicalDevice)
        {
            // get device value
            $deviceValue = $deviceValueRepository->findOneByLogicalDeviceId($logicalDevice->getId());

            // device has a current value
            if($deviceValue)
            {
                // if new value is different than current one
                if($deviceValue->getValue() != $value)
                {
                    $deviceValue->setValue($value);
                    $deviceValue->setModifyDate(new \DateTime('now'));

                    $this->entityManager->persist($deviceValue);
                }
            }
            // device don't have a current value, create one
            else
            {
                $newDeviceValue = new DeviceValue();

                $newDeviceValue->setLogicalDeviceId($logicalDevice->getId());
                $newDeviceValue->setValue($value);
                $newDeviceValue->setCreateDate(new \DateTime('now'));

                $this->entityManager->persist($newDeviceValue);
            }

            $this->entityManager->flush();
        }
    }
}
