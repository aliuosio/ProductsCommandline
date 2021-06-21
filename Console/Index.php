<?php

namespace Aliuosio\ProductsMassDelete\Console;

use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Index extends Command
{

    /** @var string */
    const NAME = 'aliuosio:products:delete';

    /** @var CollectionFactory */
    private $productCollectionFactory;

    /** @var State */
    private $state;

    public function __construct(
        State $state,
        CollectionFactory $productCollectionFactory
    )
    {
        parent::__construct(self::NAME);
        $this->productCollectionFactory = $productCollectionFactory;
        $this->state = $state;
    }

    protected function configure()
    {
        $this->setDescription('Delete all disabled products');

        parent::configure();
    }

    /**
     * @throws LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->state->setAreaCode(Area::AREA_FRONTEND);
        $output->writeln(
            $this->getProductCollection()
                ->delete()
                ->count()
        );
    }

    private function getProductCollection(): Collection
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToFilter('status', ['in' => Status::STATUS_DISABLED]);

        return $collection;
    }

}