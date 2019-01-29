<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2018 Jiří Svěcený
 * @version 17.07.2018
 */

declare(strict_types=1);

namespace App\Models\Menu\Restaurants;

use App\Models\Menu\Item;
use App\Models\Menu\Restaurant;
use Nette\Caching\Cache;
use Nette\Utils\Strings;
use Symfony\Component\DomCrawler\Crawler;


class Jidelna extends Restaurant
{
    /** @var string */
    public const API_LINK = 'http://intranet/?main=jidelna_jidel';

    /** @var int */
    public const PRICE = 33;


    /**
     * Get restaurant name
     * @return string
     */
    function getName(): string
    {
        return 'Školní jídelna VOŠ a SPŠ Jičín';
    }


    /**
     * Get link to restaurant
     * @return string
     */
    function getLink(): string
    {
        return self::API_LINK;
    }


    /**
     * Get restaurant slug
     * @return string
     */
    function getSlug(): string
    {
        return 'skolni-jidelna';
    }


    /**
     * Convert raw data
     */
    function convert(): void
    {
        $html = file_get_contents(self::API_LINK);
        $crawler = new Crawler($html);

        $menu = [];
        $contnt = $crawler->filter('#container #content .odsazeni');

        // Find meals
        $contnt->filter('table')->each(
            function (Crawler $item, int $i) use (&$menu): void {

                $item->filter('tr')->each(
                    function (Crawler $item, int $r) use (&$menu, &$i): void {
                        if ($r == 0) return;

                        // Soup
                        if ($r == 1) {
                            $soup = $item->filter('.jidelnicek-typ-v')->text();
                            preg_match("/(Pol(é|e)vka)( -|)(?<name>\W.*)/", $soup, $matches);

                            if (isset($matches['name'])) {
                                $menu[$i]['soups'][] = new Item(
                                    Strings::firstUpper(Strings::trim($matches['name'])),
                                    self::PRICE
                                );
                            }
                        }

                        // Meal
                        if ($r == 2 || $r == 3) {
                            $data = $item->filter('.jidelnicek-typ-v');

                            if ($data->count() > 0) {
                                $menu[$i]['meals'][] = new Item(
                                    $item->filter('.jidelnicek-typ-v')->text(),
                                    self::PRICE
                                );
                            }
                        }
                    }
                );
            }
        );

        $this->menu = $menu;
    }
}