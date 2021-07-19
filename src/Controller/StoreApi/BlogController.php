<?php declare(strict_types=1);

namespace Sas\BlogModule\Controller\StoreApi;

use OpenApi\Annotations as OA;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Plugin\Exception\DecorationPatternException;
use Shopware\Core\Framework\Routing\Annotation\Entity;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"store-api"})
 */
class BlogController extends AbstractBlogController
{
    public function __construct(protected EntityRepositoryInterface $blogRepository)
    {
    }

    public function getDecorated(): AbstractBlogController
    {
        throw new DecorationPatternException(self::class);
    }

    /**
     * @Entity("sas_blog_entries")
     * @OA\Get(
     *      path="/blog",
     *      summary="This route can be used to load the sas_blog_entries by specific filters",
     *      operationId="listBlog",
     *      tags={"Store API", "Blog"},
     *      @OA\Parameter(name="Api-Basic-Parameters"),
     *      @OA\Response(
     *          response="200",
     *          description="",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(
     *                  property="total",
     *                  type="integer",
     *                  description="Total amount"
     *              ),
     *              @OA\Property(
     *                  property="aggregations",
     *                  type="object",
     *                  description="aggregation result"
     *              ),
     *              @OA\Property(
     *                  property="elements",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/swag_example_flat")
     *              )
     *          )
     *     )
     * )
     * @Route("/store-api/blog", name="store-api.sas.blog.load", methods={"GET"})
     */
    public function load(Criteria $criteria, SalesChannelContext $context): BlogControllerResponse
    {
        $criteria->addAssociations(['author.salutation', 'blogCategories']);

        return new BlogControllerResponse($this->blogRepository->search($criteria, $context->getContext()));
    }

    /**
     * @Entity("sas_blog_entries")
     * @OA\Get(
     *      path="/blog/{articleId}",
     *      summary="This route can be used to load one entry of sas_blog_entries",
     *      operationId="readExample",
     *      tags={"Store API", "Example"},
     *      @OA\Parameter(name="Api-Basic-Parameters"),
     *      @OA\Response(
     *          response="200",
     *          description="",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(
     *                  property="total",
     *                  type="integer",
     *                  description="Total amount"
     *              ),
     *              @OA\Property(
     *                  property="aggregations",
     *                  type="object",
     *                  description="aggregation result"
     *              ),
     *              @OA\Property(
     *                  property="elements",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/swag_example_flat")
     *              )
     *          )
     *     )
     * )
     * @Route("/store-api/blog/{articleId}", name="store-api.sas.blog.detail", methods={"GET"})
     */
    public function detail(string $articleId, Criteria $criteria, SalesChannelContext $context): BlogControllerResponse
    {
        $criteria->setIds([$articleId]);
        $criteria->addAssociations(['author.salutation', 'blogCategories']);

        return new BlogControllerResponse($this->blogRepository->search($criteria, $context->getContext()));
    }
}
