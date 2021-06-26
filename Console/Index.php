<?php

namespace Aliuosio\ProductsMassDelete\Console;

use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Index extends Command
{

    /** @var int */
    private $pageSize = 250;

    /** @var string */
    const NAME = 'aliuosio:products:delete';

    /** @var CollectionFactory */
    private $productCollectionFactory;

    /** @var State */
    private $state;

    /*** @var Registry */
    private $registry;

    public function __construct(
        State $state,
        CollectionFactory $productCollectionFactory,
        Registry $registry
    ) {
        parent::__construct(self::NAME);
        $this->productCollectionFactory = $productCollectionFactory;
        $this->state = $state;
        $this->registry = $registry;
    }

    protected function configure()
    {
        $this->setDescription('Delete all disabled products');

        parent::configure();
    }

    /**
     * @throws LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode(Area::AREA_GLOBAL);
        if ($this->registry->registry('isSecureArea') === null) {
            $this->registry->register('isSecureArea', true);
        }

        $this->delete($output);
    }

    /**
     * @param OutputInterface $output
     * @return bool
     * @throws LocalizedException
     */
    private function delete(OutputInterface $output): bool
    {
        $this->getProductCollection()->delete();

        if (($this->getProductCollection())->count() > 0) {
            $output->writeln("Products left to delete {$this->getProductCollection()->count()}");
            return $this->delete($output);
        }

        return true;
    }

    /**
     * @return Collection
     * @throws LocalizedException
     */
    private function getProductCollection(): Collection
    {
        return $this->productCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status', array('eq' => Status::STATUS_DISABLED))
            ->setPageSize($this->pageSize)
            ;
    }

}
