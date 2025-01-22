<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoCategoryNewsSorting\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\System;

#[AsCallback('tl_module', 'config.onload')]
class ModuleNewsOrderOptionsListener
{
    public function __invoke(DataContainer $dc): void
    {
        $callback = $GLOBALS['TL_DCA'][$dc->table]['fields']['news_order']['options_callback'] ?? static fn (): array => [];

        $GLOBALS['TL_DCA'][$dc->table]['fields']['news_order']['options_callback'] = static function () use ($callback, $dc): array {
            $defaultOptions = [];

            if (\is_callable($callback)) {
                $defaultOptions = $callback($dc);
            } elseif (\is_array($callback)) {
                $defaultOptions = System::importStatic($callback[0])->{$callback[1]}($dc);
            }

            if ($dc->activeRecord && 'newsmenu' === $dc->activeRecord->type) {
                return $defaultOptions;
            }

            return array_merge($defaultOptions, ['category_sorting']);
        };
    }
}
