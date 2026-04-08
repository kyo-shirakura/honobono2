<?php

namespace Customize\Controller\Mypage;

use Customize\Form\Type\Admin\SearchReportType;
use Customize\Repository\ReportRepository;
use Eccube\Controller\AbstractController;
use Eccube\Entity\BaseInfo;
use Eccube\Entity\Customer;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Front\CustomerLoginType;
use Eccube\Repository\BaseInfoRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MypageController extends AbstractController
{
    /**
     * @var BaseInfo
     */
    protected $BaseInfo;

    /**
     * @var ReportRepository
     */
    protected $reportRepository;

    /**
     * MypageController constructor.
     *
     * @param BaseInfoRepository $baseInfoRepository
     * @param ReportRepository $reportRepository
     */
    public function __construct(
        BaseInfoRepository $baseInfoRepository,
        ReportRepository $reportRepository
    ) {
        $this->BaseInfo = $baseInfoRepository->get();
        $this->reportRepository = $reportRepository;
    }

    /**
     * ログイン画面.
     *
     * @Route("/mypage/login", name="mypage_login", methods={"GET", "POST"})
     * @Template("Mypage/login.twig")
     */
    public function login(Request $request, AuthenticationUtils $utils)
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            log_info('認証済のためログイン処理をスキップ');

            return $this->redirectToRoute('mypage');
        }

        /** @var \Symfony\Component\Form\FormInterface $form */
        $builder = $this->formFactory
            ->createNamedBuilder('', CustomerLoginType::class);

        $builder->get('login_memory')->setData((bool) $request->getSession()->get('_security.login_memory'));

        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $Customer = $this->getUser();
            if ($Customer instanceof Customer) {
                $builder->get('login_email')
                    ->setData($Customer->getEmail());
            }
        }

        $event = new EventArgs(
            [
                'builder' => $builder,
            ],
            $request
        );
        $this->eventDispatcher->dispatch($event, EccubeEvents::FRONT_MYPAGE_MYPAGE_LOGIN_INITIALIZE);

        $form = $builder->getForm();

        return [
            'error' => $utils->getLastAuthenticationError(),
            'form' => $form->createView(),
        ];
    }

    /**
     * マイページ.
     *
     * @Route("/mypage/", name="mypage", methods={"GET"})
     * @Template("Mypage/index.twig")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $Customer = $this->getUser();

        // 購入処理中/決済処理中ステータスの受注を非表示にする.
        $this->entityManager
            ->getFilters()
            ->enable('incomplete_order_status_hidden');

        return [
        ];
    }

    /**
     * 業務報告一覧.
     *
     * @Route("/mypage/report", name="mypage_report", methods={"GET", "POST"})
     * @Template("Mypage/report_list.twig")
     */
    public function report(Request $request, PaginatorInterface $paginator)
    {
        if (!$this->BaseInfo->isOptionFavoriteProduct()) {
            throw new NotFoundHttpException();
        }
        // 顧客情報取得
        $Customer = $this->getUser();

        $builder = $this->formFactory
            ->createBuilder(SearchReportType::class);
        $searchForm = $builder->getForm();
        $searchData = [];

        if ('POST' === $request->getMethod()) {
            $searchForm->handleRequest($request);
            $searchData = $searchForm->getData();
        }

        // 業務報告取得
        $searchData['Customer'] = $Customer;
        if( !isset($searchData['working_ym']) ){
            $searchData['working_ym'] = new \DateTime();
        }

        $qb = $this->reportRepository->getQueryBuilderBySearchData($searchData);
        $pagination = $paginator->paginate(
            $qb,
            $request->get('pageno', 1),
            $this->eccubeConfig['eccube_search_pmax'],
            ['wrap-queries' => true]
        );

        return [
            'searchForm' => $searchForm->createView(),
            'searchData' => $searchData,
            'pagination' => $pagination,
            'page_no' => $request->get('pageno', 1),
            'Customer' => $Customer,
        ];
    }

    /**
     * 契約情報を表示する.
     *
     * @Route("/mypage/favorite", name="mypage_business", methods={"GET"})
     * @Template("Mypage/business.twig")
     */
    public function business(Request $request, PaginatorInterface $paginator)
    {
        if (!$this->BaseInfo->isOptionFavoriteProduct()) {
            throw new NotFoundHttpException();
        }
        $Customer = $this->getUser();

        // paginator
        $qb = $this->customerFavoriteProductRepository->getQueryBuilderByCustomer($Customer);

        $event = new EventArgs(
            [
                'qb' => $qb,
                'Customer' => $Customer,
            ],
            $request
        );
        $this->eventDispatcher->dispatch($event, EccubeEvents::FRONT_MYPAGE_MYPAGE_FAVORITE_SEARCH);

        $pagination = $paginator->paginate(
            $qb,
            $request->get('pageno', 1),
            $this->eccubeConfig['eccube_search_pmax'],
            ['wrap-queries' => true]
        );

        return [
            'pagination' => $pagination,
        ];
    }

}
