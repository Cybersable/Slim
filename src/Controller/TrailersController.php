<?php


namespace App\Controller;


use App\Entity\Movie;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use phpDocumentor\Reflection\Types\Object_;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Interfaces\RouteCollectorInterface;
use Twig\Environment;


class TrailersController
{
    /**
     * @var RouteCollectorInterface
     */
    private $routeCollector;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var EntityManager
     */
    private $model;

    /**
     * @var Movie
     */
    private $movie;

    /**
     * TrailersController constructor.
     *
     * @param RouteCollectorInterface $routeCollector
     * @param Environment             $twig
     * @param EntityManagerInterface  $em
     */
    public function __construct(RouteCollectorInterface $routeCollector, Environment $twig, EntityManagerInterface $em)
    {
        $this->routeCollector = $routeCollector;
        $this->twig = $twig;
        $this->em = $em;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $movie
     *
     * @return ResponseInterface
     *
     * @throws HttpBadRequestException
     */
    public function item(ServerRequestInterface $request, ResponseInterface $response, array $movie)
    {
        try {
            $data = $this->twig->render('trailers/item.html.twig', [
                'trailer' => $this->em->getRepository(Movie::class)->findOneBy(['slug' => $movie['slug']])
            ]);
        } catch (\Exception $e) {
            throw new HttpBadRequestException($request, $e->getMessage(), $e);
        }
        $response->getBody()->write($data);

        return $response;
    }
}