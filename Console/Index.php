<?php

namespace Aliuosio\ProductsMassDelete\Console;

use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
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
     * Index constructor.
     * @param CollectionFactory $productCollectionFactory
     */
    public function __construct(
        CollectionFactory $productCollectionFactory
    )
    {
        parent::__construct(self::NAME);
        $this->productCollectionFactory = $productCollectionFactory;
    }

    protected function configure()
    {
        $this->setDescription('OSIO');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(
            $this->getProductCollection()->delete()
                ->count()
        );
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