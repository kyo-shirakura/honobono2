<?php

namespace Customize\Controller\Mypage;

use Customize\Entity\Business;
use Customize\Entity\Master\BusinessConfig;
use Customize\Form\Type\Admin\SearchReportType;
use Customize\Repository\BusinessRepository;
use Customize\Repository\BusinessPlanRepository;
use Customize\Repository\ReportRepository;
use Customize\Repository\StaffRepository;
use Eccube\Controller\AbstractController;
use Eccube\Entity\BaseInfo;
use Eccube\Entity\Customer;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Front\CustomerLoginType;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\CustomerRepository;
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
     * @var BusinessRepository
     */
    protected $businessRepository;

    /**
     * @var BusinessPlanRepository
     */
    protected $businessPlanRepository;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var ReportRepository
     */
    protected $reportRepository;

    /**
     * MypageController constructor.
     *
     * @param BaseInfoRepository $baseInfoRepository
     * @param BusinessPlanRepository $businessPlanRepository
     * @param CustomerRepository $customerRepository
     * @param ReportRepository $reportRepository
     */
    public function __construct(
        BaseInfoRepository $baseInfoRepository,
        BusinessRepository $businessRepository,
        BusinessPlanRepository $businessPlanRepository,
        CustomerRepository $customerRepository,
        ReportRepository $reportRepository,
        StaffRepository $staffRepository
    ) {
        $this->BaseInfo = $baseInfoRepository->get();
        $this->businessRepository = $businessRepository;
        $this->businessPlanRepository = $businessPlanRepository;
        $this->customerRepository = $customerRepository;
        $this->reportRepository = $reportRepository;
        $this->staffRepository = $staffRepository;
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
     * @Route("/mypage/business", name="mypage_business", methods={"GET"})
     * @Template("Mypage/business.twig")
     */
    public function business(Request $request, PaginatorInterface $paginator)
    {
        if (!$this->BaseInfo->isOptionFavoriteProduct()) {
            throw new NotFoundHttpException();
        }
        $Customer = $this->getUser();

        // 案件を取得
        $Business = $this->businessRepository->findBy( array( 'Customer' => $Customer , 'Status' => array(3,4) ), ['id' => 'ASC']);
        $BusinessRow = $this->businessRepository->findOneBy( array( 'Customer' => $Customer , 'Status' => array(3,4) ), ['id' => 'ASC']);
        if(! $Business ){
            throw new NotFoundHttpException();
        }

        // 基本料金を取得
        $BusinessPlan = $this->businessPlanRepository->getActivePlan([
            'contractor_id' => $BusinessRow->getContractorId()   // 契約対象(法人/個人)
            ,'kind_id' => $BusinessRow->getKindId() // 契約種類(定期/単発)
        ]);

        return [
            'Business' => $Business,
            'Customer' => $Customer,
            'BusinessPlan' => $BusinessPlan,
            'BusinessConstractors' => BusinessConfig::BUSINESS_CONTRACTOR,
            'BusinessKinds' => BusinessConfig::BUSINESS_KIND,
            'BusinessRegularType' => BusinessConfig::BUSINESS_REGULAR_TYPE,
            'BusinessWeekly' => BusinessConfig::BUSINESS_WEEKLY,
            'PaymentType' => BusinessConfig::BUSINESS_PAYMENT,
        ];
    }

}
