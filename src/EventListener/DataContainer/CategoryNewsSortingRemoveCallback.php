<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoCategoryNewsSorting\EventListener\DataContainer;

use Contao\CoreBundle\Exception\RedirectResponseException;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CategoryNewsSortingRemoveCallback
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly Connection $db,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(): void
    {
        $request = $this->requestStack->getCurrentRequest();

        \assert('tl_category_news_sorting' === $request->query->get('table'));
        \assert('removeCategoryNewsSorting' === $request->query->get('key'));

        $this->db->delete('tl_category_news_sorting', ['pid' => $request->query->get('id')]);

        throw new RedirectResponseException($this->urlGenerator->generate('contao_backend', ['do' => 'news', 'table' => 'tl_news_category', 'ref' => $request->attributes->get('_contao_referer_id')], UrlGeneratorInterface::ABSOLUTE_URL));
    }
}
