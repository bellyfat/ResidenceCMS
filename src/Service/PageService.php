<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Menu;
use App\Entity\Page;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

final class PageService extends AbstractService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        parent::__construct($container);
        $this->em = $entityManager;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function create(Page $page)
    {
        // Save page
        $this->save($page);
        $this->clearCache('pages_count');
        $this->addFlash('success', 'message.created');

        // Add a menu item
        if (true === $page->getShowInMenu()) {
            $menu = new Menu();
            $menu->setTitle($page->getTitle() ?? '');
            $menu->setUrl('/info/'.($page->getSlug() ?? ''));
            $this->save($menu);
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function countAll(): int
    {
        $cache = new FilesystemAdapter();

        $count = $cache->get('pages_count', function () {
            return $this->em->getRepository(Page::class)->countAll();
        });

        return (int) $count;
    }

    public function save(object $object): void
    {
        $this->em->persist($object);
        $this->em->flush();
    }

    public function remove(object $object): void
    {
        $this->em->remove($object);
        $this->em->flush();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function delete(Page $page): void
    {
        // Delete page
        $this->remove($page);
        $this->clearCache('pages_count');
        $this->addFlash('success', 'message.deleted');

        // Delete a menu item
        $menu = $this->em->getRepository(Menu::class)->findOneBy(['url' => '/info/'.($page->getSlug() ?? '')]);
        if ($menu) {
            $this->remove($menu);
        }
    }
}
