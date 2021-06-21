<?php

namespace Osio\ProductsMassDelete\Console;

use Magento\Catalog\Model\Product\Attribute\Source\Status;
use \Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Index extends Command
{

    const NAME = 'osio:products';

    /**
     * @var CollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var Status
     */
    private $productStatus;

    /**
     * Index constructor.
     * @param CollectionFactory $productCollectionFactory
     * @param Status $productStatus
     */
    public function __construct(
        CollectionFactory $productCollectionFactory,
        Status $productStatus
    )
    {
        parent::__construct(self::NAME);
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productStatus = $productStatus;
    }

    protected function configure()
    {
        $this->setDescription('OSIO');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->getProductCollection()->count());
    }

    /**
     * @return Collection
     */
    private function getProductCollection(): Collection
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToFilter('status', ['in' => Status::STATUS_ENABLED]);

        return $collection;
    }

}