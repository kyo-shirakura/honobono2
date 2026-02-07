<?php
namespace Customize\Controller\Admin\BusinessPlan;

use Customize\Entity\Master\BusinessConfig;
use Customize\Entity\BusinessPlan;
use Customize\Form\Type\Admin\BusinessPlanType;
use Customize\Form\Type\Admin\SearchConfigType;
use Customize\Repository\BusinessPlanRepository;
use Eccube\Common\Constant;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\Master\PageMaxRepository;
use Eccube\Util\FormUtil;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BusinessPlanController extends AbstractController
{
    /**
     * @var PageMaxRepository
     */
    protected $pageMaxRepository;

    /**
     * @var BusinessPlanRepository
     */
    protected $businessPlanRepository;

    /**
     * @var ValidatorInterface
     */
    protected $validator;


    /**
     * constructor.
     *
     * @param PageMaxRepository $pageMaxRepository
     * @param BusinessPlanRepository $businessPlanRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(
        PageMaxRepository $pageMaxRepository,
        BusinessPlanRepository $businessPlanRepository,
        ValidatorInterface $validator
    ) {
        $this->pageMaxRepository = $pageMaxRepository;
        $this->businessPlanRepository = $businessPlanRepository;
        $this->validator = $validator;
    }

     /**
      * 一覧画面.
      * @Route("/%eccube_admin_route%/business_plan", name="admin_business_plan", methods={"GET", "POST"})
      * @Route("/%eccube_admin_route%/business_plan/page/{page_no}", requirements={"page_no" = "\d+"}, name="business_plan_page", methods={"GET", "POST"})
      * @Template("@admin/BusinessPlan/index.twig")
      */
      public function index(Request $request, PaginatorInterface $paginator, $page_no = null)
      {
          $builder = $this->formFactory
              ->createBuilder(SearchConfigType::class);
          $searchForm = $builder->getForm();
          $firstAccess = 0;

          /**
           * ページの表示件数は, 以下の順に優先される.
           * - リクエストパラメータ
           * - セッション
           * - デフォルト値
           * また, セッションに保存する際は mtb_page_maxと照合し, 一致した場合のみ保存する.
           **/
          $page_count = $this->session->get('eccube.admin.config.search.page_count',
              $this->eccubeConfig->get('eccube_default_page_count'));

          $page_count_param = (int) $request->get('page_count');
          $pageMaxis = $this->pageMaxRepository->findAll();

          if ($page_count_param) {
              foreach ($pageMaxis as $pageMax) {
                  if ($page_count_param == $pageMax->getName()) {
                      $page_count = $pageMax->getName();
                      $this->session->set('eccube.admin.config.search.page_count', $page_count);
                      break;
                  }
              }
          }

          if ('POST' === $request->getMethod()) {
              $searchForm->handleRequest($request);

              if ($searchForm->isValid()) {
                  /**
                   * 検索が実行された場合は, セッションに検索条件を保存する.
                   * ページ番号は最初のページ番号に初期化する.
                   */
                  $page_no = 1;
                  $searchData = $searchForm->getData();

                  // 検索条件, ページ番号をセッションに保持.
                  $this->session->set('eccube.admin.config.search', FormUtil::getViewData($searchForm));
                  $this->session->set('eccube.admin.config.search.page_no', $page_no);
              } else {
                  // 検索エラーの際は, 詳細検索枠を開いてエラー表示する.
                  return [
                      'searchForm' => $searchForm->createView(),
                      'searchData' => [],
                      'pagination' => [],
                      'pageMaxis' => $pageMaxis,
                      'page_no' => $page_no,
                      'page_count' => $page_count,
                      'has_errors' => true,
                      'firstAccess' => 1,
                  ];
              }
          } else {
              if (null !== $page_no || $request->get('resume')) {
                  /*
                   * ページ送りの場合または、他画面から戻ってきた場合は, セッションから検索条件を復旧する.
                   */
                  if ($page_no) {
                      // ページ送りで遷移した場合.
                      $this->session->set('eccube.admin.config.search.page_no', (int) $page_no);
                  } else {
                      // 他画面から遷移した場合.
                      $page_no = $this->session->get('eccube.admin.config.search.page_no', 1);
                  }
                  $viewData = $this->session->get('eccube.admin.config.search', []);
                  $searchData = FormUtil::submitAndGetData($searchForm, $viewData);
              } else {
                  /**
                   * 初期表示の場合.
                   */
                  $page_no = 1;
                  $viewData = [];
                  $firstAccess = 1;

                  if ($statusId = (int) $request->get('order_status_id')) {
                      $viewData = ['status' => $statusId];
                  }

                  $searchData = FormUtil::submitAndGetData($searchForm, $viewData);

                  // セッション中の検索条件, ページ番号を初期化.
                  $this->session->set('eccube.admin.config.search', $viewData);
                  $this->session->set('eccube.admin.config.search.page_no', $page_no);
              }
          }

          // 法人
          $searchData = array('contractor_id' => '1');
          $qb = $this->businessPlanRepository->getQueryBuilderBySearchData($searchData);
          $paginationCorp = $paginator->paginate(
              $qb,
              $page_no,
              $page_count
          );

          // 個人
          $searchData = array('contractor_id' => '2');
          $qb = $this->businessPlanRepository->getQueryBuilderBySearchData($searchData);
          $paginationPerson = $paginator->paginate(
              $qb,
              $page_no,
              $page_count
          );

          return [
              'searchForm' => $searchForm->createView(),
              'searchData' => $searchData,
              'paginationPerson' => $paginationPerson,
              'paginationCorp' => $paginationCorp,
              'pageMaxis' => $pageMaxis,
              'page_no' => $page_no,
              'page_count' => $page_count,
              'has_errors' => false,
              'firstAccess' => $firstAccess,
              'BusinessConstractors' => BusinessConfig::BUSINESS_CONTRACTOR,
              'BusinessKinds' => BusinessConfig::BUSINESS_KIND,
  //            'OrderStatuses' => $this->orderStatusRepository->findBy([], ['sort_no' => 'ASC']),
          ];
      }
}
