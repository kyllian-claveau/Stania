<?php

namespace App\Business;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Yaml\Yaml;
use Twig\Environment;

class SidebarBusiness
{
    private const FILE_EXTENSION = '.yaml';

    public function __construct(
        private readonly RouterInterface $router,
        private readonly string          $directoryConfiguration,
        private readonly Environment     $environment,
        private readonly RequestStack    $requestStack
    )
    {

    }

    public function getItems(string $configurationFileName): ?array
    {
        $path = $this->directoryConfiguration . '/' . $configurationFileName . self::FILE_EXTENSION;

        if (!file_exists($path)) {
            return null;
        }

        $items = [];
        $configuration = Yaml::parseFile($path);
        $currentRoute = $this->requestStack->getMainRequest()->attributes->get('_route');

        foreach ($configuration['items'] as $item) {
            $content = $this->environment->render($item['content']);
            $url = $this->router->generate($item['url']['route']);
            $active = in_array($currentRoute, $item['activating_routes']);

            $items[] = [
                'content' => $content,
                'url' => $url,
                'active' => $active,
            ];
        }

        return $items;
    }
}