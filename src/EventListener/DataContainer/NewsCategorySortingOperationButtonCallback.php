<?php

declare(strict_types=1);

namespace InspiredMinds\ContaoCategoryNewsSorting\EventListener\DataContainer;

use Contao\Backend;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\Image;
use Contao\StringUtil;
use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[AsCallback(table: 'tl_news_category', target: 'list.operations.sorting.button')]
class NewsCategorySortingOperationButtonCallback
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly Connection $db,
    ) {
    }

    public function __invoke(array $row, string|null $href, string $label, string $title, string|null $icon, string $attributes): string
    {
        if (!$this->authorizationChecker->isGranted(ContaoCorePermissions::USER_CAN_EDIT_FIELDS_OF_TABLE, 'tl_news_category')) {
            return '';
        }

        if (!$this->authorizationChecker->isGranted(ContaoCorePermissions::USER_CAN_EDIT_FIELDS_OF_TABLE, 'tl_news')) {
            return '';
        }

        if (!$this->db->fetchOne('SELECT TRUE FROM tl_category_news_sorting WHERE pid = ?', [$row['id']])) {
            $attributes .= ' style="filter: grayscale(1); opacity: 0.5"';
        }

        return \sprintf(
            '<a href="%s" title="%s"%s>%s</a> ',
            Backend::addToUrl($href.'&amp;id='.$row['id']),
            StringUtil::specialchars($title),
            $attributes,
            Image::getHtml($icon, $label),
        );
    }
}
